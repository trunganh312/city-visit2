<?
include('../../Core/Config/require_cms.php');
// Lấy ra danh sách thành phố
$list_city   =  $DB->query('SELECT cit_id, cit_name FROM city')->toArray();

/** Lấy danh sách thành phố để cho vào ô select */
$city_data = [];
foreach ($list_city as $item) {
    $city_data[$item['cit_id']] = $item['cit_name'];
}
