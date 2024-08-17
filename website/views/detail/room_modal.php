<?
foreach ($rooms as $room) : ?>
    <?
    // Lấy ra ảnh từng phòng
    $roomDetail = $DB->query("SELECT r.roo_id, JSON_ARRAYAGG(img.rimg_image) AS images FROM room r LEFT JOIN room_image img ON r.roo_id = img.rimg_room_id
    WHERE r.roo_id =" . $room['roo_id'])->getOne();
    /** MẢNG ẢNH CỦA PHÒNG */
    $images = json_decode($roomDetail['images'], true);

    // Lấy ra tất cả tiện ích của phòng
    $amenities = $DB->query("SELECT a.* FROM room_amenities ra LEFT JOIN amenity a ON ra.ram_amenity_id = a.ame_id WHERE ra.ram_room_id = " . $room['roo_id'])->toArray();

    ?>
    <div id="ontargetModalRoom<?= $room['roo_id'] ?>" class="js-modal-window u-modal-window " data-modal-type="ontarget" style="width: 80vw; max-height: 90vh;overflow: hidden scroll;" data-open-effect="zoomIn" data-close-effect="zoomOut" data-speed="500">
        <div class="bg-white p-3 row">
            <div class="col-lg-7 col-xl-7">
                <div class="pb-4 mb-2">
                    <?= showGallary($images, 'room_images') ?>
                </div>
            </div>
            <div class="col-lg-5 col-xl-5">
                <h4>Phòng: <?= $room['roo_name'] ?></h4>
                <span class="font-size-17"><?= formatVND($room['roo_price']) ?> VNĐ</span>
                <a class="btn btn-primary w-100 my-2 p-2 " href="<?= DOMAIN_WEB_VIEW ?>booking/index.php?room_id=<?= $room['roo_id'] ?>">Đặt ngay</a>
                <span>Mô tả: </span>
                <div class="content my-2 text-gray-1">
                    <?= html_entity_decode($room['roo_description']) ?>
                </div>
                <div class="amenity">
                    <div class="d-flex mb-1">
                        <div class="ml-2">
                            <ul class="list-unstyled mb-0">
                                <li class="media mb-1 text-gray-1">
                                    <small class="mr-2">
                                        <i class="fi fi-rs-telescope font-size-17 text-primary"></i>
                                    </small>
                                    <div class="media-body font-size-1 ml-1">
                                        <?= $room['roo_view'] ?>
                                    </div>
                                </li>
                                <li class="media mb-1 text-gray-1">
                                    <small class="mr-2">
                                        <small class="flaticon-plans font-size-17 text-primary"></small>
                                    </small>
                                    <div class="media-body font-size-1 ml-1">
                                        <?= $room['roo_size'] ?> m²
                                    </div>
                                </li>
                                <li class="media mb-1 text-gray-1">
                                    <small class="mr-2">
                                        <small class="flaticon-bed-1 font-size-17 text-primary"></small>
                                    </small>
                                    <div class="media-body font-size-1 ml-1">
                                        <?= $room['roo_size_person'] ?>
                                    </div>
                                </li>
                                <li class="media mb-1 text-gray-1">
                                    <small class="mr-2">
                                        <i class="fi fi-rs-bed font-size-17 text-primary"></i>
                                    </small>
                                    <div class="media-body font-size-1 ml-1">
                                        <?= $room['roo_bed'] ?>
                                    </div>
                                </li>
                                <? if ($room['roo_breakfast']) : ?>
                                    <li class="media mb-1 text-gray-1">
                                        <small class="mr-2">
                                            <i class="fi fi-rs-fork font-size-17 text-primary"></i>
                                        </small>
                                        <div class="media-body font-size-1 ml-1">
                                            Miễn phí ăn sáng
                                        </div>
                                    </li>
                                <? endif; ?>
                                <li>Tiện nghi phòng: </li>
                                <? foreach ($amenities as $amenity) : ?>
                                    <li class="media mt-1 text-gray-1">
                                        <small class="mr-2">
                                            <i class="fi <?= $amenity['ame_icon'] ?> font-size-17 text-primary"></i>
                                        </small>
                                        <div class="media-body font-size-1 ml-1">
                                            <?= $amenity['ame_name'] ?>
                                        </div>
                                    </li>
                                <? endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<? endforeach; ?>