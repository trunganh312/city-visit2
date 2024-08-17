<?
include('../../Core/Config/require_cms.php');
// Lấy ra danh sách thành phố
$list_city   =  $DB->query('SELECT cit_id, cit_name FROM city')->toArray();

// Lấy ra danh sách huyện trong tỉnh
$list_district   =  $DB->query('SELECT dis_id, dis_name, dis_city_id FROM district')->toArray();



// Mảng rate để fill vào ô select
// Rate chỉ từ 1 - 5
$rate_data = [];
foreach (range(1, 5) as $i) {
    $rate_data[$i] = $i;
}

/** Lấy danh sách thành phố để cho vào ô select */
$city_data = [];
foreach ($list_city as $item) {
    $city_data[$item['cit_id']] = $item['cit_name'];
}

/** --- Lấy danh sách huyện trong thành phố --- **/
$district_data = [];
foreach ($list_district as $item) {
    $district_data[$item['dis_id']] = $item['dis_name'];
}

// Chuyển đổi dữ liệu huyện thành một mảng có cấu trúc theo tỉnh
$districts_by_city = [];
foreach ($list_district as $item) {
    $districts_by_city[$item['dis_city_id']][] = ['id' => $item['dis_id'], 'name' => $item['dis_name']];
}

// Chuyển đổi mảng thành JSON và nhúng vào trang HTML
$districts_json = json_encode($districts_by_city);




/** Lấy danh sách tất cả tiện nghi theo từng nhóm */
// Lấy ra danh sách tất cả các tiện nghi
$amenities  =  $DB->query('SELECT a.*, gr.amg_name FROM amenity a LEFT JOIN amenity_group gr ON a.ame_group_id = gr.amg_id')->toArray();

// Mảng kết quả
$list_amenities = [];

// Duyệt qua từng phần tử của mảng đầu vào
foreach ($amenities as $item) {
    // Sử dụng tên nhóm làm key cho mảng kết quả
    $groupName = $item['amg_name'];

    // Nếu nhóm chưa tồn tại, tạo mới nhóm đó
    if (!isset($list_amenities[$groupName])) {
        $list_amenities[$groupName] = [];
    }

    // Thêm tiện ích vào nhóm tương ứng
    $resultArray[$groupName][] = [
        'ame_id' => $item['ame_id'],
        'ame_name' => $item['ame_name'],
        'ame_icon' => $item['ame_icon'],
        'ame_group_id' => $item['ame_group_id']
    ];
}
/** End of Lấy danh sách tất cả tiện nghi theo từng nhóm */
