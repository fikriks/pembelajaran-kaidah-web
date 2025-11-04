<?php

if (!function_exists('time_ago')) {
    /**
     * Calculate time ago from a given datetime
     *
     * @param string $datetime The datetime string
     * @return string Time ago description in Indonesian
     */
    function time_ago($datetime) {
        if (!$datetime) return 'Tidak ada data';

        $time = strtotime($datetime);
        $now = time();
        $diff = $now - $time;

        if ($diff < 60) {
            return 'Baru saja';
        } elseif ($diff < 3600) {
            $minutes = floor($diff / 60);
            return $minutes . ' menit yang lalu';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' jam yang lalu';
        } elseif ($diff < 604800) {
            $days = floor($diff / 86400);
            return $days . ' hari yang lalu';
        } elseif ($diff < 2592000) {
            $weeks = floor($diff / 604800);
            return $weeks . ' minggu yang lalu';
        } elseif ($diff < 31536000) {
            $months = floor($diff / 2592000);
            return $months . ' bulan yang lalu';
        } else {
            $years = floor($diff / 31536000);
            return $years . ' tahun yang lalu';
        }
    }
}

if (!function_exists('calculate_days_since')) {
    /**
     * Calculate days since a given datetime
     *
     * @param string $datetime The datetime string
     * @return int Number of days
     */
    function calculate_days_since($datetime) {
        if (!$datetime) return 0;
        $created = strtotime($datetime);
        $today = time();
        $days = floor(($today - $created) / (60 * 60 * 24));
        return max(1, $days);
    }
}

if (!function_exists('format_date_time')) {
    /**
     * Format date and time in Indonesian format
     *
     * @param string $datetime The datetime string
     * @param string $format The format (default: 'd M Y H:i:s')
     * @return string Formatted date
     */
    function format_date_time($datetime, $format = 'd M Y H:i:s') {
        if (!$datetime) return '-';
        return date($format, strtotime($datetime));
    }
}