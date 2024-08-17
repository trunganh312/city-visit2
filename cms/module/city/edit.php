<?
include('../../Core/Config/require_cms.php');
$Admin->checkPermission('admin_edit');

/** --- Khai báo một số biến cơ bản --- **/
$table          =   'city';
$page_title     =   'Sửa thông tin thành phố';
$field_id       =   'cit_id';
$record_id      =   getValue('id');
$record_info    =   $DB->query("SELECT * FROM " . $table . " WHERE " . $field_id . " = " . $record_id)->getOne();
if (empty($record_info)) {
    exit('Dữ liệu này không tồn tại!');
}
/** --- End of Khai báo một số biến cơ bản --- **/

/** --- Class query để insert dữ liệu --- **/
$Query  =   new GenerateQuery($table);
$Query->add('cit_name', DATA_STRING, '', 'Bạn chưa nhập tên thành phố')
    ->add('cit_name_other', DATA_STRING, '')
    ->add('cit_address_map', DATA_STRING, '')
    ->add('cit_active', DATA_INTEGER, 0)
    ->add('cit_order', DATA_INTEGER, 0)
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
$Upload = new Upload('cit_image', '');
/** End of Class Image để upload ảnh */


/** --- Submit form --- **/
if ($Query->submitForm()) {
    // Validate form
    if ($Query->validate($field_id, $record_id)) {

        // Xử lý upload ảnh
        if (isset($_FILES['cit_image']) && $_FILES['cit_image']['error'] === UPLOAD_ERR_OK) {
            $fileName =  $Upload->uploadSingleImage($_FILES['cit_image'], 'city');
            $Query->add('cit_image', DATA_STRING, $fileName);
        }
        // Update dữ liệu vào cơ sở dữ liệu
        if ($DB->execute($Query->generateQueryUpdate($field_id, $record_id)) >= 0) {
            set_session_toastr();
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
    <?= $Form->text('Tên thành phố', 'cit_name', $cit_name, true) ?>
    <?= $Form->text('Tên khác', 'cit_name_other', $cit_name_other) ?>
    <?= $Form->textarea('Nội dung', 'cit_content', $cit_content, true) ?>
    <?= $Form->checkbox('Active', 'cit_active', $cit_active) ?>
    <?= $Form->text('Order', 'cit_order', $cit_order) ?>
    <?= $Form->checkbox('Hot', 'cit_hot', $cit_hot) ?>
    <?= $Form->checkbox('Priority', 'cit_priority', $cit_priority) ?>
    <?= $Form->file('Ảnh thành phố', 'cit_image') ?>
    <div id="previewImageCity" class="row text-center overflow-hidden" style="gap: 30px;">
        <? if ($cit_image) {
            echo '<div class="col-md-5 mb-3 position-relative" style="height: 200px; width: 200px;">
                <img src="' . DOMAIN_UPLOADS . '/city/' . $cit_image . '" class="img-fluid" style="object-fit: cover; height: 100%;">
                </div>';
        } ?>
    </div>
    <div style="display: none">
        <input id="pac-input" class="controls" type="text" placeholder="Enter a location" />
    </div>
    <div id="map" style="height: 400px;"></div>
    <?= $Form->text('Latitude', 'cit_lat_center', $cit_lat_center, true, '', 'readonly') ?>
    <?= $Form->text('Longitude', 'cit_lng_center', $cit_lng_center, true, '', 'readonly') ?>
    <?= $Form->button('Cập nhật') ?>
    <?= $Form->closeForm() ?>
    <?
    $Layout->footer();
    ?>
    <?
    $lat = $record_info['cit_lat_center'];
    $lng = $record_info['cit_lng_center'];
    $Layout->loadMapInit($lat, $lng);
    ?>

    <script>
        // Show image preview
        function showImagePreview(input, previewElementId) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imagePreview = document.getElementById(previewElementId);
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = "block";
                };
                reader.readAsDataURL(file);
            }
        }
        document.getElementById('cit_image').addEventListener('change', function() {
            showImagePreview(this, 'image-preview');
        });
    </script>
    <script>
        setupImagePreview('cit_image', 'previewImageCity');
    </script>

</body>

</html>