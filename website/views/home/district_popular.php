    <!-- Destinantion v4 -->
    <div class="destination-block destination-v4">
        <div class="container space-bottom-1 pt-3">
            <div class="w-md-80 w-lg-50 text-center mx-md-auto mb-5 mt-3">
                <h2 class="section-title text-black font-size-30 font-weight-bold mb-0">
                    Điểm đến phổ biến
                </h2>
            </div>
            <div class="row">
                <!-- Check xem có mấy điểm đến phổ biến, nếu 2 thì show 2 cục to, nếu là 3 thì show 1 cục to và 2 cục nhỏ, nếu 4 thì show 4 cục to, nếu 5 thì show 3 cục to 2 cục nhỏ, nếu 6 thì show như bình thường -->
                <?
                $count = count($district_popular);
                if ($count == 2) {
                    echo itemLarge($district_popular[0]);
                    echo itemLarge($district_popular[1]);
                }
                if ($count == 3) {
                    echo itemLarge($district_popular[0]);
                    echo itemSmall($district_popular[1]);
                    echo itemSmall($district_popular[2]);
                }

                if ($count == 4) {
                    echo itemSmall($district_popular[0]);
                    echo itemSmall($district_popular[1]);
                    echo itemSmall($district_popular[2]);
                    echo itemSmall($district_popular[3]);
                }

                if ($count == 5) {
                    echo itemLarge($district_popular[0]);
                    echo itemLarge($district_popular[1]);
                    echo itemLarge($district_popular[2]);
                    echo itemSmall($district_popular[3]);
                    echo itemSmall($district_popular[4]);
                }

                if ($count == 6) {
                    echo itemLarge($district_popular[0]);
                    echo itemSmall($district_popular[1]);
                    echo itemSmall($district_popular[2]);
                    echo itemSmall($district_popular[3]);
                    echo itemSmall($district_popular[4]);
                    echo itemLarge($district_popular[5]);
                }

                ?>


            </div>
        </div>
    </div>
    <!-- End Destinantion v4 -->