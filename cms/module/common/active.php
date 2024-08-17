<?php
include('../../core/config/require_cms.php');
$Admin->checkPermission('admin_edit');

// Get parameters from request
$table          =  'city';
$field_id       =  '';
$record_id      =  getValue('id'); // cit_id
$field          =  getValue('field', GET_STRING); // cit_active

$arr_fields = explode('_', $field);
switch ($arr_fields[0]) {
    case 'cit':
        $table = 'city';
        $field_id = 'cit_id';
        break;
    case 'mod':
        $table = 'module';
        $field_id = 'mod_id';
        break;
    case 'adm':
        $table = 'admin';
        $field_id = 'adm_id';
        break;
    case 'modf':
        $table = 'module_file';
        $field_id = 'modf_id';
        break;
    case 'hot':
        $table = 'hotel';
        $field_id = 'hot_id';
        break;
    case 'dis':
        $table = 'district';
        $field_id = 'dis_id';
        break;
    case 'roo':
        $table = 'room';
        $field_id = 'roo_id';
        break;
    default:
        exit('Invalid field');
}
if (!$table || !$field_id || !$record_id) {
    exit('Missing parameters');
}

// Khởi tạo đối tượng GenerateQuery
$Query = new GenerateQuery($table);

// Tìm ra bản ghi hiện tại
$record = $DB->query("SELECT * FROM $table WHERE $field_id = $record_id")->getOne();

if (!$record) {
    exit('Record not found');
}
// Trạng thái feild hiện tại
$current_active = $record[$field];
$new_active = $current_active == '0' ? 1 : 0;

$Query->add($field, DATA_INTEGER, $new_active);

// Update feild hiện tại
$update = $Query->generateQueryUpdate($field_id, $record_id);

if ($DB->execute($update) !== false) {
    set_session_toastr();
    reload_parent_window();
} else {
    set_session_toastr('result', 'faild');
    reload_parent_window();
}
