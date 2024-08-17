<?
// Lấy ra tên khách sạn theo id
$city = $DB->query("SELECT cit_name,cit_id FROM city WHERE cit_id = " . CITY_ID)->getOne();

// Lưu tên thành phố vào để tạo slug dạng: DOMAIN/{CITY_ID}-slug(cit_name).html
$_SESSION['city_name'] = to_slug($city['cit_name']);
$_SESSION['city_id'] = $city['cit_id'];

// Lấy ra 10 hotel phổ biến nhất và được phép hiển thị thuộc 1 tỉnh
// VD Đà Nẵng -> cit_id = 48
$hotel_popular = $HotelController->getPopularHotels(CITY_ID);

// Lấy ra 6 huyện phổ biến nhất của tỉnh đó và lấy ra số hotel thuộc huyện đó
// ví dụ : Đà Nẵng
$district_popular = $DB->query('SELECT d.*, c.cit_name, c.cit_id, COUNT(h.hot_id) AS hotel_count
FROM district d
LEFT JOIN hotel h ON d.dis_id = h.hot_district_id
LEFT JOIN city c ON d.dis_city_id = c.cit_id
WHERE d.dis_city_id = ' . CITY_ID . '
GROUP BY d.dis_id, d.dis_name
LIMIT 6')->toArray();

$star_ratings = $DB->query('SELECT DISTINCT hot_star FROM hotel WHERE hot_active = 1 AND hot_promotion = 1 ORDER BY hot_star DESC LIMIT 2')->toArray();

$amenities = $DB->query('SELECT ame.* FROM amenity ame LEFT JOIN amenity_group gr ON gr.amg_id = ame.ame_group_id WHERE gr.amg_name = "Vị trí"')->toArray();
