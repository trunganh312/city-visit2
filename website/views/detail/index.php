<?
include('config_module.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?=
    $Layout->loadHead($hotel['hot_name']);
    ?>
</head>

<body>
    <?=
    $Layout->loadHeaderList();
    ?>
    <!-- Content -->
    <main id="content">
        <?
        $arrBreadcrumbs = array(
            'Trang chủ' => '/',
            $hotel['dis_name'] => '' . returnUrlCity() . '?district=' . $hotel['dis_name'],
            $hotel['hot_name'] => ''
        );
        echo showBreadcrumbs($arrBreadcrumbs);
        ?>
        <div class="container mt-4">
            <div class="row">
                <div class="col-lg-8 col-xl-9">
                    <div class="d-block d-md-flex flex-center-between align-items-start mb-2">
                        <div class="mb-3">
                            <ul class="list-unstyled mb-2 d-md-flex flex-lg-wrap flex-xl-nowrap mb-2">
                                <li class="border border-green bg-green rounded-xs d-flex align-items-center text-lh-1 py-1 px-3 mr-md-2 mb-2 mb-md-0 mb-lg-2 mb-xl-0">
                                    <span class="font-weight-normal text-white font-size-14"><?= $hotel['hot_type'] ?></span>
                                </li>
                                <? if ($hotel['hot_promotion'] == 1) : ?>
                                    <li class="border border-brown bg-brown rounded-xs d-flex align-items-center text-lh-1 py-1 px-3 mr-md-2 mb-2 mb-md-0 mb-lg-2 mb-xl-0">
                                        <span class="font-weight-normal text-white font-size-14">Khuyến mại</span>
                                    </li>
                                <? endif; ?>
                                <? if ($hotel['hot_hot'] == 1) : ?>
                                    <li class="border border-maroon bg-maroon rounded-xs d-flex align-items-center text-lh-1 py-1 px-3 mr-md-2 mb-2 mb-md-0 mb-lg-2 mb-xl-0 mb-md-0">
                                        <span class="font-weight-normal font-size-14 text-white">Khách sạn nổi bật</span>
                                    </li>
                                <? endif; ?>
                            </ul>
                            <div class="d-block d-md-flex flex-horizontal-center mb-2 mb-md-0">
                                <h4 class="font-size-23 font-weight-bold mb-1"><?= $hotel['hot_name'] ?></h4>
                                <div class="ml-3 font-size-10 letter-spacing-2">
                                    <span class="d-block green-lighter ml-1">
                                        <?= star($hotel['hot_star']) ?>
                                    </span>
                                </div>
                            </div>
                            <div class="d-block d-md-flex flex-horizontal-center font-size-14 text-gray-1">
                                <i class="icon flaticon-placeholder mr-2 font-size-20"></i>
                                <?= $hotel['hot_address_map'] ?>
                                <a href="#ontargetModal" data-modal-target="#ontargetModal" data-modal-effect="fadein">
                                    <span class="text-primary font-size-14"> - Xem bản đồ</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <? include('gallary.php') ?>
                <div class="col-lg-4 col-xl-3">
                    <div class="border border-color-7 rounded px-4 pt-4 pb-3 mb-5">
                        <div class="px-2 pt-2">
                            <a class="d-inline-block border rounded mb-4 overflow-hidden">
                                <img class="img-fluid" src="<?= DOMAIN_UPLOADS ?>/hotel_cover/<?= $hotel['hot_page_cover'] ?>" alt="Image-Description">
                            </a>
                            <div class="d-flex align-items-center mb-2">
                                <i class="flaticon-placeholder-1 font-size-25 text-primary mr-3 pr-1"></i>
                                <h6 class="mb-0 font-size-14 text-gray-1">
                                    <a href="#">Khách sạn tốt nhất tại </a>
                                    <a href="<?= returnUrlCity() ?>?district=<?= $hotel['dis_name'] ?>" class="text-primary"><?= $hotel['dis_name'] ?></a>
                                </h6>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="flaticon-medal font-size-25 text-primary mr-3 pr-1"></i>
                                <h6 class="mb-0 font-size-14 ">
                                    Khách sạn có nhiều tiện nghi
                                </h6>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="flaticon-home font-size-25 text-primary mr-3 pr-1"></i>
                                <h6 class="mb-0 font-size-14 ">
                                    Gần các địa điểm du lịch
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Mô tả -->
            <? include('description.php') ?>

            <!-- Hạng phòng -->
            <? include('rooms.php') ?>

            <!-- Đánh giá -->
            <? include('review.php') ?>

            <!-- Khách sạn lân cận -->
            <? include('near.php') ?>
        </div>
    </main>

    <!-- Map -->
    <? include('map.php') ?>
    <?=
    $Layout->loadfooter();
    ?>

</body>

</html>