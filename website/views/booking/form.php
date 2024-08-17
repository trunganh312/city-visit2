<form class="js-validate" action="" method="post" id="bookingForm">
    <div class="row">
        <!-- Input -->
        <div class="col-sm-6 mb-4">
            <div class="js-form-message">
                <label class="form-label">
                    Họ và tên
                </label>

                <input type="text" class="form-control" name="fullName" placeholder="Nhập họ và tên..." aria-label="Ali" required data-msg="Vui lòng nhập họ và tên." data-error-class="u-has-error" data-success-class="u-has-success">
            </div>
        </div>
        <!-- End Input -->

        <!-- Input -->
        <div class="col-sm-6 mb-4">
            <div class="js-form-message">
                <label class="form-label">
                    Email
                </label>

                <input type="email" class="form-control" name="email" placeholder="name@gmail.com" aria-label="name@gmail.com" required data-msg="Please enter a valid email address." data-error-class="u-has-error" data-success-class="u-has-success">
            </div>
        </div>
        <!-- End Input -->

        <!-- Input -->
        <div class="col-sm-6 mb-4">
            <div class="js-form-message">
                <label class="form-label">
                    Số điện thoại
                </label>

                <input type="number" class="form-control" name="phone" placeholder="Nhập số điện thoại" aria-label="+90 (254) 458 96 32" required data-msg="Vui lòng nhập số điện thoại." data-error-class="u-has-error" data-success-class="u-has-success">
            </div>
        </div>
        <!-- End Input -->

        <!-- Input -->
        <div class="col-sm-6 mb-4">
            <div class="js-form-message">
                <label class="form-label">
                    Địa chỉ
                </label>

                <input type="text" class="form-control" name="address" placeholder="Nhập địa chỉ" required data-msg="Vui lòng nhập địa chỉ." data-error-class="u-has-error" data-success-class="u-has-success">
            </div>
        </div>
        <!-- End Input -->

        <div class="w-100"></div>

        <div class="col">
            <!-- Input -->
            <div class="js-form-message mb-6">
                <label class="form-label">
                    Yêu cầu riêng
                </label>

                <div class="input-group">
                    <textarea class="form-control" rows="4" name="message" placeholder="VD: Đặt thêm vé máy bay, vé tàu" aria-label="" data-error-class="u-has-error" data-success-class="u-has-success"></textarea>
                </div>
            </div>
            <!-- End Input -->
        </div>

        <input type="text" hidden readonly name="hotel_id" value="<?= $room['hot_id'] ?>">
        <input type="text" hidden readonly name="room_id" value="<?= $room['roo_id'] ?>">

        <div class="w-100 d-flex" style="justify-content: flex-end;"> <button type="submit" class="btn btn-primary">Đặt ngay</button></div>
    </div>
</form>