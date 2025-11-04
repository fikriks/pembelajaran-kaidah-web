/**
 * DataTables Helper - Initialization and Common Functions
 * Green Theme Arabic Learning Application
 */

// Global DataTables configuration
window.DataTableHelper = {
    // Default configuration
    defaultConfig: {
        responsive: true,
        autoWidth: false,
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
        language: {
            "sEmptyTable": "Tidak ada data tersedia dalam tabel",
            "sInfo": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            "sInfoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
            "sInfoFiltered": "(disaring dari _MAX_ total data)",
            "sInfoPostFix": "",
            "sInfoThousands": ",",
            "sLengthMenu": "Tampilkan _MENU_ data",
            "sLoadingRecords": "Memuat...",
            "sProcessing": "Memproses...",
            "sSearch": "Cari:",
            "sZeroRecords": "Tidak ditemukan data yang cocok",
            "oAria": {
                "sSortAscending": ": aktifkan untuk mengurutkan kolom secara ascending",
                "sSortDescending": ": aktifkan untuk mengurutkan kolom secara descending"
            }
        },
        initComplete: function() {
            // Add custom styling to DataTables elements
            var api = this.api();

            // Style the search input
            api.columns().every(function() {
                var column = this;
                var searchInput = $('.dataTables_filter input');

                if (searchInput.length && !searchInput.hasClass('enhanced')) {
                    searchInput.addClass('enhanced form-control');
                    searchInput.attr('placeholder', 'Ketik untuk mencari...');
                }
            });

            // Style length select
            var lengthSelect = $('.dataTables_length select');
            if (lengthSelect.length && !lengthSelect.hasClass('enhanced')) {
                lengthSelect.addClass('enhanced form-select');
            }
        }
    },

    // Initialize DataTable with custom configuration
    init: function(selector, customConfig = {}) {
        const config = { ...this.defaultConfig, ...customConfig };
        const table = $(selector).DataTable(config);

        // Add row hover effects
        $(selector + ' tbody').on('mouseenter', 'tr', function() {
            $(this).addClass('hovered');
        }).on('mouseleave', 'tr', function() {
            $(this).removeClass('hovered');
        });

        return table;
    },

    // Configuration for different table types
    configs: {
        // Basic table configuration
        basic: {
            pageLength: 10,
            order: [[0, 'asc']]
        },

        // Large dataset configuration
        large: {
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
            deferRender: true,
            order: [[0, 'asc']]
        },

        // Small dataset configuration
        small: {
            pageLength: 5,
            lengthMenu: [[5, 10, 25, -1], [5, 10, 25, "Semua"]],
            paging: false,
            searching: true,
            info: false
        },

        // Configuration for student tables
        students: {
            pageLength: 15,
            order: [[0, 'asc']], // Sort by NIS
            columnDefs: [
                {
                    targets: -1, // Action column
                    orderable: false,
                    className: 'table-actions text-center'
                },
                {
                    targets: [4], // Status column
                    className: 'text-center'
                }
            ]
        },

        // Configuration for report tables
        reports: {
            pageLength: 20,
            order: [[0, 'desc']], // Sort by date (newest first)
            columnDefs: [
                {
                    targets: -1, // Action column
                    orderable: false
                }
            ]
        }
    },

    // Initialize table by type
    initByType: function(selector, type = 'basic', customConfig = {}) {
        const typeConfig = this.configs[type] || {};
        const config = { ...typeConfig, ...customConfig };
        return this.init(selector, config);
    },

    // Utility function to export table data
    exportData: function(table, format = 'csv') {
        switch(format) {
            case 'csv':
                return new Blob([table.buttons().exportData({
                    modifier: {
                        selected: null
                    }
                })], { type: 'text/csv;charset=utf-8' });
            case 'excel':
                return new Blob([table.buttons().exportData({
                    modifier: {
                        selected: null
                    },
                    format: {
                        header: function(data, columnIdx) {
                            return data;
                        },
                        body: function(data, rowIdx, columnIdx, node) {
                            return data;
                        }
                    }
                })], { type: 'application/vnd.ms-excel' });
            default:
                return null;
        }
    },

    // Utility function to refresh table data
    refresh: function(table) {
        if (table && typeof table.ajax.reload === 'function') {
            table.ajax.reload(null, false);
        } else if (table && typeof table.draw === 'function') {
            table.draw();
        }
    },

    // Utility function to search across all columns
    globalSearch: function(table, searchTerm) {
        if (table && typeof table.search === 'function') {
            table.search(searchTerm).draw();
        }
    },

    // Utility function to clear all filters
    clearFilters: function(table) {
        if (table) {
            // Clear global search
            table.search('').draw();

            // Clear column searches
            table.columns().search('').draw();

            // Reset page length to default
            table.page.len(this.defaultConfig.pageLength).draw();
        }
    },

    // Utility function to get selected rows
    getSelectedRows: function(table) {
        const selectedRows = [];
        table.rows({ selected: true }).every(function() {
            selectedRows.push(this.data());
        });
        return selectedRows;
    },

    // Utility function to show/hide columns
    toggleColumn: function(table, columnIndex, show = true) {
        if (table) {
            const column = table.column(columnIndex);
            if (show) {
                column.visible(true);
            } else {
                column.visible(false);
            }
        }
    },

    // Utility function to add custom row click handler
    addRowClickHandler: function(selector, handler) {
        $(document).on('click', selector + ' tbody tr', function(e) {
            // Ignore clicks on action buttons
            if ($(e.target).closest('.table-actions').length === 0) {
                handler(this, e);
            }
        });
    },

    // Initialize table with action buttons
    initWithActions: function(selector, type = 'basic', actionButtons = []) {
        const config = this.configs[type] || {};

        // Add custom action buttons DOM
        if (actionButtons.length > 0) {
            config.dom = '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 text-end"B>>' +
                        '<"row"<"col-sm-12"f>>' +
                        '<"row"<"col-sm-12"tr>>' +
                        '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>';

            config.buttons = actionButtons;
        }

        return this.init(selector, config);
    }
};

// Auto-initialize DataTables on page load
$(document).ready(function() {
    // Look for tables with data-table attribute
    $('table[data-table]').each(function() {
        const $table = $(this);
        const tableType = $table.data('table') || 'basic';
        const selector = '#' + $table.attr('id');

        if (selector) {
            DataTableHelper.initByType(selector, tableType);
        }
    });

    // Look for tables with .datatable class
    $('.datatable').each(function() {
        const $table = $(this);
        const tableType = $table.data('type') || 'basic';
        const selector = '#' + $table.attr('id');

        if (selector) {
            DataTableHelper.initByType(selector, tableType);
        }
    });
});

// Make DataTableHelper available globally
window.DataTableHelper = DataTableHelper;