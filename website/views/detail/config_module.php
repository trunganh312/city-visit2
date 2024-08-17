<?
include '../../config/require_web.php';

$slug = $_GET['slug'];
/** LẤY RA HOTEL DETAIL THEO SLUG */
$hotel = $HotelController->getDetail($slug);

if (!$hotel) {
    redirect_url('/404/404.php');
    exit();
}

// Lấy ra danh sách phòng theo hotel id

$rooms = $HotelController->getRooms($hotel['hot_id']);


// Lấy ra tất cả ảnh thheo hotel id gom vào chung 1 type
$images = $DB->query("SELECT * FROM hotel_image WHERE hti_hotel_id = " . $hotel['hot_id'] . " ORDER BY hti_type_image")->toArray();


// Nhóm các hình ảnh theo loại
$groupedImages = [];
$total_image = 0;
foreach ($images as $image) {
    $type = $image['hti_type_image'];
    if (!isset($groupedImages[$type])) {
        $groupedImages[$type] = [
            'images' => [],
            'total' => 0,
        ];
    }
    $groupedImages[$type]['images'][] = $image;
    $groupedImages[$type]['total']++;
    $total_image++;
}

$groupedImages['total_image'] = $total_image;


if (!$hotel) {
    redirect_url('' . DOMAIN_WEB_VIEW . 'list/index.php');
    exit();
}

/** MẢNG ẢNH CỦA HOTEL */
$images = json_decode($hotel['images'], true);

// Lấy ra danh sách khách sạn lân cận
$nearbyHotels = $HotelController->getNearbyHotels($hotel['hot_district_id'], $hotel['hot_id']);

// Lấy ra danh sách tất cả ks tại đà nẵng
$hotels = $HotelController->getHotelsByCityId(CITY_ID);

$hotelsJson = json_encode($hotels);

$hotelJson = json_encode($hotel);
