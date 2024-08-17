<!-- On Target Modal -->
<div id="ontargetModal" class="js-modal-window u-modal-window max-height-100vh  overflow-hidden" data-modal-type="ontarget" style="width: 100vw;" data-open-effect="zoomIn" data-close-effect="zoomOut" data-speed="500">
    <div class="bg-white">
        <div class="border-bottom py-xl-2">
            <div class="row d-block d-md-flex flex-horizontal-center mx-0">
                <div class="col-xl">
                    <!-- Nav Links -->
                    <ul class="row nav align-items-center py-xl-1 px-0 mb-3 mb-xl-0 border-bottom border-xl-bottom-0" role="tablist">
                        <li class="col-6 col-md-3 col-lg-auto border-bottom border-xl-bottom-0 mx-0 col-xl-auto nav-item d-flex align-items-center flex-shrink-0 flex-xl-shrink-1 font-size-20 text-dark" style="font-weight: 600;">
                            Thành Phố Đà Nẵng
                        </li>
                        <li class="col-6 col-md-3 col-lg-auto border-bottom border-xl-bottom-0 mx-0 col-xl-auto nav-item d-flex align-items-center flex-shrink-0 flex-xl-shrink-1">
                            <select id="starSelect" class="js-select selectpicker dropdown-select bootstrap-select__custom-nav w-xl-auto" data-style="btn-sm py-2 px-3 px-xl-3 px-wd-4 font-size-16 text-dark d-flex align-items-center" name="star">
                                <option selected value="">Hạng sao</option>
                                <option value="1">⭐</option>
                                <option value="2">⭐⭐</option>
                                <option value="3">⭐⭐⭐</option>
                                <option value="4">⭐⭐⭐⭐</option>
                                <option value="5">⭐⭐⭐⭐⭐</option>
                            </select>
                        </li>
                        <li class="col-6 col-md-3 col-lg-auto border-bottom border-xl-bottom-0 mx-0 col-xl-auto nav-item d-flex align-items-center flex-shrink-0 flex-xl-shrink-1 border-left">
                            <select id="typeSelect" class="js-select selectpicker dropdown-select bootstrap-select__custom-nav w-xl-auto" data-style="btn-sm py-2 px-3 px-xl-3 px-wd-4 font-size-16 text-dark d-flex align-items-center">
                                <option value="" selected>Loại hình</option>
                                <?
                                foreach ($type_data as $type) { ?>
                                    <option value="<?= $type ?>"><?= $type ?></option>
                                <?php } ?>
                            </select>
                        </li>
                        <li>
                            <button type="button" class="btn btn-wide btn-blue-1 font-weight-normal font-size-14 rounded-xs  mb-xl-0" aria-label="Close" onclick="Custombox.modal.close();">
                                <span aria-hidden="true">Quay lại trang tìm kiếm</span>
                                <i class="fas fa-times font-size-15 ml-3"></i>
                            </button>
                        </li>
                    </ul>
                    <!-- End Nav Links -->
                </div>

            </div>
        </div>
        <div class="height-100vh-72">
            <div class="row no-gutters">
                <div class="col-lg-5 col-xl-4 col-wd-3gdot5 order-1 order-lg-0 d-md-0">
                    <div class="pt-4 px-4 px-xl-5">
                        <div class="js-scrollbar height-100vh-72">
                            <ul class="d-block list-unstyled">
                                <? foreach ($hotels as $hotel) : ?>
                                    <li class="card mb-4 overflow-hidden">
                                        <div class="product-item__outer w-100">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="product-item__header">
                                                        <div class="position-relative">
                                                            <a target="_blank" href="<?= returnDomain(['hotel', $item['hot_slug']])  ?>" class="d-block gradient-overlay-half-bg-gradient-v5"><img class="img-fluid min-height-150 card-img-top" src="<?= DOMAIN_UPLOADS ?>/hotel_cover/<?= $hotel['hot_page_cover'] ?>" /></a>
                                                        </div>
                                                        <div class="position-absolute bottom-0 left-0 right-0">
                                                            <div class="px-4 pb-3">
                                                                <a target="_blank" href="<?= returnDomain(['hotel', $item['hot_slug']])  ?>" class="d-block">
                                                                    <div class="d-flex align-items-center font-size-14 text-white">
                                                                        <i class="icon flaticon-pin-1 mr-2 font-size-20"></i>
                                                                        <?= $hotel['dis_address_map'] ?>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 flex-horizontal-center">
                                                    <div class="w-100 position-relative m-4 m-md-0">
                                                        <div class="mb-1 pb-1">
                                                            <span class="green-lighter ml-2">
                                                                <?= star($hotel['hot_star'])  ?>
                                                            </span>
                                                        </div>
                                                        <div style="flex: 1">
                                                            <a target="_blank" href="<?= returnDomain(['hotel', $hotel['hot_slug']])  ?>" class="card-title font-size-17 font-weight-medium text-dark"><?= $hotel['hot_name']  ?></a>
                                                        </div>
                                                        <div class="card-body p-0">
                                                            <div class="my-2">
                                                                <span class="badge badge-pill badge-primary py-1 px-2 font-size-14 border-radius-3 font-weight-normal"><?= $hotel['hot_star']  ?>/5</span>
                                                            </div>
                                                            <div class="mb-0">
                                                                <span class="mr-1 font-size-14 text-gray-1">Chỉ từ</span>
                                                                <span class="font-weight-bold"><?= formatVND($hotel['hot_price']) ?></span>
                                                                <span class="font-size-14 text-gray-1">
                                                                    / đêm</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                <? endforeach; ?>

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 col-xl-8 col-wd-8gdot5" style="height: 85vh;">
                    <div style="display: none">
                        <input id="pac-input" class="controls" type="text" placeholder="Enter a location" />
                    </div>
                    <div id="map" class="h-100">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End On Target Modal -->

<script>
    // Biến để lưu trữ giá trị của star và type
    let currentStarRating = '';
    let currentType = '';

    // Cập nhật giá trị starRating và gọi API khi thay đổi
    document.getElementById('starSelect').addEventListener('change', function() {
        currentStarRating = this.value;
        fetchData();
    });

    // Cập nhật giá trị type và gọi API khi thay đổi
    document.getElementById('typeSelect').addEventListener('change', function() {
        currentType = this.value;
        fetchData();
    });

    // Hàm gọi API với cả hai tham số
    function fetchData() {
        // Tạo URL với cả hai tham số
        let url = '/ajax/preview_map.php?';
        if (currentStarRating) {
            url += 'star=' + encodeURIComponent(currentStarRating) + '&';
        }
        if (currentType) {
            url += 'type=' + encodeURIComponent(currentType);
        }

        // Gọi API bằng AJAX
        fetch(url)
            .then(response => response.json())
            .then(data => {
                // Xử lý dữ liệu nhận được từ API và cập nhật giao diện người dùng
                updateHotelList(data);
            })
            .catch(error => console.error('Error:', error));
    }

    function updateHotelList(data) {
        var hotelList = document.querySelector('.js-scrollbar .list-unstyled');
        hotelList.innerHTML = '';

        data.hotels.forEach(hotel => {
            var hotelItem = `
                <li class="card mb-4 overflow-hidden" >
                    <div class="product-item__outer w-100">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="product-item__header">
                                    <div class="position-relative">
                                        <a target="_blank" href="/hotel/${hotel.hot_slug}" class="d-block gradient-overlay-half-bg-gradient-v5">
                                            <img class="img-fluid min-height-150 card-img-top" src="<?= DOMAIN_UPLOADS ?>/hotel_cover/${hotel.hot_page_cover}" />
                                        </a>
                                    </div>
                                    <div class="position-absolute bottom-0 left-0 right-0">
                                        <div class="px-4 pb-3">
                                            <a target="_blank" href="/hotel/${hotel.hot_slug}" class="d-block">
                                                <div class="d-flex align-items-center font-size-14 text-white">
                                                    <i class="icon flaticon-pin-1 mr-2 font-size-20"></i>
                                                    ${hotel.dis_address_map}
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 flex-horizontal-center">
                                <div class="w-100 position-relative m-4 m-md-0">
                                    <div class="mb-1 pb-1">
                                        <span class="green-lighter ml-2">
                                            ${getStarRating(hotel.hot_star)}
                                        </span>
                                    </div>
                                    <div style="flex: 1">
                                        <a target="_blank" href="/hotel/${hotel.hot_slug}" class="card-title font-size-17 font-weight-medium text-dark">${hotel.hot_name}</a>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="my-2">
                                            <span class="badge badge-pill badge-primary py-1 px-2 font-size-14 border-radius-3 font-weight-normal">${hotel.hot_star}/5</span>
                                        </div>
                                        <div class="mb-0">
                                            <span class="mr-1 font-size-14 text-gray-1">Chỉ từ</span>
                                            <span class="font-weight-bold">${formatVND(hotel.hot_price)}</span>
                                            <span class="font-size-14 text-gray-1">
                                                / đêm</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            `;
            hotelList.innerHTML += hotelItem;
        });
    }

    function getStarRating(rate) {
        let stars = '';
        for (let i = 0; i < rate; i++) {
            stars += '<i class="fas fa-star"></i>';
        }
        return stars;
    }

    function formatVND(price) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(price);
    }
</script>