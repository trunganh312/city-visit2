<?
include_once('../../config/require_web.php');

// Lấy ra thông tin chi tiết theo room id
$roomId = isset($_GET['room_id']) ? $_GET['room_id'] : null;

if (!isset($roomId)) {
    redirect_url('' . DOMAIN_WEB_VIEW . 'list');
    exit();
}

$room = $DB->query("SELECT r.*, h.hot_address_map, h.hot_slug, h.hot_name, h.hot_id FROM room r LEFT JOIN hotel h ON h.hot_id = r.roo_hotel_id  WHERE r.roo_id = $roomId")->getOne();
