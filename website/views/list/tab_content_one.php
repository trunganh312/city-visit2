<div class="tab-pane fade show active" id="pills-three-example1" role="tabpanel" aria-labelledby="pills-three-example1-tab" data-target-group="groups">
    <ul class="d-block list-unstyled products-group prodcut-list-view">
        <? foreach ($hotels as $hotel) : ?>
            <li class="card mb-5 overflow-hidden">
                <div class="product-item__outer w-100">
                    <div class="row">
                        <div class="col-md-5 col-xl-4">
                            <div class="product-item__header">
                                <div class="position-relative">
                                    <div class="js-slick-carousel u-slick u-slick--equal-height " data-slides-show="1" data-slides-scroll="1" data-arrows-classes="d-none d-lg-inline-block u-slick__arrow-classic v4 u-slick__arrow-classic--v4 u-slick__arrow-centered--y rounded-circle" data-arrow-left-classes="flaticon-back u-slick__arrow-classic-inner u-slick__arrow-classic-inner--left" data-arrow-right-classes="flaticon-next u-slick__arrow-classic-inner u-slick__arrow-classic-inner--right" data-pagi-classes="js-pagination text-center u-slick__pagination u-slick__pagination--white position-absolute right-0 bottom-0 left-0 mb-3 mb-0">
                                        <?
                                        $images = json_decode($hotel['images'], true);
                                        $max_images = 10; // Giới hạn số lượng ảnh tối đa
                                        $count = 0; // Khởi tạo bộ đếm 
                                        ?>
                                        <? foreach ($images as $index => $image) :
                                            if ($count >= $max_images) {
                                                break; // Dừng vòng lặp khi đã đạt đến giới hạn
                                            }
                                            $count++;
                                        ?>
                                            <div class="js-slide">
                                                <a href="<?= returnDomain(['hotel', $hotel['hot_slug']]) ?>" class="d-block gradient-overlay-half-bg-gradient-v5"><img class="img-fluid min-height-230 card-img-top" src="<?= DOMAIN_UPLOADS ?>/hotel_images/<?= $image ?>"></a>
                                            </div>
                                        <? endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7 col-xl-5 col-wd-4gdot5 flex-horizontal-center">
                            <div class="w-100 position-relative m-4 m-md-0">
                                <div class="my-1 pb-1">
                                    <?= $hotel['hot_hot'] == 1 ? '<span class="badge badge-orange text-white rounded-xs font-size-13 py-1 p-xl-2">Hot</span>' : '' ?>
                                    <span class="green-lighter ml-2">
                                        <?= star($hotel['hot_star']) ?>
                                    </span>
                                </div>
                                <a href="<?= returnDomain(['hotel', $hotel['hot_slug']]) ?>">
                                    <span class="font-weight-medium font-size-17 text-dark d-flex mb-1"><?= $hotel['hot_name'] ?></span>
                                </a>
                                <div class="card-body p-0">
                                    <div class="text-gray-1 mb-2">
                                        <i class="fi fi-rs-map-marker font-size-20 mr-2"></i>
                                        <a href="#ontargetModal" data-modal-target="#ontargetModal" data-modal-effect="fadein">
                                            <span class="text-primary font-size-14">Xem bản đồ</span>
                                        </a>
                                    </div>

                                    <div class="text-gray-1 mb-2">
                                        <i class="fi fi-rs-marker mr-2 font-size-20"></i>
                                        <span class="font-size-14"> <?= $hotel['hot_address_map'] ?>
                                    </div>
                                    <div class="text-gray-1">
                                        <i class="fi fi-rs-hotel mr-2 font-size-20"></i>
                                        <span class="font-size-14"><?= $hotel['hot_type'] ?> - Gần trung tâm</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col col-xl-3 col-wd-3gdot5 align-self-center py-4 py-xl-0 border-top border-xl-top-0">
                            <div class="d-xl-flex flex-wrap border-xl-left mx-sm-4 ml-xl-0 pr-xl-3 pr-wd-5 text-xl-right justify-content-xl-end" style="padding: 0 !important;">
                                <div class="mb-xl-2 mb-wd-2">
                                    <div class="mb-0">
                                        <div class="my-xl-1">
                                            <div class="d-flex align-items-center justify-content-xl-end mb-2">
                                                <span class="badge badge-primary rounded-xs font-size-14 p-2 mr-2 mb-0"><?= $hotel['average_rating'] ?> /5 </span>
                                                <span class="font-size-17 font-weight-bold text-primary">Đánh giá tốt</span>
                                            </div>
                                        </div>
                                        <!-- <span class="font-size-14 text-gray-1">(1,186 Reviews)</span> -->
                                    </div>
                                </div>
                                <div class="mb-0">
                                    <span class="mr-1 font-size-14 text-gray-1">Chỉ từ</span>
                                    <span class="font-weight-bold"><?= formatVND($hotel['hot_price']) ?>₫</span>
                                    <span class="font-size-14 text-gray-1"> / đêm</span>
                                </div>
                                <a href="<?= returnDomain(['hotel', $hotel['hot_slug']]) ?>" class="btn btn-primary p-2 w-100 mt-2 ml-wd-2 ml-xl-2">Xem thêm</a>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        <? endforeach; ?>

    </ul>
    <? include('pagination.php'); ?>
</div>