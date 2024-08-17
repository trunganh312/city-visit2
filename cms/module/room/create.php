<?
include('./config_module.php');
$Admin->checkPermission('admin_create');
/** --- Khai báo một số biến cơ bản --- **/
$page_title =   'Thêm mới hạng phòng';
/** --- End of Khai báo một số biến cơ bản --- **/

/** --- Class query để insert dữ liệu --- **/
$Query          =   new GenerateQuery('room');
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
    if ($Query->validate()) {

        // Xử lý upload banner phòng
        if (isset($_FILES['roo_cover']) && $_FILES['roo_cover']['error'] === UPLOAD_ERR_OK) {
            $fileName =  $Upload->uploadSingleImage($_FILES['roo_cover'], 'room_cover');
            $Query->add('roo_cover', DATA_STRING, $fileName);
        }
        // Lấy ra roo_id khi tạo mơi hotel
        $roo_id   =   $DB->executeReturn($Query->generateQueryInsert());

        if ($roo_id > 0) {

            // Xử lý thêm tiện ích phòng
            if (isset($_POST['amenities'])) {
                $selectedAmenities = $_POST['amenities'];

                // Xử lý dữ liệu các tiện ích đã chọn
                foreach ($selectedAmenities as $amenityId) {
                    $DB->execute("INSERT INTO room_amenities (ram_room_id, ram_amenity_id) VALUES ($roo_id, '{$amenityId}')");
                }
            }

            redirect_url('upload_image.php?roo_id=' . $roo_id . '&hotel_id=' . $roo_hotel_id);
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

<body class="sidebar-mini listing_page">
    <?
    $Layout->header($page_title);
    ?>
    <?
    //Tạo ra các biến sẵn để fill vào form dựa vào các hàm add trường dữ liệu ở trên (GenerateQuery)
    $Query->generateVariable();
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
                <?= $Form->checkbox(htmlspecialchars($amenity['ame_name']), 'amenities[]', '', $amenity['ame_id']) ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
    <?= $Form->file('Ảnh phòng', 'roo_cover') ?>
    <div id="previewRoomBanner" class="row text-center overflow-hidden" style="gap: 30px;"></div>
    <?= $Form->textarea('Mô tả', 'roo_description', $roo_description, true, '', 'hidden') ?>
    <div class="form-group mx-5 my-3">
        <div id="editor"></div>
    </div>
    <?= $Form->button('Thêm mới') ?>
    <?= $Form->closeForm() ?>
    <?
    $Layout->footer();
    ?>
    <?
    $Layout->loadMapInit();
    ?>
    <script>
        setupImagePreview('roo_cover', 'previewRoomBanner');
    </script>
    <script>
        const editor = new Quill('#editor', options);
        insertText('#roo_description');
    </script>
</body>

</html>