<!-- Responsive Toggle Button -->
<button type="button" class="navbar-toggler btn u-hamburger u-hamburger--primary order-2 ml-3" aria-label="Toggle navigation" aria-expanded="false" aria-controls="navBar" data-toggle="collapse" data-target="#navBar">
    <span id="hamburgerTrigger" class="u-hamburger__box">
        <span class="u-hamburger__inner"></span>
    </span>
</button>
<!-- End Responsive Toggle Button -->
<!-- Navigation -->
<div id="navBar" class="navbar-collapse u-header__navbar-collapse collapse order-2 order-xl-0 pt-4 p-xl-0 position-relative">
    <ul class="navbar-nav u-header__navbar-nav">
        <!-- KHÁCH SẠN THEO HUYỆN -->
        <li class="nav-item hs-has-mega-menu u-header__nav-item" data-event="hover" data-animation-in="slideInUp" data-animation-out="fadeOut" data-max-width="722px" data-position="right">
            <a id="hotelMegaMenu" class="nav-link u-header__nav-link u-header__nav-link-toggle" href="javascript:;" aria-haspopup="true" aria-expanded="false">Khách sạn</a>

            <!-- Tour - Mega Menu -->
            <div class="hs-mega-menu u-header__sub-menu u-header__sub-menu-rounded" aria-labelledby="hotelMegaMenu">
                <div class="row">
                    <? foreach ($groupedHotels as $key => $hotel) : ?>
                        <div class="col-12 col-xl-3dot64">
                            <span class="u-header__sub-menu-title"><?= $key ?></span>
                            <ul class="u-header__sub-menu-nav-group u-header__sub-menu-bordered mb-3">
                                <? foreach ($groupedHotels[$key] as $item) : ?>
                                    <li class="single-line">
                                        <a class="nav-link u-header__sub-menu-nav-link" href="<?= returnDomain(['hotel', $item['hot_slug']])  ?>"><?= $item['hot_name'] ?></a>
                                    </li>
                                <? endforeach; ?>
                            </ul>
                        </div>
                    <? endforeach; ?>
                </div>

            </div>
            <!-- End Tour - Mega Menu -->
        </li>
        <!-- End KHÁCH SẠN THEO HUYỆN -->

        <!-- Điểm đến -->
        <li class="nav-item hs-has-mega-menu u-header__nav-item" data-event="hover" data-animation-in="slideInUp" data-animation-out="fadeOut" data-max-width="722px" data-position="right">
            <a id="districtMegaMenu" class="nav-link u-header__nav-link u-header__nav-link-toggle" href="javascript:;" aria-haspopup="true" aria-expanded="false">Top điểm đến</a>
            <!-- Điểm đến - DistrictMegaMenu -->
            <div class="hs-mega-menu u-header__sub-menu u-header__sub-menu-rounded" aria-labelledby="districtMegaMenu">
                <div class="row">
                    <? foreach ($groupedHotels as $key => $hotel) : ?>
                        <div class="col-12 col-xl-3dot64">
                            <ul class="u-header__sub-menu-nav-group u-header__sub-menu-bordered">
                                <li class="single-line">
                                    <a class="nav-link u-header__sub-menu-nav-link" href="<?= returnUrlCity() ?>?district=<?= $key ?>"><?= $key ?></a>
                                </li>
                            </ul>
                        </div>
                    <? endforeach; ?>
                </div>
                <!-- End Pages - DistrictMegaMenu -->
        </li>
        <!-- End Pages -->

        <!-- Thông tin -->
        <div class="d-flex align-items-center">
            <ul class="list-inline u-header__topbar-nav-divider mb-0">
                <li class="list-inline-item mr-0">
                    <a href="tel:<?= $config['con_hotline']  ?>" class="u-header__navbar-link"><?= $config['con_hotline']  ?></a>
                </li>
                <li class="list-inline-item mr-0">
                    <a href="mailto:info@myhotel.com" class="u-header__navbar-link"><?= $config['con_email_contact']  ?></a>
                </li>
            </ul>
        </div>
        <!-- End thông tin -->
    </ul>
</div>
<!-- End Navigation -->