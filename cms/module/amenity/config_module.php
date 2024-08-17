<?
include('../../Core/Config/require_cms.php');
// Lấy danh sách nhóm tiện ích
$list_group = $DB->query('SELECT amg_name, amg_id FROM amenity_group')->toArray();

/** Lấy danh sách thành phố để cho vào ô select */
$list_group_data = [];
foreach ($list_group as $item) {
    $list_group_data[$item['amg_id']] = $item['amg_name'];
}
