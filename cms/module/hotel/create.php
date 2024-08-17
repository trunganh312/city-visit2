<?
include('./config_module.php');
$Admin->checkPermission('admin_create');


/** --- Khai báo một số biến cơ bản --- **/
$page_title =   'Thêm mới khách sạn';
/** --- End of Khai báo một số biến cơ bản --- **/

/** --- Class query để insert dữ liệu --- **/
$Query          =   new GenerateQuery('hotel');
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
    if ($Query->validate()) {

        // Xử lý upload banner hotel
        if (isset($_FILES['hot_page_cover']) && $_FILES['hot_page_cover']['error'] === UPLOAD_ERR_OK) {
            $fileName =  $Upload->uploadSingleImage($_FILES['hot_page_cover'], 'hotel_cover');
            $Query->add('hot_page_cover', DATA_STRING, $fileName);
        }
        // Lấy ra hotel_id khi tạo mơi hotel
        $hotel_id   =   $DB->executeReturn($Query->generateQueryInsert());

        if ($hotel_id > 0) {

            // Xử lý thêm tiện ích khách sạn
            if (isset($_POST['amenities'])) {
                $selectedAmenities = $_POST['amenities'];

                // Xử lý dữ liệu các tiện ích đã chọn
                foreach ($selectedAmenities as $amenityId) {
                    $DB->execute("INSERT INTO hotel_amenities (hta_hotel_id, hta_amenity_id) VALUES ($hotel_id, '{$amenityId}')");
                }
            }

            redirect_url('upload_image.php?hotel_id=' . $hotel_id);
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
    <?= $Form->text('Tên khách sạn', 'hot_name', $hot_name, true) ?>
    <?= $Form->text('Slug', 'hot_slug', $hot_slug, true, '', 'readonly') ?>
    <?= $Form->text('Tên khác', 'hot_name_other', $hot_name_other) ?>
    <?= $Form->text('Địa chỉ', 'hot_address_map', $hot_address_map) ?>
    <?= $Form->number('Giá', 'hot_price', $hot_price, true) ?>
    <?= $Form->checkbox('Active', 'hot_active', $hot_active) ?>
    <?= $Form->checkbox('Hot', 'hot_hot', $hot_hot) ?>
    <?= $Form->checkbox('Khuyến mại', 'hot_promotion', $hot_promotion) ?>
    <?= $Form->checkbox('Priority', 'hot_priority', $hot_priority) ?>
    <?= $Form->select('Hạng sao', 'hot_star', $rate_data, $hot_star, true) ?>
    <?= $Form->select('Kiểu khách sạn', 'hot_type', $type_data, $hot_type, true) ?>
    <?= $Form->select('Chọn thành phố', 'hot_city_id', $city_data, $hot_city_id, true) ?>
    <?= $Form->select('Chọn huyện', 'hot_district_id', array(), $hot_district_id, true) ?>
    <?= $Form->text('Tiện ích khách sạn', '', '', false, '', 'hidden') ?>
    <div class="form-group">
        <?php foreach ($resultArray as $groupName => $amenities) : ?>
            <label><?php echo htmlspecialchars($groupName); ?> :</label>
            <?php foreach ($amenities as $amenity) : ?>
                <?= $Form->checkbox(htmlspecialchars($amenity['ame_name']), 'amenities[]', '', $amenity['ame_id']) ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
    <?= $Form->file('Ảnh trang chủ khách sạn', 'hot_page_cover') ?>
    <div id="previewHotelBanner" class="row text-center overflow-hidden" style="gap: 30px;"></div>
    <div style="display: none">
        <input id="pac-input" class="controls" type="text" placeholder="Enter a location" />
    </div>
    <div id="map" style="height: 400px;"></div>
    <?= $Form->text('Latitude', 'hot_lat', $hot_lat, true, '', 'readonly') ?>
    <?= $Form->text('Longitude', 'hot_lng', $hot_lng, true, '', 'readonly') ?>
    <?= $Form->textarea('Nội dung', 'hot_content', $hot_content, true, '', 'hidden') ?>
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
        // Chuyển đổi dữ liệu huyện từ PHP sang JavaScript
        const districtsByCity = <?= $districts_json ?>;

        document.getElementById('hot_city_id').addEventListener('change', function() {
            const cityId = this.value;
            const districtSelect = document.getElementById('hot_district_id');

            // Xóa các tùy chọn huyện cũ
            districtSelect.innerHTML = '<option value="">--Chọn huyện--</option>';

            // Thêm các tùy chọn huyện mới dựa trên tỉnh đã chọn
            if (cityId && districtsByCity[cityId]) {
                districtsByCity[cityId].forEach(function(district) {
                    const option = document.createElement('option');
                    option.value = district.id;
                    option.textContent = district.name;
                    districtSelect.appendChild(option);
                });
            }
        });
    </script>
    <script>
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