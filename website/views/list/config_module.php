<?
include '../../config/require_web.php';


// Check url xem có đúng k
$city_name_url = getValue('city', GET_STRING);
$city_id_url = getValue('city_id', GET_INT,);

$city_name_ss = getValue('city_name', GET_STRING, GET_SESSION);
$city_id_ss = getValue('city_id', GET_STRING, GET_SESSION);

if ($city_id_ss != $city_id_url || $city_name_ss != $city_name_url) return redirect_url('/404/404.php');


// Lấy ra danh sách quận huyện tại 1 tỉnh. VD: Tại Đà Năng là 48
$districts   =  $DB->query("SELECT dis_name FROM district WHERE dis_city_id = " . CITY_ID)->toArray();

// Lấy ra dánh sách tiện ích
$amenities = $DB->query("SELECT * FROM amenity")->toArray();
