<script src="http://code.jquery.com/jquery-1.11.3.min.js" charset="utf-8"></script>

<? if ($hotel['total_reviews'] > 0) : ?>
    <div class="border-bottom py-4">
        <h5 id="scroll-reviews" class="font-size-21 font-weight-bold text-dark mb-4">
            Đánh giá
        </h5>
        <div class="row">
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="border rounded flex-content-center py-5 border-width-2">
                    <div class="text-center">
                        <h2 class="font-size-50 font-weight-bold text-primary mb-0 text-lh-sm">
                            <?= $hotel['average_rating'] ?>
                        </h2>
                        <div class="text-dark mb-3">Dựa trên <?= $hotel['total_reviews'] ?> đánh giá</div>
                        <div class="text-gray-1">Cam kết 100% các đánh giá đều được đánh giá bởi các khách hàng!</div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <?= review($hotel['averages']) ?>
                </div>
            </div>
        </div>
    </div>
<? endif; ?>
<? if ($hotel['total_reviews'] <= 0) : ?>
    <div class="p-5 d-flex justify-content-center border">Không có đánh giá nào</div>
<? endif; ?>
<div class="py-4">
    <div class="media flex-column flex-md-row  align-items-start">
        <div class="media-body  text-left">
            <? foreach ($hotel['reviews'] as $review): ?>
                <div class="mb-4">
                    <p class="font-weight-bold text-gray-3 mb-0">
                        <a><?= $review['rev_name'] != null  ? $review['rev_name'] : 'Người dùng ẩn danh' ?></a> -
                        <span class="font-weight-normal text-gray-1">9/8/2024</span>
                    </p>
                    <p class="text-lh-1dot6 mb-0 pr-lg-5" style="text-align: left;"><?= $review['rev_comment'] ?></p>
                </div>
                <hr>
            <? endforeach; ?>
        </div>
    </div>
</div>
<div class="py-4">
    <h5 class="font-size-21 font-weight-bold text-dark mb-6">
        Viết đánh giá
    </h5>
    <form id="reviewForm" method="post">
        <div class="row">
            <? foreach ($review_data as $index => $review) : ?>
                <div class="col-md-4 mb-2">
                    <h6 class="font-weight-bold text-dark mb-1">
                        <?= $review ?>
                    </h6>
                    <div class="<?= $index ?>" data-rate-value=5></div>
                    <input id="<?= $index ?>" type="text" readonly hidden name="<?= $index ?>">
                </div>
            <? endforeach; ?>
        </div>

        <div class="row mb-5 mb-lg-0">
            <!-- Input -->
            <div class="col-sm-6 mb-5">
                <div class="js-form-message">
                    <input type="text" class="form-control" name="name" placeholder="Nhập tên hoặc có thể ẩn danh">
                </div>
            </div>
            <!-- End Input -->

            <!-- Input -->
            <div class="col-sm-6 mb-5">
                <div class="js-form-message">
                    <input type="email" class="form-control" name="email" placeholder="Nhập email">
                </div>
            </div>
            <!-- End Input -->
            <div class="col-sm-12 mb-5">
                <div class="js-form-message">
                    <div class="input-group">
                        <textarea required class="form-control" rows="6" cols="77" name="comment" placeholder="Để lại bình luận của bạn 😘😘😘"></textarea>
                    </div>
                </div>
            </div>
            <input type="hidden" name="hotel_id" readonly value="<?= $hotel['hot_id'] ?>">
            <!-- End Input -->
            <div class="col d-flex justify-content-center justify-content-lg-start">
                <button type="submit" class="btn rounded-xs bg-blue-dark-1 text-white p-2 height-51 width-190 transition-3d-hover">Gửi đánh giá</button>
            </div>
        </div>
    </form>
</div>