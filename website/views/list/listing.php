<? if ($totals <= 0) : ?>
    <div class="col-lg-8 col-xl-9 order-md-1 order-lg-2 pb-5 pb-lg-0">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="font-size-21 font-weight-bold mb-0 text-lh-1">
                <?= isset($_GET['type']) ? $_GET['type'] : 'Khách sạn' ?>
                <?= isset($_GET['rating']) ? $_GET['rating'] . ' sao' : '' ?>
                tốt nhất tại
                <?= isset($_GET['district']) && !empty($_GET['district']) ? $_GET['district'] : CITY_NAME ?>
            </h3>
            <ul class="nav tab-nav-shop flex-nowrap" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link font-size-22 p-0 active" id="pills-three-example1-tab" data-toggle="pill" href="#pills-three-example1" role="tab" aria-controls="pills-three-example1" aria-selected="true">
                        <div class="d-md-flex justify-content-md-center align-items-md-center">
                            <i class="fa fa-list"></i>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-size-22 p-0 ml-2 " id="pills-one-example1-tab" data-toggle="pill" href="#pills-one-example1" role="tab" aria-controls="pills-one-example1" aria-selected="false">
                        <div class="d-md-flex justify-content-md-center align-items-md-center">
                            <i class="fa fa-th"></i>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
        <div class="mb-3">
            <a href="<?= returnUrlCity() ?>">Xóa bộ lọc</a>
        </div>
        <div class="alert alert-primary" role="alert">
            Không có kết quả nào được tìm thấy, vui lòng thay đổi các tiêu chí tìm kiếm để có kết quả tốt hơn!
        </div>
    </div>
<? endif ?>
<? if ($totals > 0) : ?>
    <div class="col-lg-8 col-xl-9 order-md-1 order-lg-2 pb-5 pb-lg-0">
        <!-- Shop-control-bar Title -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="font-size-21 font-weight-bold mb-0 text-lh-1">
                <?= isset($_GET['type']) ? $_GET['type'] : 'Khách sạn' ?>
                <?= isset($_GET['rating']) ? $_GET['rating'] . ' sao' : '' ?>
                tốt nhất tại
                <?= isset($_GET['district']) && !empty($_GET['district']) ? $_GET['district'] : CITY_NAME ?>
            </h3>
            <ul class="nav tab-nav-shop flex-nowrap" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link font-size-22 p-0 active" id="pills-three-example1-tab" data-toggle="pill" href="#pills-three-example1" role="tab" aria-controls="pills-three-example1" aria-selected="true">
                        <div class="d-md-flex justify-content-md-center align-items-md-center">
                            <i class="fa fa-list"></i>
                        </div>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-size-22 p-0 ml-2 " id="pills-one-example1-tab" data-toggle="pill" href="#pills-one-example1" role="tab" aria-controls="pills-one-example1" aria-selected="false">
                        <div class="d-md-flex justify-content-md-center align-items-md-center">
                            <i class="fa fa-th"></i>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
        <!-- End shop-control-bar Title -->

        <!-- Check xem nếu có filter thì show bộ lọc -->
        <? if (!empty($filter)) : ?>
            <!-- Xóa bộ lọc -->
            <div class="mb-3">
                <a href="<?= returnUrlCity() ?>">Xóa bộ lọc</a>
            </div>
        <? endif ?>



        <!-- Slick Tab carousel -->
        <div class="u-slick__tab">
            <!-- SORT -->
            <?
            include_once('sort.php')
            ?>
            <!-- End SORT -->

            <!-- Tab Content -->
            <div class="tab-content" id="pills-tabContent">
                <!-- Show content là 1 cột -->
                <?
                include_once('tab_content_one.php')
                ?>
                <!-- Show content là 3 cột. Mặc định show 3 cột -->
                <?
                include_once('tab_content_three.php')
                ?>

            </div>
            <!-- End Tab Content -->
        </div>
        <!-- Slick Tab carousel -->
    </div>
<? endif ?>