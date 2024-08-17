<?
include('config_module.php');
$Admin->checkPermission('admin_create_group');

/** --- Khai báo một số biến cơ bản --- **/
$page_title =   'Thêm mới Nhóm tài khoản Admin';
/** --- End of Khai báo một số biến cơ bản --- **/

/** --- Class query để insert dữ liệu --- **/
$Query  =   new GenerateQuery('admin_group');
$Query->add('adgr_name', DATA_STRING, '', 'Bạn chưa nhập tên nhóm', 'Tên nhóm này đã tồn tại')
    ->add('adgr_note', DATA_STRING, '')
    ->add('adgr_parent', DATA_INTEGER, 0)
    ->add('adgr_check_level', DATA_INTEGER, 0);
/** --- End of Class query để insert dữ liệu --- **/

/** --- Submit form --- **/
if ($Query->submitForm()) {

    //Validate form
    if ($Query->validate()) {

        if ($DB->execute($Query->generateQueryInsert()) > 0) {
            //close_tb_window();
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
    <?= $Layout->loadHead($page_title); ?>
</head>

<body class="window-thickbox">
    <?
    $Layout->setPopup(true)->header($page_title);
    ?>
    <?
    //Tạo ra các biến sẵn để fill vào form dựa vào các hàm add trường dữ liệu ở trên (GenerateQuery)
    $Query->generateVariable();

    //Show form data
    $Form   =   new GenerateForm;
    ?>
    <?= $Form->createForm() ?>
    <?= $Form->showError($Query->error) ?>
    <?= $Form->text('Tên nhóm', 'adgr_name', $adgr_name, true) ?>
    <?= $Form->textarea('Mô tả', 'adgr_note', $adgr_note) ?>
    <?= $Form->select('Nhóm cấp trên', 'adgr_parent', $list_group_admin, $adgr_parent) ?>
    <?= $Form->checkbox('Check quyền theo Level', 'adgr_check_level', $adgr_check_level) ?>
    <?= $Form->button('Thêm mới') ?>
    <?= $Form->closeForm() ?>
    <?
    $Layout->footer();
    ?><?
        $Layout->loadMapInit();
        ?>
</body>

</html>