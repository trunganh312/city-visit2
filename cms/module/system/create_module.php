<?
include('config_module.php');
$Admin->checkPermission('module_create');

/** --- Khai báo một số biến cơ bản --- **/
$page_title =   'Thêm mới Module';
/** --- End of Khai báo một số biến cơ bản --- **/

/** --- Class query để insert dữ liệu --- **/
$Query  =   new GenerateQuery($table);
$Query->add('mod_name', DATA_STRING, '', 'Bạn chưa nhập tên module', 'Tên module đã tồn tại')
    ->add('mod_folder', DATA_STRING, '', 'Bạn chưa nhập Folder của module', 'Folder đã được dùng ở module khác')
    ->add('mod_icon', DATA_STRING, '')
    ->add('mod_order', DATA_INTEGER, 0)
    ->add('mod_active', DATA_INTEGER, 1)
    ->add('mod_note', DATA_STRING, '');
/** --- End of Class query để insert dữ liệu --- **/

/** --- Submit form --- **/
if ($Query->submitForm()) {

    //Validate form
    if ($Query->validate()) {

        if ($DB->execute($Query->generateQueryInsert()) > 0) {
            reload_parent_window();
        } else {
            $Query->addError('Có lỗi xảy ra khi tạo mới bản ghi');
        }
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
    //Tạo ra các biến sẵn để fill vào form dựa vào các hàm add trường dữ liệu ở trên (GenerateQuery)
    $Query->generateVariable();

    //Lấy ra giá trị lớn nhất hiện tại của mod_order để tự động cho order +1
    $row    =   $DB->query("SELECT MAX(mod_order) AS max_order FROM " . $table)->getOne();
    if (isset($row['max_order'])) {
        $mod_order  =   (int)$row['max_order'] + 1;
    }

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
    <?= $Form->button('Thêm mới') ?>
    <?= $Form->closeForm() ?>
    <?
    $Layout->footer();
    ?><?
        $Layout->loadMapInit();
        ?>
</body>

</html>