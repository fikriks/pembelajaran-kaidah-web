<?php
/**
 * Stats Row Component - Wrapper for Multiple Stats Cards
 * Green Theme Arabic Learning Application
 *
 * Automatically handles column sizing based on number of stats cards
 * - 1 card: Full width (col-12)
 * - 2 cards: Half width (col-md-6)
 * - 3 cards: One-third width (col-md-4)
 * - 4+ cards: Quarter width (col-md-3)
 *
 * @param array $stats       Array of stats card configurations
 * @param array $attributes  Additional HTML attributes for the row div
 */

// Ensure $stats is a valid array
if (empty($stats) || !is_array($stats)) {
    return; // Don't render anything if no stats provided
}

// Determine column class based on number of stats cards
$statsCount = count($stats);
$columnClass = 'col-md-3'; // Default for 4+ cards

switch ($statsCount) {
    case 1:
        $columnClass = 'col-12';
        break;
    case 2:
        $columnClass = 'col-md-6';
        break;
    case 3:
        $columnClass = 'col-md-4';
        break;
    default:
        $columnClass = 'col-md-3';
        break;
}

// Build attributes string for the row
$attrString = '';
if (!empty($attributes) && is_array($attributes)) {
    foreach ($attributes as $attr => $value) {
        $attrString .= ' ' . $attr . '="' . esc($value) . '"';
    }
}

// Default values for stats cards
$defaultStats = [
    'title' => 'Statistic',
    'value' => '0',
    'subtitle' => '',
    'icon' => 'chart-bar',
    'variant' => 'primary',
    'attributes' => []
];
?>

<div class="row mb-4<?= $attrString ?>">
    <?php foreach ($stats as $stat): ?>
        <?php
        // Merge with defaults
        $statConfig = array_merge($defaultStats, $stat);
        $statConfig['columnClass'] = $columnClass;

        // Render stats card
        echo view('partials/stats_card', $statConfig);
        ?>
    <?php endforeach; ?>
</div>