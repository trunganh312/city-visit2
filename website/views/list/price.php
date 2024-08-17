<div id="shopCartAccordion" class="accordion rounded shadow-none">
    <div class="border-0">
        <div class="card-collapse" id="shopCardHeadingOne">
            <h3 class="mb-0">
                <button type="button" class="btn btn-link btn-block card-btn py-2 px-5 text-lh-3 collapsed" data-toggle="collapse" data-target="#shopCardOne" aria-expanded="false" aria-controls="shopCardOne">
                    <span class="row align-items-center">
                        <span class="col-9">
                            <span class="d-block font-size-lg-15 font-size-17 font-weight-bold text-dark">Giá</span>
                        </span>
                        <span class="col-3 text-right">
                            <span class="card-btn-arrow">
                                <span class="fas fa-chevron-down small"></span>
                            </span>
                        </span>
                    </span>
                </button>
            </h3>
        </div>
        <div id="shopCardOne" class="collapse show" aria-labelledby="shopCardHeadingOne" data-parent="#shopCartAccordion">
            <form class="card-body pt-0 px-5" action="" method="GET">
                <div class="pb-3 mb-1 d-flex text-lh-1">
                    <span id="rangeSliderExample3MinResult" class=""></span>
                    <span>₫</span>
                    <span class="mx-0dot5"> — </span>
                    <span id="rangeSliderExample3MaxResult" class=""></span>
                    <span>đ</span>
                </div>
                <? isset($_GET['price_range']) ? list($from, $to) = explode(';', $_GET['price_range']) : list($from, $to) = [0, 20000000] ?>
                <input class="js-range-slider price_range" type="text" name="price_range" data-extra-classes="u-range-slider height-35" data-type="double" data-grid="false" data-hide-from-to="true" data-min="0" data-max="20000000" data-from="<?= $from; ?>" data-to="<?= $to; ?>" data-prefix="$" data-result-min="#rangeSliderExample3MinResult" data-result-max="#rangeSliderExample3MaxResult" />
                <input type="text" name="district" hidden value="<?= isset($_GET['district']) ? $_GET['district'] : ''  ?>" <?= isset($_GET['district']) ? '' : 'disabled'  ?>>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </form>
        </div>
    </div>
</div>