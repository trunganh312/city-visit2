<!-- Tabs v3 -->
<div class="tabs-block tabs-v3">
    <div class="container space-top-1 pb-3 mb-1">
        <div class="w-md-80 w-lg-50 text-center mx-md-auto my-3">
            <h2 class="section-title text-black font-size-30 font-weight-bold mb-0">
                Khách sạn nên thử
            </h2>
        </div>

        <!-- Nav Classic -->
        <ul class="nav tab-nav-line flex-nowrap pb-4 tab-nav justify-content-lg-center text-nowrap" role="tablist">
            <!-- Tab các hạng sao -->
            <?php foreach ($star_ratings as $index => $star) : ?>
                <li class="nav-item">
                    <a class="nav-link font-weight-medium <?= $index === 0 ? 'active' : '' ?>" id="pills-star-<?= $star['hot_star'] ?>-tab" data-toggle="pill" href="#pills-star-<?= $star['hot_star'] ?>" role="tab" aria-controls="pills-star-<?= $star['hot_star'] ?>" aria-selected="<?= $index === 0 ? 'true' : 'false' ?>">
                        <div class="d-flex flex-column flex-md-row position-relative text-dark align-items-center">
                            <span class="tabtext font-weight-semi-bold"><?= $star['hot_star'] ?> sao</span>
                        </div>
                    </a>
                </li>
            <?php endforeach; ?>

            <!-- Tab các tiện ích -->
            <?php foreach ($amenities as $index => $amenity) : ?>
                <li class="nav-item">
                    <a class="nav-link font-weight-medium" id="pills-amenity-<?= $amenity['ame_id'] ?>-tab" data-toggle="pill" href="#pills-amenity-<?= $amenity['ame_id'] ?>" role="tab" aria-controls="pills-amenity-<?= $amenity['ame_id'] ?>" aria-selected="false">
                        <div class="d-flex flex-column flex-md-row position-relative text-dark align-items-center">
                            <span class="tabtext font-weight-semi-bold"><?= $amenity['ame_name'] ?></span>
                        </div>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <!-- End Nav Classic -->

        <div class="tab-content">
            <!-- Tab content theo hạng sao -->
            <?php foreach ($star_ratings as $index => $star) : ?>
                <div class="tab-pane fade <?= $index === 0 ? 'active show' : '' ?>" id="pills-star-<?= $star['hot_star'] ?>" role="tabpanel" aria-labelledby="pills-star-<?= $star['hot_star'] ?>-tab">
                    <div class="row">
                        <?php
                        // Lấy danh sách các khách sạn thuộc hạng sao này từ database
                        $hotels = $HotelController->getHotelsByStar($star['hot_star']);
                        ?>
                        <?php foreach ($hotels as $hotel) : ?>
                            <div class="col-md-6 col-lg-4 col-xl-3 mb-3 mb-md-4 pb-1">
                                <?= itemHotel($hotel) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Tab content theo tiện ích -->
            <?php foreach ($amenities as $index => $amenity) : ?>
                <div class="tab-pane fade" id="pills-amenity-<?= $amenity['ame_id'] ?>" role="tabpanel" aria-labelledby="pills-amenity-<?= $amenity['ame_id'] ?>-tab">
                    <div class="row">
                        <?php
                        // Lấy danh sách các khách sạn có tiện ích này từ database
                        $hotels = $HotelController->getHotelsByAmenity($amenity['ame_id']);
                        ?>
                        <?php foreach ($hotels as $hotel) : ?>
                            <div class="col-md-6 col-lg-4 col-xl-3 mb-3 mb-md-4 pb-1">
                                <?= itemHotel($hotel) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<!-- End Tabs v3 -->