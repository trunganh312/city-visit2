<?
global  $DB;
// Lấy ra huyện được active, tick hot có ks nổi tiếng, active
$hotelsByGroupDistrict = $DB->query("SELECT h.*, d.dis_name FROM hotel h 
    LEFT JOIN district d on h.hot_district_id = d.dis_id
 WHERE hot_city_id = 48 AND h.hot_active = 1 AND h.hot_hot = 1 AND d.dis_active = 1 AND d.dis_hot = 1")->toArray();
$groupedHotels = array();

foreach ($hotelsByGroupDistrict as $hotel) {
    $districtId = $hotel['dis_name'];
    if (!isset($groupedHotels[$districtId])) {
        $groupedHotels[$districtId] = array();
    }
    $groupedHotels[$districtId][] = $hotel;
}

// Lấy ra config
$config = $DB->query("SELECT * FROM configuration")->toArray()[0];
