<?
include('./config_module.php');
$Admin->checkPermission('admin_edit');

/** --- Khai báo một số biến cơ bản --- **/
$table          =   'district';
$page_title     =   'Sửa thông tin quận huyện';
$field_id       =   'dis_id';
$record_id      =   getValue('id');
$record_info    =   $DB->query("SELECT * FROM " . $table . " WHERE " . $field_id . " = " . $record_id)->getOne();
if (empty($record_info)) {
    exit('Dữ liệu này không tồn tại!');
}
/** --- End of Khai báo một số biến cơ bản --- **/

/** --- Class query để insert dữ liệu --- **/
$Query  =   new GenerateQuery($table);
$Query->add('dis_name', DATA_STRING, '', 'Bạn chưa nhập quận huyện')
    ->add('dis_name_other', DATA_STRING, '')
    ->add('dis_lat_center', DATA_DOUBLE, 1)
    ->add('dis_lng_center', DATA_DOUBLE, 1)
    ->add('dis_content', DATA_STRING, '', 'Bạn chưa nhập nội dung')
    ->add('dis_address_map', DATA_STRING, '')
    ->add('dis_city_id', DATA_INTEGER, '')
    ->add('dis_active', DATA_DOUBLE, 0)
    ->add('dis_hot', DATA_INTEGER, 0);
/** --- End of Class query để insert dữ liệu --- **/

/** Class Image để upload ảnh */
$Upload = new Upload('', '');
/** End of Class Image để upload ảnh */


/** --- Submit form --- **/
if ($Query->submitForm()) {
    // Validate form
    if ($Query->validate($field_id, $record_id)) {

        // Xử lý upload banner district
        if (isset($_FILES['dis_page_cover']) && $_FILES['dis_page_cover']['error'] === UPLOAD_ERR_OK) {
            $fileName =  $Upload->uploadSingleImage($_FILES['dis_page_cover'], 'district');
            $Query->add('dis_page_cover', DATA_STRING, $fileName);
        }

        // Xử lý upload ảnh district
        if (isset($_FILES['dis_image']) && $_FILES['dis_image']['error'] === UPLOAD_ERR_OK) {
            $imageName =  $Upload->uploadSingleImage($_FILES['dis_image'], 'district_cover');
            $Query->add('dis_image', DATA_STRING,  $imageName);
        }

        // Update dữ liệu vào cơ sở dữ liệu
        if ($DB->execute($Query->generateQueryUpdate($field_id, $record_id)) >= 0) {
            set_session_toastr();
            redirect_url('list.php');
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

<body class="sidebar-mini listing_page">
    <?
    $Layout->header($page_title);
    ?>
    <?
    //Tạo ra các biến sẵn được lấy ra từ bản ghi này để fill vào form
    $Query->generateVariable($record_info);

    //Show form data
    $Form   =   new GenerateForm;
    ?>
    <?= $Form->createForm() ?>
    <?= $Form->showError($Query->error) ?>
    <?= $Form->text('Tên quận huyện', 'dis_name', $dis_name, true) ?>
    <?= $Form->text('Tên khác', 'dis_name_other', $dis_name_other) ?>
    <?= $Form->text('Địa chỉ', 'dis_address_map', $dis_address_map) ?>
    <?= $Form->textarea('Nội dung', 'dis_content', $dis_content, true) ?>
    <?= $Form->checkbox('Active', 'dis_active', $dis_active) ?>
    <?= $Form->checkbox('Hot', 'dis_hot', $dis_hot) ?>
    <?= $Form->select('Chọn thành phố', 'dis_city_id', $city_data, $dis_city_id, true) ?>

    <?= $Form->file('Ảnh trang chủ quận huyện', 'dis_page_cover') ?>
    <div id="previewImageBanner" class="row text-center overflow-hidden" style="gap: 30px;">
        <? if ($dis_page_cover) {
            echo '<div class="col-md-5 mb-3 position-relative" style="height: 200px; width: 200px;">
                <img src="' . DOMAIN_UPLOADS . '/district/' . $dis_page_cover . '" class="img-fluid" style="object-fit: cover; height: 100%;">
                </div>';
        } ?>
    </div>
    <?= $Form->file('Ảnh quận huyện', 'dis_image') ?>
    <div id="previewImageDistrict" class="row text-center overflow-hidden" style="gap: 30px;">
        <? if ($dis_image) {
            echo '<div class="col-md-5 mb-3 position-relative" style="height: 200px; width: 200px;">
                <img src="' . DOMAIN_UPLOADS . '/district_cover/' . $dis_image . '" class="img-fluid" style="object-fit: cover; height: 100%;">
                </div>';
        } ?>
    </div>
    <div style="display: none">
        <input id="pac-input" class="controls" type="text" placeholder="Enter a location" />
    </div>
    <div id="map" style="height: 400px;"></div>

    <?= $Form->text('Latitude', 'dis_lat_center', $dis_lat_center, true, '', 'readonly') ?>
    <?= $Form->text('Longitude', 'dis_lng_center', $dis_lng_center, true, '', 'readonly') ?>
    <?= $Form->button('Thêm mới') ?>
    <?= $Form->closeForm() ?>
    <?
    $Layout->footer();
    ?>
    <?
    $lat = $record_info['dis_lat_center'];
    $lng = $record_info['dis_lng_center'];
    $Layout->loadMapInit($lat, $lng);
    ?>
    <script>
        // Khởi tạo preview cho tất cả các trường tải ảnh
        setupImagePreview('dis_image', 'previewImageDistrict');
        setupImagePreview('dis_page_cover', 'previewImageBanner');
    </script>
</body>

</html>