<div class="col-lg-4 col-xl-3 order-lg-1 width-md-100">
    <div class="navbar-expand-lg navbar-expand-lg-collapse-block">
        <button class="btn d-lg-none mb-5 p-0 collapsed" type="button" data-toggle="collapse" data-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
            <i class="far fa-caret-square-down text-primary font-size-20 card-btn-arrow ml-0"></i>
            <span class="text-primary ml-2">Bộ lọc</span>
        </button>
        <div id="sidebar" class="collapse navbar-collapse">
            <div class="mb-6 w-100">
                <!-- Tìm kiếm theo tên ks hoặc địa điểm -->
                <? include('search.php') ?>

                <!-- Map -->
                <? include('map.php') ?>
                <!-- End Map -->
                <div class="sidenav border border-color-8 rounded-xs">
                    <!-- STAR -->
                    <? include('star.php') ?>
                    <!-- END STAR -->

                    <!-- PRICE -->
                    <? include('price.php') ?>
                    <!-- PRICE -->

                    <!-- LOẠI HÌNH -->
                    <? include('type.php') ?>
                    <!-- END LOẠI HÌNH -->

                    <!-- Quận huyên -->
                    <? include('district.php') ?>
                    <!-- End quận huyện -->


                    <!-- Tiện ích -->
                    <? include('amenity.php') ?>
                    <!-- End tiện ích -->

                    <!-- End Accordion -->
                </div>
            </div>
        </div>
    </div>
</div>