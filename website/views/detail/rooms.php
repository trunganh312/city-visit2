<div class="border-bottom py-4">
    <h5 id="scroll-amenities" class="font-size-21 font-weight-bold text-dark mb-4">
        Hạng phòng tại <?= $hotel['hot_name']  ?>
    </h5>
    <? foreach ($rooms as $room) : ?>
        <div class="card border-color-7 mb-5 overflow-hidden">
            <div class="position-absolute top-0 right-0 mr-md-1 mt-md-1">
                <div class="border border-brown bg-brown rounded-xs d-flex align-items-center text-lh-1 py-1 px-3 mr-2 mt-2">
                    <span class="font-weight-normal text-white font-size-14"><?= isset($room) && $room['roo_promotion'] == 1 ? 'Khuyến mại' : ''  ?></span>
                </div>
            </div>
            <div class="product-item__outer w-100">
                <div class="row">
                    <div class="col-md-5 col-lg-5 col-xl-3dot5">
                        <div class="pt-5 pb-md-5 pl-4 pr-4 pl-md-5 pr-md-2 pr-xl-2">
                            <div class="product-item__header mt-2 mt-md-0">
                                <div class="position-relative">
                                    <img class="img-fluid rounded-sm" src="<?= DOMAIN_UPLOADS ?>/room_cover/<?= $room['roo_cover'] ?>" alt="<?= $room['roo_name'] ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col col-md-7 col-lg-7 col-xl-5 flex-horizontal-center pl-xl-0">
                        <div class="w-100 position-relative m-4 m-md-0">
                            <a href="../hotels/hotel-booking.html" class="mb-2 d-inline-block">
                                <span class="font-weight-bold font-size-17 text-dark text-dark"><?= $room['roo_name'] ?></span>
                            </a>
                            <div class="mt-1 pt-2">
                                <div class="d-flex mb-1">
                                    <div class="ml-0">
                                        <ul class="list-unstyled mb-0">
                                            <li class="media mb-3 text-gray-1">
                                                <small class="mr-2">
                                                    <i class="fi fi-rs-telescope font-size-17 text-primary"></i>
                                                </small>
                                                <div class="media-body font-size-1 ml-1">
                                                    <?= $room['roo_view'] ?>
                                                </div>
                                            </li>
                                            <li class="media mb-3 text-gray-1">
                                                <small class="mr-2">
                                                    <small class="flaticon-plans font-size-17 text-primary"></small>
                                                </small>
                                                <div class="media-body font-size-1 ml-1">
                                                    <?= $room['roo_size'] ?> m²
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="ml-7">
                                        <ul class="list-unstyled mb-0">
                                            <li class="media mb-3 text-gray-1">
                                                <small class="mr-2">
                                                    <small class="flaticon-bed-1 font-size-17 text-primary"></small>
                                                </small>
                                                <div class="media-body font-size-1 ml-1">
                                                    <?= $room['roo_size_person'] ?>
                                                </div>
                                            </li>
                                            <li class="media mb-3 text-gray-1">
                                                <small class="mr-2">
                                                    <i class="fi fi-rs-bed font-size-17 text-primary"></i>
                                                </small>
                                                <div class="media-body font-size-1 ml-1">
                                                    <?= $room['roo_bed'] ?>
                                                </div>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                                <a href="#ontargetModalRoom<?= $room['roo_id'] ?>" data-modal-target="#ontargetModalRoom<?= $room['roo_id'] ?>" data-modal-effect="fadein">
                                    <span class="text-primary font-size-14">Xem hình ảnh và tiện nghi</span>
                                </a>

                            </div>
                        </div>
                    </div>
                    <div class="col col-xl-3dot5 align-self-center py-4 py-xl-0 border-top border-xl-top-0">
                        <div class="flex-content-center border-xl-left py-xl-5 ml-4 ml-xl-0 justify-content-start justify-content-xl-center">
                            <div class="text-center my-xl-1">
                                <div class="mb-2 pb-1">
                                    <span class="font-weight-bold font-size-22 ml-1"><?= formatVND($room['roo_price']) ?>₫/ đêm</span>
                                </div>
                                <a href="<?= DOMAIN_WEB_VIEW ?>booking/index.php?room_id=<?= $room['roo_id']  ?>" class="btn btn-outline-primary border-radius-3 border-width-2 px-4 font-weight-bold min-width-200 py-2 text-lh-lg">Đặt ngay</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <? endforeach; ?>
    <? include('room_modal.php') ?>
    <h5 id="scroll-amenities" class="font-size-21 font-weight-bold text-dark mb-4">
        Tiện ích
    </h5>
    <ul class="list-group list-group-borderless list-group-horizontal list-group-flush no-gutters row">
        <? foreach ($hotel['amenities'] as $amenity) : ?>
            <li class="col-md-4 list-group-item d-flex mb-2 " style="align-items: center;"><i class="fi <?= $amenity['ame_icon'] ?> mr-3 text-primary font-size-24"></i><?= $amenity['ame_name'] ?></li>
        <? endforeach; ?>
    </ul>
</div>