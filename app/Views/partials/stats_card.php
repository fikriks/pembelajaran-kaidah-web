<?php
/**
 * Stats Card Component - Individual Statistics Card
 * Green Theme Arabic Learning Application
 *
 * @param string $title        Card title/label
 * @param string $value        Main value/statistic
 * @param string $subtitle     Small subtitle text (optional)
 * @param string $icon         Tabler icon name (without 'ti ti-')
 * @param string $variant      Card variant: primary, success, warning, info, danger
 * @param string $columnClass  Bootstrap column class (col-md-3, col-md-4, etc)
 * @param array  $attributes   Additional HTML attributes for the card div
 */

// Default values for optional parameters
$subtitle = $subtitle ?? '';
$variant = $variant ?? 'primary';
$columnClass = $columnClass ?? 'col-md-3';
$attributes = $attributes ?? [];

// Build attributes string
$attrString = '';
foreach ($attributes as $attr => $value) {
    $attrString .= ' ' . $attr . '="' . esc($value) . '"';
}

// Map variants to gradient colors
$variantMap = [
    'primary' => 'stats-card-primary',
    'success' => 'stats-card-success',
    'warning' => 'stats-card-warning',
    'info' => 'stats-card-info',
    'danger' => 'stats-card-danger'
];

$cardClass = $variantMap[$variant] ?? 'stats-card-primary';
?>

<div class="<?= $columnClass ?> mb-3">
    <div class="card stats-card <?= $cardClass ?> border-0 d-print-none <?= $attrString ?>">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="stats-icon">
                    <i class="ti ti-<?= esc($icon) ?> text-white"></i>
                </div>
                <div class="ms-3">
                    <h6 class="mb-0"><?= esc($title) ?></h6>
                    <h2 class="mb-0 fw-bold"><?= esc($value) ?></h2>
                    <?php if (!empty($subtitle)): ?>
                        <small><?= esc($subtitle) ?></small>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>