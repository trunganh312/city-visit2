<?
include('config_module.php');
$Admin->checkPermission('module_edit');

/** --- Khai báo một số biến cơ bản --- **/
$page_title =   'Sửa thông tin Module';
$table      =   'module';
$field_id   =   'mod_id';
$record_id  =   getValue('id');
$record_info    =   $DB->query("SELECT * FROM " . $table . " WHERE " . $field_id . " = " . $record_id)->getOne();
if (empty($record_info)) {
    exit('Dữ liệu này không tồn tại!');
}

/** --- End of Khai báo một số biến cơ bản --- **/

/** --- Class query để insert dữ liệu --- **/
$Query  =   new GenerateQuery($table);
$Query->add('mod_name', DATA_STRING, '', 'Bạn chưa nhập tên module', 'Tên module đã tồn tại')
    ->add('mod_folder', DATA_STRING, '', 'Bạn chưa nhập Folder của module', 'Folder đã được dùng ở module khác')
    ->add('mod_icon', DATA_STRING, '')
    ->add('mod_order', DATA_INTEGER, 0)
    ->add('mod_note', DATA_STRING, '');
/** --- End of Class query để insert dữ liệu --- **/

/** --- Submit form --- **/
if ($Query->submitForm()) {

    //Validate form
    if ($Query->validate($field_id, $record_id)) {

        $DB->execute($Query->generateQueryUpdate($field_id, $record_id));
        reload_parent_window();
    }
}
/** --- End of Submit form --- **/
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= $Layout->loadHead($page_title) ?>
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
    <?= $Form->text('Tên module', 'mod_name', $mod_name, true) ?>
    <?= $Form->text('Folder', 'mod_folder', $mod_folder, true) ?>
    <?= $Form->text('Icon', 'mod_icon', $mod_icon) ?>
    <?= $Form->text('Thứ tự', 'mod_order', $mod_order) ?>
    <?= $Form->textarea('Mô tả', 'mod_note', $mod_note) ?>
    <?= $Form->button('Cập nhật') ?>
    <?= $Form->closeForm() ?>
    <?
    $Layout->footer();
    ?><?
        $Layout->loadMapInit();
        ?>
</body>

</html>