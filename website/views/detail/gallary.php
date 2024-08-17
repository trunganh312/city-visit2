<div class="col-lg-8 col-xl-9">
    <div class="pb-4 mb-2">
        <div class="row mx-n1">
            <!-- Hiển thị 1 cái ảnh đầu tiên của mảng -->
            <div class="col-lg-8 col-xl-9 mb-1 mb-lg-0 px-0 px-lg-1">
                <a href="#ontargetModalGallary" data-modal-target="#ontargetModalGallary" data-modal-effect="fadein" class="js-fancybox u-media-viewer h-100">
                    <img style="width: 100%; height: 100%;" class="img-fluid border-radius-3 min-height-458" src="<?= DOMAIN_UPLOADS ?>/hotel_images/<?= $images[0] ?>" alt="Image Description">
                    <span class="u-media-viewer__container">
                        <span class="u-media-viewer__icon">
                            <span class="fas fa-plus u-media-viewer__icon-inner"></span>
                        </span>
                    </span>
                </a>
            </div>
            <div class="col-lg-4 col-xl-3 px-0 d-lg-block d-sm-none">
                <div class="d-flex" style="justify-content: space-around;flex-direction: column;gap: 10px;">
                    <!-- Show 3 ảnh nhỏ vị trí 1,2,3 -->
                    <!-- Ảnh thứ 2 -->
                    <a href="#ontargetModalGallary" data-modal-target="#ontargetModalGallary" data-modal-effect="fadein" class="js-fancybox u-media-viewer">
                        <img class="img-fluid border-radius-3 min-height-150 w-100" src="<?= DOMAIN_UPLOADS ?>/hotel_images/<?= $images[1] ?>" alt="Image Description">
                        <span class="u-media-viewer__container">
                            <span class="u-media-viewer__icon">
                                <span class="fas fa-plus u-media-viewer__icon-inner"></span>
                            </span>
                        </span>
                    </a>
                    <!-- Ảnh thứ 3 -->
                    <a href="#ontargetModalGallary" data-modal-target="#ontargetModalGallary" data-modal-effect="fadein" class="js-fancybox u-media-viewer">
                        <img class="img-fluid border-radius-3 min-height-150" src="<?= DOMAIN_UPLOADS ?>/hotel_images/<?= $images[2] ?>" alt="Image Description">

                        <span class="u-media-viewer__container">
                            <span class="u-media-viewer__icon">
                                <span class="fas fa-plus u-media-viewer__icon-inner"></span>
                            </span>
                        </span>
                    </a>
                    <!-- Ảnh thứ 4 -->
                    <a href="#ontargetModalGallary" data-modal-target="#ontargetModalGallary" data-modal-effect="fadein" class="js-fancybox u-media-viewer">
                        <img class="img-fluid border-radius-3 min-height-150" src="<?= DOMAIN_UPLOADS ?>/hotel_images/<?= $images[3] ?>" alt="Image Description">
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<? include('gallary_modal.php') ?>