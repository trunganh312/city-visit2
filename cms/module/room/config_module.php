<?
include('../../Core/Config/require_cms.php');
// Lấy danh sách khách sạn
$list_hotel   =  $DB->query('SELECT hot_id, hot_name FROM hotel')->toArray();


/** Lấy danh sách khách sạn để cho vào ô select */
$hotel_data = [];
foreach ($list_hotel as $hotel) {
    $hotel_data[$hotel['hot_id']] = $hotel['hot_name'];
}

// Tạo biến const cho view 
$view_data = array(
    'View thành phố' => 'View thành phố',
    'View biển' => 'View biển',
    'View vườn' => 'View vườn',
    'View núi' => 'View núi',
    'View biển, thành phố' => 'View biển, thành phố',
);

// Tạo biên const cho số người trong mỗi hạng phòng
$person_data = array(
    '2 người lớn' => '2 người lớn',
    '4 người lớn' => '4 người lớn',
    '6 người lớn' => '6 người lớn',
);

// Tạo biên const cho kiểu giương
$bed_data = array(
    '1 giường lớn (King)' => '1 giường lớn (King)',
    '1 giường lớn (Queen)' => '1 giường lớn (Queen)',
    '1 giường đôi Hoặc 2 giường đơn' => '1 giường đôi Hoặc 2 giường đơn',
    '1 giường đôi' => '1 giường đôi',
    '3 giường đôi' => '3 giường đôi',
);

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
