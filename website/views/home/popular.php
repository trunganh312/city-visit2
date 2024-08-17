<?
require_once('config_module.php');
?>
<!-- Product Cards Ratings With carousel -->
<div class="product-card-block product-card-v3">
    <div class="container-fluid space-top-2 space-top-lg-3">
        <div class="w-md-80 w-lg-50 text-center mx-md-auto pb-4 mt-xl-4">
            <h2 class="section-title text-black font-size-30 font-weight-bold mb-0">
                KHÁCH SẠN PHỔ BIẾN NHẤT
            </h2>
        </div>
        <div class="js-slick-carousel u-slick u-slick--equal-height u-slick--gutters-3" data-slides-show="4" data-slides-scroll="1" data-arrows-classes="d-none d-lg-inline-block u-slick__arrow-classic v1 u-slick__arrow-classic--v1 u-slick__arrow-centered--y rounded-circle" data-arrow-left-classes="fas fa-chevron-left u-slick__arrow-classic-inner u-slick__arrow-classic-inner--left shadow-5" data-arrow-right-classes="fas fa-chevron-right u-slick__arrow-classic-inner u-slick__arrow-classic-inner--right shadow-5" data-pagi-classes="text-center u-slick__pagination mt-4" data-responsive='[{
                        "breakpoint": 1025,
                        "settings": {
                        "slidesToShow": 3
                        }
                        }, {
                        "breakpoint": 992,
                        "settings": {
                        "slidesToShow": 3
                        }
                        }, {
                        "breakpoint": 768,
                        "settings": {
                        "slidesToShow": 2
                        }
                        }, {
                        "breakpoint": 554,
                        "settings": {
                        "slidesToShow": 1
                        }
                        }]'>
            <?php foreach ($hotel_popular as $hotel) : ?>
                <div class="js-slide mt-2">
                    <?= itemHotel($hotel) ?>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</div>
</div>
<!-- End Product Cards Ratings With carousel -->