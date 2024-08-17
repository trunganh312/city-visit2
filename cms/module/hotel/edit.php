<?
include('./config_module.php');
$Admin->checkPermission('admin_edit');

/** --- Khai báo một số biến cơ bản --- **/
$table          =   'hotel';
$page_title     =   'Sửa thông tin khách sạn';
$field_id       =   'hot_id';
$record_id      =   getValue('id');
$record_info    =   $DB->query("SELECT * FROM " . $table . " WHERE " . $field_id . " = " . $record_id)->getOne();
if (empty($record_info)) {
    exit('Dữ liệu này không tồn tại!');
}
// Láy ra danh sách image theo hotel id
$list_image = $DB->query('SELECT * FROM hotel_image WHERE hti_hotel_id = ' . $record_id)->toArray();

// Lấy tất cả các tiện ích và nhóm của chúng
$query = "SELECT ame_id, ame_name, ame_group_id, amg_name FROM amenity 
          JOIN amenity_group ON amenity.ame_group_id = amenity_group.amg_id";
$allAmenities = $DB->query($query)->toArray();

// Lấy các tiện ích đã được chọn cho khách sạn
$selectedQuery = "SELECT hta_amenity_id FROM hotel_amenities WHERE hta_hotel_id = " . intval($record_id);
$selectedAmenities = $DB->query($selectedQuery)->toArray();
$selectedAmenityIds = array_column($selectedAmenities, 'hta_amenity_id');

/** --- End of Khai báo một số biến cơ bản --- **/

/** --- Class query để insert dữ liệu --- **/
$Query  =   new GenerateQuery($table);
$Query->add('hot_name', DATA_STRING, '', 'Bạn chưa nhập tên khách sạn')
    ->add('hot_name_other', DATA_STRING, '')
    ->add('hot_slug', DATA_STRING, '')
    ->add('hot_lat', DATA_DOUBLE, 1)
    ->add('hot_lng', DATA_DOUBLE, 1)
    ->add('hot_content', DATA_STRING, '', 'Bạn chưa nhập nội dung')
    ->add('hot_address_map', DATA_STRING, '')
    ->add('hot_active', DATA_INTEGER, 0)
    ->add('hot_promotion', DATA_INTEGER, 0)
    ->add('hot_type', DATA_STRING, '')
    ->add('hot_priority', DATA_INTEGER, 0)
    ->add('hot_city_id', DATA_INTEGER, '')
    ->add('hot_district_id', DATA_INTEGER, '')
    ->add('hot_price', DATA_DOUBLE, 0)
    ->add('hot_star', DATA_INTEGER, 1)
    ->add('hot_hot', DATA_INTEGER, 0);
/** --- End of Class query để insert dữ liệu --- **/

/** Class Image để upload ảnh */
// $Image  = new Image();
$Upload = new Upload('', '');
/** End of Class Image để upload ảnh */


/** --- Submit form --- **/
if ($Query->submitForm()) {
    // Validate form
    if ($Query->validate($field_id, $record_id)) {

        // Upload banner hotel
        if (isset($_FILES['hot_page_cover']) && $_FILES['hot_page_cover']['error'] === UPLOAD_ERR_OK) {
            $fileName =  $Upload->uploadSingleImage($_FILES['hot_page_cover'], 'hotel_cover');
            $Query->add('hot_page_cover', DATA_STRING, $fileName);
        }

        // Lấy được mảng id các tiện nghi
        // Xóa các tiện nghi của khách sạn đó trong bảng tiện nghi
        // Sau khi xóa lặp qua mảng id tiện nghi để insert vào bảng hotel_amenties 
        $amenityIds = isset($_POST['amenities']) ? $_POST['amenities'] : [];
        $DB->execute("DELETE FROM hotel_amenities WHERE hta_hotel_id = $record_id");
        foreach ($amenityIds as $amenityId) {
            $DB->execute("INSERT INTO hotel_amenities (hta_hotel_id, hta_amenity_id) VALUES ($record_id, $amenityId)");
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
    <?= $Form->text('Tên khách sạn', 'hot_name', $hot_name, true) ?>
    <?= $Form->text('Slug', 'hot_slug', $hot_slug, true, '', 'readonly') ?>
    <?= $Form->text('Tên khác', 'hot_name_other', $hot_name_other) ?>
    <?= $Form->text('Địa chỉ', 'hot_address_map', $hot_address_map) ?>
    <?= $Form->number('Giá', 'hot_price', $hot_price, true) ?>
    <?= $Form->checkbox('Active', 'hot_active', $hot_active) ?>
    <?= $Form->checkbox('Hot', 'hot_hot', $hot_hot) ?>
    <?= $Form->checkbox('Khuyến mại', 'hot_promotion', $hot_promotion) ?>
    <?= $Form->checkbox('Priority', 'hot_priority', $hot_priority) ?>
    <?= $Form->select('Rate', 'hot_star', $rate_data, $hot_star, true) ?>
    <?= $Form->select('Kiểu khách sạn', 'hot_type', $type_data, $hot_type, true) ?>
    <?= $Form->select('Chọn thành phố', 'hot_city_id', $city_data, $hot_city_id, true) ?>
    <?= $Form->select('Chọn huyện', 'hot_district_id', $district_data, $hot_district_id, true) ?>
    <?= $Form->text('Tiện ích khách sạn', '', '', false, '', 'hidden') ?>
    <div class="form-group">
        <?php foreach ($resultArray as $groupName => $amenities) : ?>
            <label><?php echo htmlspecialchars($groupName); ?> :</label>
            <?php foreach ($amenities as $amenity) : ?>
                <?= $Form->checkbox(htmlspecialchars($amenity['ame_name']), 'amenities[]', in_array($amenity['ame_id'], $selectedAmenityIds), $amenity['ame_id']) ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
    <?= $Form->file('Ảnh trang chủ khách sạn', 'hot_page_cover') ?>
    <div id="previewHotelBanner">
        <? if ($hot_page_cover) {
            echo '<div class="col-md-5 mb-3 position-relative" style="height: 200px; width: 200px;">
                <img src="' . DOMAIN_UPLOADS . '/hotel_cover/' . $hot_page_cover . '" class="img-fluid" style="object-fit: cover; height: 100%;">
                </div>';
        } ?>
    </div>
    <div class="form-group">
        <label for="">Upload ảnh: </label>
        <a class="btn btn-success ml-4" href="upload_image.php?hotel_id=<?= $record_id ?>">Upload ảnh</a>
    </div>
    <div style="display: none">
        <input id="pac-input" class="controls" type="text" placeholder="Enter a location" />
    </div>
    <div id="map" style="height: 400px;"></div>
    <?= $Form->text('Latitude', 'hot_lat', $hot_lat, true, '', 'readonly') ?>
    <?= $Form->text('Longitude', 'hot_lng', $hot_lng, true, '', 'readonly') ?>
    <?= $Form->textarea('Nội dung', 'hot_content', $hot_content, true, '', 'hidden') ?>
    <div class="form-group mx-5 my-3">
        <div id="editor">
            <?= html_entity_decode($record_info['hot_content'])  ?>
        </div>
    </div>
    <?= $Form->button('Cập nhật') ?>
    <?= $Form->closeForm() ?>
    <?
    $Layout->footer();
    ?>
    <?
    $Layout->loadMapInit();
    ?>
    <script>
        // Khởi tạo preview cho tất cả các trường tải ảnh
        setupImagePreview('imageUpload', 'previewContainer');
        setupImagePreview('hot_page_cover', 'previewHotelBanner');
    </script>
    <script>
        document.querySelector('#hot_name').addEventListener('input', (e) => {
            const slug = toSlug(e.target.value)
            document.querySelector('#hot_slug').value = slug;
        })
    </script>
    <script>
        const editor = new Quill('#editor', options);
        insertText('#hot_content');
    </script>
</body>

</html>