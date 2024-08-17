<?php
// api.php
include '../config/require_web.php';
header('Content-Type: application/json');

// Lấy tham số từ URL
$star = $_GET['star'] ?? '';
$type = $_GET['type'] ?? '';

// Giả lập dữ liệu khách sạn
$sql = "SELECT h.*,
        c.cit_name,
        d.dis_name, d.dis_address_map
        FROM hotel h
        LEFT JOIN city c ON h.hot_city_id = c.cit_id
        LEFT JOIN district d ON h.hot_district_id = d.dis_id
        WHERE h.hot_city_id = " . CITY_ID;

$hotels = $DB->query($sql)->toArray();

// Lọc khách sạn dựa trên điều kiện
$filteredHotels = array_filter($hotels, function ($hotel) use ($star, $type) {
    $starMatch = $star ? $hotel['hot_star'] == $star : true;
    $typeMatch = $type ? $hotel['hot_type'] == $type : true;
    return $starMatch && $typeMatch;
});

// Trả về dữ liệu dưới dạng JSON
echo json_encode(['hotels' => array_values($filteredHotels)]);
