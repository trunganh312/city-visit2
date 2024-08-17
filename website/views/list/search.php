<div class="pb-4 mb-2">
    <div class="sidebar border border-color-1 rounded-xs">
        <form class="p-4 mb-1" action="" method="GET">
            <!-- Input -->
            <span class="d-block text-gray-1 font-weight-normal mb-0 text-left">Nhập điểm đến hoặc khách sạn</span>
            <div class="mb-4">
                <div class="input-group border-bottom border-width-2 border-color-1">
                    <i class="flaticon-pin-1 d-flex align-items-center mr-2 text-primary font-weight-semi-bold font-size-22"></i>
                    <input name="s" value="<?= isset($_GET['s']) && !empty($_GET['s']) ? $_GET['s'] : (isset($_GET['district']) ? $_GET['district'] : '')  ?>" type="text" class="form-control font-weight-medium font-size-15 shadow-none hero-form border-0 p-0" placeholder="Nhập điểm đến hoặc khách sạn..." aria-label="Keyword or title" aria-describedby="keywordInputAddon" />
                </div>
            </div>
            <!-- End Input -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary height-60 w-100 font-weight-bold mb-xl-0 mb-lg-1 transition-3d-hover">
                    <i class="flaticon-magnifying-glass mr-2 font-size-17"></i>Tìm kiếm
                </button>
            </div>
        </form>
    </div>
</div>