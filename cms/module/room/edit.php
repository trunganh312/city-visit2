<?
include('./config_module.php');
$Admin->checkPermission('admin_edit');

/** --- Khai báo một số biến cơ bản --- **/
$table          =   'room';
$page_title     =   'Sửa thông tin phòng';
$field_id       =   'roo_id';
$record_id      =   getValue('id');
$record_info    =   $DB->query("SELECT * FROM " . $table . " WHERE " . $field_id . " = " . $record_id)->getOne();
if (empty($record_info)) {
    exit('Dữ liệu này không tồn tại!');
}
// Láy ra danh sách image theo room id
$list_image = $DB->query('SELECT * FROM room_image WHERE rimg_room_id = ' . $record_id)->toArray();

// Lấy tất cả các tiện ích và nhóm của chúng
$query = "SELECT ame_id, ame_name, ame_group_id, amg_name FROM amenity 
          JOIN amenity_group ON amenity.ame_group_id = amenity_group.amg_id";
$allAmenities = $DB->query($query)->toArray();

// Lấy các tiện ích đã được chọn cho khách sạn
$selectedQuery = "SELECT ram_amenity_id FROM room_amenities WHERE ram_room_id = " . intval($record_id);
$selectedAmenities = $DB->query($selectedQuery)->toArray();
$selectedAmenityIds = array_column($selectedAmenities, 'ram_amenity_id');

/** --- End of Khai báo một số biến cơ bản --- **/

/** --- Class query để insert dữ liệu --- **/
$Query  =   new GenerateQuery($table);
$Query->add('roo_name', DATA_STRING, '', 'Bạn chưa nhập tên hạng phòng')
    ->add('roo_view', DATA_STRING, '')
    ->add('roo_size_person', DATA_STRING, '')
    ->add('roo_size', DATA_INTEGER, '')
    ->add('roo_description', DATA_STRING, '')
    ->add('roo_bed', DATA_STRING, '')
    ->add('roo_promotion', DATA_INTEGER, 0)
    ->add('roo_active', DATA_INTEGER, 0)
    ->add('roo_hotel_id', DATA_INTEGER, '')
    ->add('roo_price', DATA_DOUBLE, 0)
    ->add('roo_breakfast', DATA_INTEGER, 1);
/** --- End of Class query để insert dữ liệu --- **/

/** Class Image để upload ảnh */
// $Image  = new Image();
$Upload = new Upload('', '');
/** End of Class Image để upload ảnh */


/** --- Submit form --- **/
if ($Query->submitForm()) {
    // Validate form
    if ($Query->validate($field_id, $record_id)) {

        // Xử lý upload banner phòng
        if (isset($_FILES['roo_cover']) && $_FILES['roo_cover']['error'] === UPLOAD_ERR_OK) {
            $fileName =  $Upload->uploadSingleImage($_FILES['roo_cover'], 'room_cover');
            $Query->add('roo_cover', DATA_STRING, $fileName);
        }

        // Lấy được mảng id các tiện nghi
        // Xóa các tiện nghi của phòng đó trong bảng tiện nghi
        // Sau khi xóa lặp qua mảng id tiện nghi để insert vào bảng room_amenities 
        $amenityIds = isset($_POST['amenities']) ? $_POST['amenities'] : [];
        $DB->execute("DELETE FROM room_amenities WHERE ram_room_id = $record_id");
        foreach ($amenityIds as $amenityId) {
            $DB->execute("INSERT INTO room_amenities (ram_room_id, ram_amenity_id) VALUES ($record_id, $amenityId)");
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
    <?= $Form->select2('Chọn khách sạn', 'roo_hotel_id', $hotel_data, $roo_hotel_id, true) ?>
    <?= $Form->text('Tên phòng', 'roo_name', $roo_name, true) ?>
    <?= $Form->number('Giá', 'roo_price', $roo_price, true) ?>
    <?= $Form->number('Diện tích', 'roo_size', $roo_size, true) ?>
    <?= $Form->checkbox('Active', 'roo_active', $roo_active) ?>
    <?= $Form->checkbox('Khuyến mại', 'roo_promotion', $roo_promotion) ?>
    <?= $Form->checkbox('Ăn sáng', 'roo_breakfast', $roo_breakfast) ?>
    <?= $Form->select('Hạng giường', 'roo_bed', $bed_data, $roo_bed, true) ?>
    <?= $Form->select('Số người', 'roo_size_person', $person_data, $roo_size_person, true) ?>
    <?= $Form->select('View', 'roo_view', $view_data, $roo_view, true) ?>
    <?= $Form->text('Tiện ích phòng', '', '', false, '', 'hidden') ?>
    <div class="form-group">
        <?php foreach ($resultArray as $groupName => $amenities) : ?>
            <label><?php echo htmlspecialchars($groupName); ?> :</label>
            <?php foreach ($amenities as $amenity) : ?>
                <?= $Form->checkbox(htmlspecialchars($amenity['ame_name']), 'amenities[]', in_array($amenity['ame_id'], $selectedAmenityIds), $amenity['ame_id']) ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
    <?= $Form->file('Ảnh trang chủ khách sạn', 'roo_cover') ?>
    <div id="previewImageBanner">
        <? if ($roo_cover) {
            echo '<div class="col-md-5 mb-3 position-relative" style="height: 200px; width: 200px;">
                <img src="' . DOMAIN_UPLOADS . '/room_cover/' . $roo_cover . '" class="img-fluid" style="object-fit: cover; height: 100%;">
                </div>';
        } ?>
    </div>
    <?= $Form->textarea('Mô tả', 'roo_description', $roo_description, true,  '', 'hidden') ?>
    <div class="form-group mx-5 my-3">
        <div id="editor">
            <?= html_entity_decode($record_info['roo_description'])  ?>
        </div>
    </div>
    <div class="form-group">
        <label for=""></label>
        <a class="" href="upload_image.php?roo_id=<?= $record_id ?>">Upload ảnh</a>
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
        setupImagePreview('roo_cover', 'previewImageBanner');
    </script>
    <script>
        const editor = new Quill('#editor', options);
        insertText('#roo_description');
    </script>
</body>

</html>