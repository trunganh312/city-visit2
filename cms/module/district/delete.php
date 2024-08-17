<?
include('../../Core/Config/require_cms.php');
$record_ids = isset($_POST['record_id']) ? $_POST['record_id'] : array();
$id         = isset($_POST['id']) ? $_POST['id'] : '';
$table      = 'district';
$field_id   = 'dis_id';
$delete_query = '';

if ($record_ids) {
    $record_ids = explode(',', $record_ids);
    $record_ids_array = array_map('intval', $record_ids); // Chuyển đổi tất cả thành số nguyên
    // Tạo danh sách các ID cho câu lệnh SQL
    $record_ids_list = implode(',', $record_ids_array);

    // Tạo câu lệnh DELETE
    $delete_query = "DELETE FROM $table WHERE $field_id IN ($record_ids_list)";
} else if ($id) {
    // Tạo câu lệnh DELETE
    $delete_query = "DELETE FROM $table WHERE $field_id = $id";
}

if ($DB->execute($delete_query) !== false) {
    reload_parent_window();
} else {
    reload_parent_window();
}
