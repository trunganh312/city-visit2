<div class="product-card-block product-card-v3">
    <div class="space-1">
        <div class="w-md-80 w-lg-50 text-center mx-md-auto pb-4">
            <h2 class="section-title text-black font-size-30 font-weight-bold mb-0">Khách sạn lân cận</h2>
        </div>
        <div class="js-slick-carousel u-slick u-slick--equal-height u-slick--gutters-3" data-slides-show="4" data-slides-scroll="1" data-arrows-classes="d-none d-lg-inline-block u-slick__arrow-classic v1 u-slick__arrow-classic--v1 u-slick__arrow-centered--y rounded-circle" data-arrow-left-classes="fas fa-chevron-left u-slick__arrow-classic-inner u-slick__arrow-classic-inner--left" data-arrow-right-classes="fas fa-chevron-right u-slick__arrow-classic-inner u-slick__arrow-classic-inner--right" data-pagi-classes="d-lg-none text-center u-slick__pagination mt-4" data-responsive='[{
                                "breakpoint": 1025,
                                "settings": {
                                    "slidesToShow": 3
                                }
                            }, {
                                "breakpoint": 992,
                                "settings": {
                                    "slidesToShow": 2
                                }
                            }, {
                                "breakpoint": 768,
                                "settings": {
                                    "slidesToShow": 1
                                }
                            }, {
                                "breakpoint": 554,
                                "settings": {
                                    "slidesToShow": 1
                                }
                            }]'>
            <? foreach ($nearbyHotels as $hotel) : ?>
                <div class="js-slide py-3">
                    <?= itemHotel($hotel) ?>
                </div>
            <? endforeach; ?>


        </div>
    </div>
</div>