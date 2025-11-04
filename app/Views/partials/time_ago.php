<?php
function time_ago($datetime) {
    $time = strtotime($datetime);
    $now = time();
    $diff = $now - $time;

    if ($diff < 60) {
        return 'Baru saja';
    } elseif ($diff < 3600) {
        $minutes = round($diff / 60);
        return $minutes . ' menit lalu';
    } elseif ($diff < 86400) {
        $hours = round($diff / 3600);
        return $hours . ' jam lalu';
    } elseif ($diff < 604800) {
        $days = round($diff / 86400);
        return $days . ' hari lalu';
    } elseif ($diff < 2592000) {
        $weeks = round($diff / 604800);
        return $weeks . ' minggu lalu';
    } elseif ($diff < 31536000) {
        $months = round($diff / 2592000);
        return $months . ' bulan lalu';
    } else {
        $years = round($diff / 31536000);
        return $years . ' tahun lalu';
    }
}

echo time_ago($time);
?>