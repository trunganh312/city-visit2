<?


function formatVND($price)
{
    $formattedAmount = number_format($price, 0, ',', ',');
    return $formattedAmount;
}

// Show cục to 
// Chỉ dùng cho các huyện phổ biến trang chủ
function itemLarge($item)
{
    $url = returnUrlCity();
    $html = '<div class="col-md-6 mb-3 mb-md-4">
                            <div class="min-height-350 bg-img-hero rounded-border p-5 gradient-overlay-half-bg-gradient transition-3d-hover shadow-hover-2" style="background-image: url(' . DOMAIN_UPLOADS . '/district_cover/' . $item['dis_image'] . ')">
                            <header class="w-100 d-flex justify-content-between mb-3">
                            <div>
                                <div class="destination pb-3 text-lh-1">
                                    <a href="' . $url . '?district=' . $item['dis_name'] . '" class="text-white font-weight-bold font-size-21">' . $item['dis_name'] . '</a>
                                </div>
                                <div class="mt-1 pt-1">
                                    <a href="' . $url . '?district=' . $item['dis_name'] . '" class="text-white">' . $item['hotel_count'] . ' Hotel</a>
                                </div>
                            </div>
                        </header>
                        </div>
                    </div>';
    return $html;
}

// // Show cục nhỏ
// Chỉ dùng cho các huyện phổ biến trang chủ

function itemSmall($item)
{
    $url = returnUrlCity();
    $html = '<div class="col-md-6 col-xl-3 mb-3 mb-md-4 pb-1">
                <div class="min-height-350 bg-img-hero rounded-border p-5 gradient-overlay-half-bg-gradient transition-3d-hover shadow-hover-2" style="background-image: url(' . DOMAIN_UPLOADS . '/district_cover/' . $item['dis_image'] . ')">
                    <header class="w-100 d-flex justify-content-between mb-3">
                        <div>
                            <div class="destination pb-3 text-lh-1">
                                <a href="' . $url . '?district=' . $item['dis_name'] . '" class="text-white font-weight-bold font-size-21">' . $item['dis_name'] . '</a>
                            </div>
                            <div class="mt-1 pt-1">
                                <a href="' . $url . '?district=' . $item['dis_name'] . '" class="text-white">' . $item['hotel_count'] . ' Hotel</a>
                            </div>
                        </div>
                    </header>
                </div>
                </div>';
    return $html;
}

// Render star
function star($number)
{
    $html = '';
    for ($i = 0; $i < $number; $i++) {
        $html .= '<small class="fas fa-star font-size-10 mr-1"></small>';
    }
    return $html;
}

// Thêm page vào mà ko mất query trước
function getBaseUrlWithPage($currentPage)
{
    // Lấy URL hiện tại
    $currentUrl = $_SERVER['REQUEST_URI'];

    // Phân tích URL hiện tại
    $parsedUrl = parse_url($currentUrl);
    $query = isset($parsedUrl['query']) ? $parsedUrl['query'] : '';

    // Phân tích các tham số truy vấn hiện tại
    parse_str($query, $queryParams);

    // Thay đổi tham số phân trang hoặc thêm nếu chưa có
    $queryParams['page'] = $currentPage;

    // Xây dựng lại chuỗi truy vấn
    $newQuery = http_build_query($queryParams);

    // Tạo URL mới
    $baseUrl = $parsedUrl['path'] . '?' . $newQuery;

    return $baseUrl;
}


// Tạo pagination
function createPagination($currentPage, $totalPages)
{
    // Số trang tối đa được hiển thị
    $maxPagesToShow = 5;

    // Tạo base URL cho các liên kết phân trang
    $baseUrl = getBaseUrlWithPage('');

    $paginationHtml = '<ul class="list-pagination-1 pagination border border-color-4 rounded-sm overflow-auto overflow-xl-visible justify-content-md-center align-items-center py-2 mb-0">';

    // Trang trước
    if ($currentPage > 1) {
        $paginationHtml .= '<li class="page-item">
            <a class="page-link border-right rounded-0 text-gray-5" href="' . getBaseUrlWithPage($currentPage - 1) . '" aria-label="Previous">
                <i class="flaticon-left-direction-arrow font-size-10 font-weight-bold"></i>
                <span class="sr-only">Previous</span>
            </a>
        </li>';
    } else {
        $paginationHtml .= '<li class="page-item disabled">
            <span class="page-link border-right rounded-0 text-gray-5" aria-label="Previous">
                <i class="flaticon-left-direction-arrow font-size-10 font-weight-bold"></i>
                <span class="sr-only">Previous</span>
            </span>
        </li>';
    }

    // Tính toán khoảng trang cần hiển thị
    $startPage = max(1, $currentPage - floor($maxPagesToShow / 2));
    $endPage = min($totalPages, $startPage + $maxPagesToShow - 1);

    // Điều chỉnh startPage nếu endPage nhỏ hơn maxPagesToShow
    if ($endPage - $startPage + 1 < $maxPagesToShow) {
        $startPage = max(1, $endPage - $maxPagesToShow + 1);
    }

    // Tạo các liên kết trang
    for ($page = $startPage; $page <= $endPage; $page++) {
        if ($page == $currentPage) {
            $paginationHtml .= '<li class="page-item"><a class="page-link font-size-14 active" href="#">' . $page . '</a></li>';
        } else {
            $paginationHtml .= '<li class="page-item"><a class="page-link font-size-14" href="' . getBaseUrlWithPage($page) . '">' . $page . '</a></li>';
        }
    }

    // Trang tiếp theo
    if ($currentPage < $totalPages) {
        $paginationHtml .= '<li class="page-item">
            <a class="page-link border-left rounded-0 text-gray-5" href="' . getBaseUrlWithPage($currentPage + 1) . '" aria-label="Next">
                <i class="flaticon-right-thin-chevron font-size-10 font-weight-bold"></i>
                <span class="sr-only">Next</span>
            </a>
        </li>';
    } else {
        $paginationHtml .= '<li class="page-item disabled">
            <span class="page-link border-left rounded-0 text-gray-5" aria-label="Next">
                <i class="flaticon-right-thin-chevron font-size-10 font-weight-bold"></i>
                <span class="sr-only">Next</span>
            </span>
        </li>';
    }

    $paginationHtml .= '</ul>';
    return $paginationHtml;
}


// Get url hiện tại
function getCurrentUrl()
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $currentUrl = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    return $currentUrl;
}

// Show item
function itemHotel($hotel)
{
    $html = '<div class="card transition-3d-hover shadow-hover-2 h-100 w-100">
                        <div class="position-relative">
                            <a href="' . returnDomain(['hotel', $hotel['hot_slug']]) . '" class="d-block gradient-overlay-half-bg-gradient-v5">
                                <img class="card-img-top" src="' . DOMAIN_UPLOADS . '/hotel_cover/' . $hotel['hot_page_cover'] . '" alt="Image Description" />
                            </a>
                            <div class="position-absolute bottom-0 left-0 right-0">
                                <div class="px-4 pb-3">
                                    <a href="' . returnDomain(['hotel', $hotel['hot_slug']]) . '" class="d-block" >
                                        <div class="d-flex align-items-center font-size-14 text-white">
                                            <i class="icon flaticon-placeholder mr-2 font-size-20"></i>
                                            ' . $hotel['dis_address_map'] . '
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-4 pt-2 pb-3" style="display: flex;flex-direction: column;
                            ">
                            <div class="mb-2">
                                <div class="d-inline-flex align-items-center font-size-13 text-lh-1 text-primary letter-spacing-3">
                                    <div class="green-lighter"
                                    ' . star($hotel['hot_star'] + 1) . '
                                    </div>
                                </div>
                            </div>
                            <div style="flex: 1">
                            <a href="' . returnDomain(['hotel', $hotel['hot_slug']]) . '" class="card-title font-size-17 font-weight-medium text-dark">' . $hotel['hot_name'] . '</a>
                            </div>';
    if ($hotel['total_reviews'] > 0) {
        $html .= ' <div class="mt-2 mb-3">
                                <span class="badge badge-pill badge-primary py-1 px-2 font-size-14 border-radius-3 font-weight-normal">' . $hotel['average_rating'] . '/5</span>
                                 <span class="font-size-14 text-gray-1 ml-2">(' . $hotel['total_reviews'] . ' đánh giá)</span>
                            </div>';
    }

    $html .= '<div class="mb-2">
                                <span class="mr-1 font-size-14 text-gray-1">Chỉ từ</span>
                                <span class="font-weight-bold">' . formatVND($hotel['hot_price']) . 'VNĐ</span>
                                <span class="font-size-14 text-gray-1"> / đêm</span>
                            </div>
                             <div class="mb-0 w-100">
                                <a href="' . returnDomain(['hotel', $hotel['hot_slug']]) . '" class="btn btn-primary p-1 w-100">Xem thêm</a>
                            </div>
                        </div>
                    </div>';
    return $html;
}

//  review
function review($averages)
{
    $reviews = array(
        array(
            'rate' => $averages['rev_cleanliness'],
            'category' => 'Sạch sẽ',
            'range' => round($averages['rev_cleanliness'] / 5 * 100) . '%'
        ),
        array(
            'rate' => $averages['rev_amenities'],
            'category' => 'Tiện ích',
            'range' => round($averages['rev_amenities'] / 5 * 100) . '%'
        ),
        array(
            'rate' => $averages['rev_money'],
            'category' => 'Giá cả',
            'range' => round($averages['rev_money'] / 5 * 100) . '%'
        ),
        array(
            'rate' => $averages['rev_service'],
            'category' => 'Dịch vụ',
            'range' => round($averages['rev_service'] / 5 * 100) . '%'
        ),
        array(
            'rate' => $averages['rev_customer_support'],
            'category' => 'Chăm sóc khách hàng',
            'range' => round($averages['rev_customer_support'] / 5 * 100) . '%'
        ),
        array(
            'rate' => $averages['rev_location'],
            'category' => 'Vị trí',
            'range' => round($averages['rev_location'] / 5 * 100) . '%'
        ),
    );
    $html = '';
    foreach ($reviews as $review) {
        $html .= '<div class="col-md-6 mb-4">
                    <h6 class="font-weight-normal text-gray-1 mb-1">
                        ' . $review['category'] . '
                    </h6>
                    <div class="flex-horizontal-center mr-6">
                        <div class="progress bg-gray-33 rounded-pill w-100" style="height: 7px;">
                            <div class="progress-bar rounded-pill" role="progressbar" style="width: ' . $review['range'] . ';" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="ml-3 text-primary font-weight-bold">
                            ' . $review['rate'] . '
                        </div>
                    </div>
                </div>';
    }
    return $html;
}

// Base url
function base_url_web()
{
    return sprintf(
        "%s://%s/",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['SERVER_NAME']
    );
}

// Show breadcrumbs
function showBreadcrumbs($arrBreadcrumbs = [])
{
    $html = '<div class="border-bottom">
    <div class="container">
        <nav class="py-3" aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-no-gutter mb-0 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visble">';
    foreach ($arrBreadcrumbs as $key => $item) {
        $html .= '<li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1"><a href="' . $item . '">
            ' . $key . '
        </a></li>';
    }

    $html .= "</ol>
                </nav>
            </div>
        </div>";

    return $html;
}

// Show gallary
// Truyền vào mảng ảnh
function showGallary($images, $folder)
{
    $html = '<div class="row mx-n1" >
                        <!-- Hiển thị 1 cái ảnh đầu tiên của mảng -->
                        <div class="col-lg-8 col-xl-9 mb-1 mb-lg-0 px-0 px-lg-1" style="max-height: 470px">
                            <a style=" height: 100%;" class="js-fancybox u-media-viewer h-100" href="javascript:;" data-src="' . DOMAIN_UPLOADS . '/' . $folder . '/' . $images[0] . '" data-fancybox="fancyboxGallery6" data-caption="Ảnh #1" data-speed="700">
                                <img style="width: 100%; height: 100%;" class="img-fluid border-radius-3 min-height-458" src="' . DOMAIN_UPLOADS . '/' . $folder . '/' . $images[0] . '" alt="Image Description">
                                <span class="u-media-viewer__container">
                                    <span class="u-media-viewer__icon">
                                        <span class="fas fa-plus u-media-viewer__icon-inner"></span>
                                    </span>
                                </span>
                            </a>
                        </div>
                        <div class="col-lg-4 col-xl-3 px-0">
                            <div class="d-flex" style="justify-content: space-around;flex-direction: column;gap: 10px;">
                            <!-- Show 3 ảnh nhỏ vị trí 1,2,3 -->
                            <!-- Ảnh thứ 2 -->
                            <a class="js-fancybox u-media-viewer pb-1" href="javascript:;" data-src="' . DOMAIN_UPLOADS . '/' . $folder . '/' . $images[1] . '" data-fancybox="fancyboxGallery6" data-caption="Ảnh   #2" data-speed="700">
                                <img class="img-fluid border-radius-3 min-height-150 w-100" src="' . DOMAIN_UPLOADS . '/' . $folder . '/' . $images[1] . '" alt="Image Description">
                                <span class="u-media-viewer__container">
                                    <span class="u-media-viewer__icon">
                                        <span class="fas fa-plus u-media-viewer__icon-inner"></span>
                                    </span>
                                </span>
                            </a>
                            <!-- Ảnh thứ 3 -->
                            <a class="js-fancybox u-media-viewer pb-1" href="javascript:;" data-src="' . DOMAIN_UPLOADS . '/' . $folder . '/' . $images[2] . '" data-fancybox="fancyboxGallery6" data-caption="Ảnh   #3" data-speed="700">
                                <img class="img-fluid border-radius-3 min-height-150" src="' . DOMAIN_UPLOADS . '/' . $folder . '/' . $images[2] . '" alt="Image Description">

                                <span class="u-media-viewer__container">
                                    <span class="u-media-viewer__icon">
                                        <span class="fas fa-plus u-media-viewer__icon-inner"></span>
                                    </span>
                                </span>
                            </a>
                            <!-- Ảnh thứ 4 -->
                            <a class="js-fancybox u-media-viewer u-media-viewer__dark" href="javascript:;" data-src="' . DOMAIN_UPLOADS . '/' . $folder . '/' . $images[3] . '" data-fancybox="fancyboxGallery6" data-caption="Ảnh   #4" data-speed="700">
                                <img class="img-fluid border-radius-3 min-height-150" src="' . DOMAIN_UPLOADS . '/' . $folder . '/' . $images[3] . '" alt="Image Description">

                                <span class="u-media-viewer__container z-index-2 w-100">
                                    <span class="u-media-viewer__icon u-media-viewer__icon--active w-100  bg-transparent">
                                        <span class="u-media-viewer__icon-inner font-size-14">SEE ALL PHOTOS</span>
                                    </span>
                                </span>
                            </a></div>';

    //     Show ảnh còn lại trong mảng 
    //    Bỏ đi 4 phần tử đầu tiên trong mảng ảnh 
    for ($i = 4; $i < count($images); $i++) {
        $html .= '<img class="js-fancybox d-none" alt="Image Description" data-fancybox="fancyboxGallery6" data-src="' . DOMAIN_UPLOADS . '/' . $folder . '/' . $images[$i] . '" data-caption="Ảnh   #' . $i . '" data-speed="700">';
    }

    $html .= '</div>
                </div>
            ';

    return $html;
}

// Show name tab content
function showNameTab($number)
{
    $html = '';
    if ($number == TYPE_ROOM) {
        $html = 'Phòng';
    } elseif ($number == TYPE_EAT) {
        $html = 'Ăn uống';
    } elseif ($number == TYPE_NEAR) {
        $html = 'Khu vực lân cận';
    } elseif ($number == TYPE_SWIM) {
        $html = 'Bơi';
    } elseif ($number == TYPE_HOTEL) {
        $html = 'Khách sạn';
    } elseif ($number == TYPE_AMENITY) {
        $html = 'Tiện ích';
    } else {
        $html = 'Khác';
    }
    return $html;
}

// Tính trung bình 
function calculateAverage($arr)
{
    return round(array_sum($arr) / count($arr), 1);
}

// Hotel detail slug
// Truyền vào kiểu ['hotel', 'slug-adsdsada-sd-sadsadasd-sadasd']
// Return ra kiểu DOMAIN/hotel/slug-adsdsada-sd-sadsadasd-sadasd
function returnDomain($arryUrl = [])
{
    $domain = DOMAIN_WEB;
    foreach ($arryUrl as $url) {
        $domain .= '/' . $url;
    }
    return $domain . '.html';
}

// URL CITY
function returnUrlCity()
{
    $city_name = getValue('city_name', GET_STRING, GET_SESSION);
    $city_id = getValue('city_id', GET_STRING, GET_SESSION);
    $slug =  $city_id  . '-' . $city_name;
    $url = returnDomain([$slug]);
    return $url;
}
