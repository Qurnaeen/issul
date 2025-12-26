<?php
/**
 * Helper Functions untuk Format Tanggal
 * File: helpers/tanggal_helper.php
 */

/**
 * Format tanggal ke format Indonesia
 * @param string $date - format Y-m-d atau timestamp
 * @param bool $with_day - tampilkan nama hari
 * @return string
 */
function format_tanggal($date, $with_day = false) {
    if (empty($date) || $date == '0000-00-00') {
        return '-';
    }
    
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    
    $hari = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    ];
    
    $timestamp = is_numeric($date) ? $date : strtotime($date);
    
    $day_name = $hari[date('l', $timestamp)];
    $day = date('d', $timestamp);
    $month = $bulan[date('n', $timestamp)];
    $year = date('Y', $timestamp);
    
    if ($with_day) {
        return $day_name . ', ' . $day . ' ' . $month . ' ' . $year;
    }
    
    return $day . ' ' . $month . ' ' . $year;
}

/**
 * Format tanggal dan waktu
 * @param string $datetime
 * @return string
 */
function format_datetime($datetime) {
    if (empty($datetime) || $datetime == '0000-00-00 00:00:00') {
        return '-';
    }
    
    $timestamp = strtotime($datetime);
    return format_tanggal($timestamp, true) . ' ' . date('H:i', $timestamp) . ' WIB';
}

/**
 * Hitung umur dari tanggal lahir
 * @param string $tanggal_lahir - format Y-m-d
 * @return int
 */
function hitung_umur($tanggal_lahir) {
    if (empty($tanggal_lahir) || $tanggal_lahir == '0000-00-00') {
        return 0;
    }
    
    $birthDate = new DateTime($tanggal_lahir);
    $today = new DateTime('today');
    $umur = $birthDate->diff($today)->y;
    
    return $umur;
}

/**
 * Format waktu relatif (berapa lama yang lalu)
 * @param string $datetime
 * @return string
 */
function time_ago($datetime) {
    if (empty($datetime)) {
        return '-';
    }
    
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;
    
    if ($diff < 60) {
        return 'Baru saja';
    } elseif ($diff < 3600) {
        $mins = floor($diff / 60);
        return $mins . ' menit yang lalu';
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return $hours . ' jam yang lalu';
    } elseif ($diff < 604800) {
        $days = floor($diff / 86400);
        return $days . ' hari yang lalu';
    } else {
        return format_tanggal($timestamp);
    }
}

/**
 * Convert tanggal dari format Indonesia ke Y-m-d
 * @param string $tanggal - format dd/mm/yyyy
 * @return string
 */
function tanggal_to_sql($tanggal) {
    if (empty($tanggal)) {
        return '';
    }
    
    $parts = explode('/', $tanggal);
    if (count($parts) == 3) {
        return $parts[2] . '-' . $parts[1] . '-' . $parts[0];
    }
    
    return $tanggal;
}

/**
 * Get nama bulan Indonesia
 * @param int $bulan - 1-12
 * @return string
 */
function nama_bulan($bulan) {
    $bulan_array = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    
    return isset($bulan_array[$bulan]) ? $bulan_array[$bulan] : '';
}
?>
