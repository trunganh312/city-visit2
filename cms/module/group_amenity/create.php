<?
include('../../Core/Config/require_cms.php');
$Admin->checkPermission('admin_create');

/** --- Khai báo một số biến cơ bản --- **/
$page_title =   'Thêm mới tiện ích';
/** --- End of Khai báo một số biến cơ bản --- **/

/** --- Class query để insert dữ liệu --- **/
$Query          =   new GenerateQuery('amenity_group');
$Query->add('amg_name', DATA_STRING, '', 'Bạn chưa nhập tên tiện ích')
    ->add('amg_icon', DATA_STRING, '');
/** --- End of Class query để insert dữ liệu --- **/

/** --- Submit form --- **/
if ($Query->submitForm()) {
    // Validate form
    if ($Query->validate()) {
        // Insert dữ liệu vào cơ sở dữ liệu
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
    <?= $Layout->loadHead($page_title); ?>
</head>

<body class="window-thinkbox">
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
    <?= $Form->text('Tên nhóm tiện ích', 'amg_name', $amg_name, true) ?>
    <?= $Form->text('Icon', 'amg_icon', $amg_icon, false, 'Lấy từ fontawesome: fas fa-phone') ?>
    <?= $Form->button('Thêm mới') ?>
    <?= $Form->closeForm() ?>
    <?
    $Layout->footer();
    ?><?
        $Layout->loadMapInit();
        ?>
</body>

</html>