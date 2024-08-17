<?
include('../../Core/Config/require_cms.php');
$Admin->checkPermission('admin_create');

/** --- Khai báo một số biến cơ bản --- **/
$page_title =   'Thêm mới thành phố';
/** --- End of Khai báo một số biến cơ bản --- **/

/** --- Class query để insert dữ liệu --- **/
$Query  =   new GenerateQuery('city');
$Query->add('cit_name', DATA_STRING, '', 'Bạn chưa nhập tên thành phố')
    ->add('cit_name_other', DATA_STRING, '')
    ->add('cit_address_map', DATA_STRING, '')
    ->add('cit_active', DATA_INTEGER, 1)
    ->add('cit_order', DATA_INTEGER, 1)
    ->add('cit_lat_center', DATA_DOUBLE, 1)
    ->add('cit_lng_center', DATA_DOUBLE, 1)
    ->add('cit_country', DATA_INTEGER, 0)
    ->add('cit_area', DATA_INTEGER, 0)
    ->add('cit_hot', DATA_INTEGER, 0)
    ->add('cit_priority', DATA_INTEGER, 0)
    ->add('cit_content', DATA_STRING, '', 'Bạn chưa nhập nội dung');
/** --- End of Class query để insert dữ liệu --- **/

/** Class Image để upload ảnh */
// $Image  = new Image();
$Upload = new Upload('', '');
/** End of Class Image để upload ảnh */


/** --- Submit form --- **/
if ($Query->submitForm()) {
    // Validate form
    if ($Query->validate()) {

        // Xử lý upload ảnh
        if (isset($_FILES['cit_image']) && $_FILES['cit_image']['error'] === UPLOAD_ERR_OK) {
            $fileName =  $Upload->uploadSingleImage($_FILES['cit_image'], 'city');
            $Query->add('cit_image', DATA_STRING, $fileName);
        }
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
    <style>
        .list_group_process label {
            width: 30%;
            display: inline-block;
            margin-right: 10px;
        }
    </style>
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
    <?= $Form->text('Tên thành phố', 'cit_name', $cit_name, true) ?>
    <?= $Form->text('Tên khác', 'cit_name_other', $cit_name_other) ?>
    <?= $Form->textarea('Nội dung', 'cit_content', $cit_content, true) ?>
    <?= $Form->checkbox('Active', 'cit_active', $cit_active) ?>
    <?= $Form->text('Order', 'cit_order', $cit_order) ?>
    <?= $Form->checkbox('Hot', 'cit_hot', $cit_hot) ?>
    <?= $Form->checkbox('Priority', 'cit_priority', $cit_priority) ?>
    <?= $Form->file('Ảnh thành phố', 'cit_image') ?>
    <div id="previewImageCity" class="row text-center overflow-hidden" style="gap: 30px;">
    </div>
    <div style="display: none">
        <input id="pac-input" class="controls" type="text" placeholder="Enter a location" />
    </div>
    <div id="map" style="height: 400px;"></div>
    <?= $Form->text('Latitude', 'cit_lat_center', $cit_lat_center, true, '', 'readonly') ?>
    <?= $Form->text('Longitude', 'cit_lng_center', $cit_lng_center, true, '', 'readonly') ?>
    <?= $Form->button('Thêm mới') ?>
    <?= $Form->closeForm() ?>
    <?
    $Layout->footer();
    ?>
    <?
    $Layout->loadMapInit();
    ?>
    <script>
        setupImagePreview('cit_image', 'previewImageCity');
    </script>

</body>

</html>