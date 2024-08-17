<?
//  Khai báo web dành cho tỉnh 
define('CITY_NAME', 'Đà Nẵng');
define('CITY_ID', 48);

// // Value sort
// $value_sort = array(
//     'Giá' => array(
//         'price-asc' => 'Giá tăng dần',
//         'price-desc' => 'Giá giảm dần',
//     )
// );

// Reviews

$review_data = array(
    'cleanliness' => 'Sạch sẽ',
    'amenities' => 'Tiện ích',
    'money' => 'Giá cả',
    'service' => 'Dịch vụ',
    'support' => 'Chăm sóc khách hàng',
    'location' => 'Vị trí',
);

// Type data
$type_data = [
    'Khách sạn'             =>  'Khách sạn',
    'Resort'                =>  'Resort',
    'Homestay'              =>  'Homestay',
    'Villa'                 =>  'Villa',
    'Khu nghỉ dưỡng'        =>  'Khu nghỉ dưỡng',
    'Du thuyền'             =>  'Du thuyền',
    'Ecofarm'               =>  'Ecofarm',
    'Căn hộ'                =>  'Căn hộ',
    'Tổ hợp du lịch'        =>  'Tổ hợp du lịch',
];

/** --- Loại ảnh hotel --- **/
define('TYPE_ROOM', 1);
define('TYPE_HOTEL', 2);
define('TYPE_AMENITY', 3);
define('TYPE_EAT', 4);
define('TYPE_SWIM', 5);
define('TYPE_OTHER', 6);
define('TYPE_NEAR', 7);

// Domain
define('DOMAIN_WEB_VIEW', 'http://cityvisit.local/views/');
