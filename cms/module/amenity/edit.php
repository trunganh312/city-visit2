<?
include('./config_module.php');
$Admin->checkPermission('admin_edit');

/** --- Khai báo một số biến cơ bản --- **/
$table          =   'amenity';
$page_title     =   'Sửa thông tin tiện ích';
$field_id       =   'ame_id';
$record_id      =   getValue('id');
$record_info    =   $DB->query("SELECT * FROM " . $table . " WHERE " . $field_id . " = " . $record_id)->getOne();
if (empty($record_info)) {
    exit('Dữ liệu này không tồn tại!');
}
/** --- End of Khai báo một số biến cơ bản --- **/

/** --- Class query để insert dữ liệu --- **/
$Query  =   new GenerateQuery($table);
$Query->add('ame_name', DATA_STRING, '', 'Bạn chưa nhập tên tiện ích')
    ->add('ame_group_id', DATA_INTEGER, '')
    ->add('ame_icon', DATA_STRING, '');
/** --- End of Class query để insert dữ liệu --- **/


/** --- Submit form --- **/
if ($Query->submitForm()) {
    // Validate form
    if ($Query->validate($field_id, $record_id)) {
        // Update dữ liệu vào cơ sở dữ liệu
        if ($DB->execute($Query->generateQueryUpdate($field_id, $record_id)) >= 0) {
            set_session_toastr();
            reload_parent_window();
        } else {
            $Query->addError('Có lỗi xảy ra khi cập nhật bản ghi');
        }
    }
}
/** --- End of Submit form --- **/

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= $Layout->loadHead($page_title); ?>
    <style>
        .list_group_process label {
            width: 30%;
            display: inline-block;
            margin-right: 10px;
        }

        #image-preview {
            max-width: 100%;
            height: auto;
            display: none;
        }
    </style>
</head>

<body class="window-thickbox">
    <?
    $Layout->setPopup(true)->header($page_title);
    ?>
    <?
    //Tạo ra các biến sẵn được lấy ra từ bản ghi này để fill vào form
    $Query->generateVariable($record_info);

    //Show form data
    $Form   =   new GenerateForm;
    ?>
    <?= $Form->createForm() ?>
    <?= $Form->showError($Query->error) ?>
    <?= $Form->text('Tên tiện ích', 'ame_name', $ame_name, true) ?>
    <?= $Form->text('Icon', 'ame_icon', $ame_icon, false, 'Lấy từ fontawesome: fas fa-phone') ?>
    <?= $Form->select('Chọn nhóm', 'ame_group_id', $list_group_data, $ame_group_id, true) ?>
    <?= $Form->button('Thêm mới') ?>
    <?= $Form->closeForm() ?>
    <?
    $Layout->footer();
    ?><?
        $Layout->loadMapInit();
        ?>
</body>

</html>