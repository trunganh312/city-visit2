<?
include('config_module.php');
$Admin->checkPermission('module_create_feature');

/** --- Khai báo một số biến cơ bản --- **/
$page_title =   'Thêm mới tính năng cho Module';
$module_id  =   getValue('module_id');
/** --- End of Khai báo một số biến cơ bản --- **/

/** --- Class query để insert dữ liệu --- **/
$Query  =   new GenerateQuery('module_file');
$Query->add('modf_module_id', DATA_INTEGER, $module_id)
    ->add('modf_name', DATA_STRING, '', 'Bạn chưa nhập tên tính năng')
    ->add('modf_parent_id', DATA_INTEGER, 0)
    ->add('modf_file', DATA_STRING, '')
    ->add('modf_order', DATA_INTEGER, 0)
    ->add('modf_active', DATA_INTEGER, 1)
    ->add('modf_note', DATA_STRING, '');
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
    <?= $Layout->loadHead($page_title) ?>
</head>

<body class="window-thickbox">
    <?
    $Layout->setPopup(true)->header($page_title);
    ?>
    <?
    //Tạo ra các biến sẵn để fill vào form dựa vào các hàm add trường dữ liệu ở trên (GenerateQuery)
    $Query->generateVariable();

    //Lấy ra các menu parent
    $parent =   $DB->query("SELECT modf_id, modf_name FROM module_file
                            WHERE modf_module_id = " . $module_id . " AND modf_parent_id = 0
                            ORDER BY modf_name")->toArray();
    $modf_parents   =   [];
    foreach ($parent as $row) {
        $modf_parents[$row['modf_id']]  =   $row['modf_name'];
    }

    //Lấy ra giá trị lớn nhất hiện tại của mod_order để tự động cho order +1
    $modf_order =   1;
    $row    =   $DB->query("SELECT MAX(modf_order) AS max_order FROM module_file WHERE modf_module_id = " . $module_id)->getOne();
    if (isset($row['max_order'])) {
        $modf_order  =   (int)$row['max_order'] + 1;
    }

    //Show form data
    $Form   =   new GenerateForm;
    ?>
    <?= $Form->createForm() ?>
    <?= $Form->showError($Query->error) ?>
    <?= $Form->text('Tên tính năng/nhóm', 'modf_name', $modf_name, true) ?>
    <?= $Form->select('Thuộc nhóm', 'modf_parent_id', $modf_parents, $modf_parent_id) ?>
    <?= $Form->text('File thực thi', 'modf_file', $modf_file, false, '<br>Nếu là tính năng thì bắt buộc phải nhập. Nếu là nhóm thì để trống.') ?>
    <?= $Form->text('Thứ tự', 'modf_order', $modf_order) ?>
    <?= $Form->textarea('Mô tả', 'modf_note', $modf_note) ?>
    <?= $Form->button('Thêm mới') ?>
    <?= $Form->closeForm() ?>
    <?
    $Layout->footer();
    ?><?
        $Layout->loadMapInit();
        ?>
</body>

</html>