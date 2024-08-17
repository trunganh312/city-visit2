<div id="ontargetModalGallary" class="js-modal-window u-modal-window " data-modal-type="ontarget" style="width: 80vw; overflow-x: hidden;overflow-y: scroll;min-height: 90vh;" data-open-effect="zoomIn" data-close-effect="zoomOut" data-speed="500">
    <div class="bg-white p-3 row" style="min-height: 90vh;">
        <div class="tabs-block tabs-v3">
            <div class="container space-top-1  mb-1">
                <!-- Nav Classic -->
                <ul class="nav tab-nav-line flex-nowrap pb-4 tab-nav justify-content-lg-center text-nowrap" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link font-weight-medium active show" id="pills-all-tab" data-toggle="pill" href="#pills-all" role="tab" aria-controls="pills-all" aria-selected="true">
                            <div class="d-flex flex-column flex-md-row position-relative text-dark align-items-center">
                                <span class="tabtext font-weight-semi-bold">
                                    Tất cả (<?= $groupedImages['total_image'] ?>)
                                </span>
                            </div>
                        </a>
                    </li>
                    <? foreach ($groupedImages as $key => $group) : ?>
                        <? if (is_int($key)) : ?>
                            <li class="nav-item">
                                <a class="nav-link font-weight-medium" id="pills-<?= $key  ?>-tab" data-toggle="pill" href="#pills-<?= $key  ?>" role="tab" aria-controls="pills-<?= $key  ?>" aria-selected="true">
                                    <div class="d-flex flex-column flex-md-row position-relative text-dark align-items-center">
                                        <span class="tabtext font-weight-semi-bold">
                                            <?= showNameTab($key) ?> (<?= $group['total'] ?>)
                                        </span>
                                    </div>
                                </a>
                            </li>
                        <? endif ?>
                    <? endforeach; ?>
                </ul>
                <!-- End Nav Classic -->
                <div class="tab-content">
                    <!-- Show all image -->
                    <div class="tab-pane fade show active" id="pills-all" role="tabpanel" aria-labelledby="pills-all-tab">
                        <div class="row">
                            <?php foreach ($groupedImages as $group) : ?>
                                <?php if (isset($group['images']) && is_array($group['images'])) : ?>
                                    <?php foreach ($group['images'] as $image) : ?>
                                        <?php if (is_array($image)) : ?>
                                            <div class="col-lg-3 col-md-4 col-6">
                                                <a class="js-fancybox u-media-viewer pb-1" href="javascript:;" data-src="<?= DOMAIN_UPLOADS ?>/hotel_images/<?= $image['hti_name'] ?>" data-fancybox="fancyboxGallery" data-speed="700">
                                                    <img class="img-fluid img-thumbnail border-radius-3 min-height-150 w-100" src="<?= DOMAIN_UPLOADS ?>/hotel_images/<?= $image['hti_name'] ?>" alt="Image Description">
                                                    <span class="u-media-viewer__container">
                                                        <span class="u-media-viewer__icon">
                                                            <span class="fas fa-plus u-media-viewer__icon-inner"></span>
                                                        </span>
                                                    </span>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <p></p>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <!-- Show theo tab -->
                    <?php foreach ($groupedImages as $key => $group) : ?>
                        <div class="tab-pane fade" id="pills-<?= $key ?>" role="tabpanel" aria-labelledby="pills-<?= $key ?>-tab">
                            <div class="row">
                                <?php foreach ($group['images'] as $image) : ?>
                                    <div class="col-lg-3 col-md-4 col-6">
                                        <a class="js-fancybox u-media-viewer pb-1" href="javascript:;" data-src="<?= DOMAIN_UPLOADS ?>/hotel_images/<?= $image['hti_name']  ?>" data-fancybox="fancyboxGallery<?= $key ?>" data-speed="700">
                                            <img class="img-fluid border-radius-3 img-thumbnail" src="<?= DOMAIN_UPLOADS ?>/hotel_images/<?= $image['hti_name']  ?>" alt="Image Description">
                                            <span class="u-media-viewer__container">
                                                <span class="u-media-viewer__icon">
                                                    <span class="fas fa-plus u-media-viewer__icon-inner"></span>
                                                </span>
                                            </span>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>