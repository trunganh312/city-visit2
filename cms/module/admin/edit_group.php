<?
include('config_module.php');
$Admin->checkPermission('admin_edit_group');

/** --- Khai báo một số biến cơ bản --- **/
$table      =   'admin_group';
$field_id   =   'adgr_id';
$page_title =   'Sửa thông tin Nhóm tài khoản Admin';
$record_id  =   getValue('id');
$record_info    =   $DB->query("SELECT * FROM " . $table . " WHERE " . $field_id . " = " . $record_id)->getOne();
if (empty($record_info)) {
    exit('Dữ liệu này không tồn tại!');
}
/** --- End of Khai báo một số biến cơ bản --- **/

/** --- Class query để insert dữ liệu --- **/
$Query  =   new GenerateQuery($table);
$Query->add('adgr_name', DATA_STRING, '', 'Bạn chưa nhập tên nhóm', 'Tên nhóm này đã tồn tại')
    ->add('adgr_note', DATA_STRING, '')
    ->add('adgr_parent', DATA_INTEGER, 0);
/** --- End of Class query để insert dữ liệu --- **/

/** --- Submit form --- **/
if ($Query->submitForm()) {

    //Validate form
    if ($Query->validate($field_id, $record_id)) {

        if ($DB->execute($Query->generateQueryUpdate($field_id, $record_id)) >= 0) {
            reload_parent_window();
        } else {
            $Query->addError('Đã có lỗi xảy ra, vui lòng thử lại!');
        }
    }
}
/** --- End of Submit form --- **/

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= $Layout->loadHead($page_title); ?>
</head>

<body class="window-thickbox">
    <?
    $Layout->setPopup(true)->header($page_title);
    ?>
    <?
    //Tạo ra các biến sẵn được lấy ra từ bản ghi này để fill vào form
    $Query->generateVariable($record_info);

    //Ko cho chọn chính mình làm parent
    if (isset($list_group_admin[$record_id]))   unset($list_group_admin[$record_id]);

    //Show form data
    $Form   =   new GenerateForm;
    ?>
    <?= $Form->createForm() ?>
    <?= $Form->showError($Query->error) ?>
    <?= $Form->text('Tên nhóm', 'adgr_name', $adgr_name, true) ?>
    <?= $Form->textarea('Mô tả', 'adgr_note', $adgr_note) ?>
    <?= $Form->select('Nhóm cấp trên', 'adgr_parent', $list_group_admin, $adgr_parent) ?>
    <?= $Form->button('Cập nhật') ?>
    <?= $Form->closeForm() ?>
    <?
    $Layout->footer();
    ?><?
        $Layout->loadMapInit();
        ?>
</body>

</html>