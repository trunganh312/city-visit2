<?
include_once('config_module.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?=
    $Layout->loadHead('Danh sách khách sạn');
    ?>
</head>

<body>
    <?=
    $Layout->loadHeaderList();
    ?>
    <!-- Content -->
    <main id="content" role="main">
        <!-- Breadcrumbs -->
        <?
        $district = isset($_GET['district']) && !empty($_GET['district']) ? $_GET['district'] : 'Đà Nẵng';
        $arrBreadcrumbs = array(
            'Trang chủ' => '/',
            $district => '',
        );
        echo showBreadcrumbs($arrBreadcrumbs);
        ?>
        <!-- End Breadcrumbs -->
        <div class="container pt-5 pt-xl-8">
            <div class="row mb-5 mb-lg-8 mt-xl-1">
                <? include('sidebar.php') ?>
                <?= $HotelController->search(); ?>
            </div>
        </div>
    </main>
    <?=
    $Layout->loadfooter();
    ?>
    <script>
        const changeCheckbox = (name) => {
            $(`.${name}`).change(function() {
                var currentUrl = window.location.href.split('?');
                var baseUrl = currentUrl[0];
                var queryString = currentUrl[1] || '';
                var urlParams = new URLSearchParams(queryString);

                var selected = $(this).val();
                var checkboxName = $(this).attr('name');

                var selectedValues = [];
                $(`input[name^="${name}"]`).each(function() {
                    if ($(this).is(':checked')) {
                        selectedValues.push($(this).val());
                    }
                });

                if (name === 'amenity') {
                    urlParams.delete(`${name}[]`);
                    selectedValues.forEach((value, index) => {
                        urlParams.append(`${name}[]`, value);
                    });
                } else {
                    // Handle single value parameters (e.g., rating, type, district)
                    if ($(this).is(':checked')) {
                        urlParams.set(name, selected);
                    } else {
                        urlParams.delete(name);
                    }
                }

                var newUrl = baseUrl + '?' + urlParams.toString();
                window.location.href = newUrl;
            });
        }

        const changeSort = () => {
            $('select[name="sort"]').change(function() {
                // Lấy URL hiện tại
                var currentUrl = window.location.href.split('?');
                var baseUrl = currentUrl[0];
                var queryString = currentUrl[1] || '';
                var urlParams = new URLSearchParams(queryString);

                // Lấy giá trị đã chọn từ dropdown
                var selectedValue = $(this).val();

                // Cập nhật hoặc xóa tham số `sort` trong URL
                if (selectedValue) {
                    urlParams.set('sort', selectedValue);
                } else {
                    urlParams.delete('sort');
                }

                // Tạo URL mới và cập nhật URL mà không tải lại trang
                var newUrl = baseUrl + '?' + urlParams.toString();
                window.location.href = newUrl;
            });
        };

        $(document).ready(function() {
            changeCheckbox('rating');
            changeCheckbox('type');
            changeCheckbox('district');
            changeCheckbox('amenity');
            changeSort();
        });
    </script>



</body>

</html>