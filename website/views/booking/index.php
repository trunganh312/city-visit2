<?
include_once('config_module.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?=
    $Layout->loadHead('Đặt phòng');
    ?>
</head>

<body>
    <?=
    $Layout->loadHeaderList();
    ?>
    <!-- Content -->
    <main id="content" class="bg-gray space-2">
        <!-- Breadcrumbs -->
        <?
        $arrBreadcrumbs = array(
            'Trang chủ' => '/',
            'Danh sách khách sạn' => returnUrlCity(),
            $room['hot_name'] =>  returnDomain(['hotel', $room['hot_slug']])
        );
        echo showBreadcrumbs($arrBreadcrumbs);
        ?>
        <!-- End Breadcrumbs -->
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-xl-9">
                    <div class="mb-5 shadow-soft bg-white rounded-sm">
                        <div class="pt-4 pb-5 px-5">
                            <h5 id="scroll-description" class="font-size-21 font-weight-bold text-dark mb-4">
                                Thông tin đặt phòng
                            </h5>
                            <!-- Contacts Form -->
                            <? include('form.php') ?>
                            <!-- End Contacts Form -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xl-3">
                    <div class="shadow-soft bg-white rounded-sm">
                        <!-- Room detail -->
                        <? include('room_detail.php')  ?>
                        <!-- End Room detail -->
                        <!-- Basics Accordion -->
                        <div id="basicsAccordion">
                            <!-- Card -->
                            <div class="card rounded-0 border-top-0 border-left-0 border-right-0">
                                <div class="card-header card-collapse bg-transparent border-0" id="basicsHeadingFour">
                                    <h5 class="mb-0">
                                        <button type="button" class="btn btn-link border-0 btn-block d-flex justify-content-between card-btn py-3 px-4 font-size-17 font-weight-bold text-dark" data-toggle="collapse" data-target="#basicsCollapseFour" aria-expanded="false" aria-controls="basicsCollapseFour">
                                            Thanh toán

                                            <span class="card-btn-arrow font-size-14 text-dark">
                                                <i class="fas fa-chevron-down"></i>
                                            </span>
                                        </button>
                                    </h5>
                                </div>
                                <div id="basicsCollapseFour" class="collapse show" aria-labelledby="basicsHeadingFour" data-parent="#basicsAccordion">
                                    <div class="card-body px-4 pt-0">
                                        <!-- Fact List -->
                                        <ul class="list-unstyled font-size-1 mb-0 font-size-16">
                                            <li class="d-flex justify-content-between py-2">
                                                <span class="font-weight-medium">Tổng tiền</span>
                                                <span class="text-secondary"><?= formatVND($room['roo_price'])  ?>đ</span>
                                            </li>

                                            <li class="d-flex justify-content-between py-2">
                                                <span class="font-weight-medium">Giảm giá</span>
                                                <span class="text-secondary">0đ</span>
                                            </li>

                                            <li class="d-flex justify-content-between py-2">
                                                <span class="font-weight-medium">Thuế</span>
                                                <span class="text-secondary">10%</span>
                                            </li>

                                            <li class="d-flex justify-content-between py-2 font-size-17 font-weight-bold">
                                                <span class="font-weight-bold">Thanh toán</span>
                                                <span class=""><?= formatVND($room['roo_price'] + ($room['roo_price'] * 0.01))  ?>đ</span>
                                            </li>
                                        </ul>
                                        <!-- End Fact List -->
                                    </div>
                                </div>
                            </div>
                            <!-- End Card -->
                        </div>
                        <!-- End Basics Accordion -->
                    </div>
                </div>
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

                // Collect all selected values
                var selectedValues = [];
                $(`input[name^="${name}"]`).each(function() {
                    if ($(this).is(':checked')) {
                        selectedValues.push($(this).val());
                    }
                });

                if (name === 'amenity') {
                    // Remove existing parameters for `amenity`
                    urlParams.delete(name);
                    // Add new parameters
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