<div id="amenityAccordion" class="accordion rounded-0 shadow-none border-top">
    <div class="border-0">
        <div class="card-collapse" id="amenityHeadingOne">
            <h3 class="mb-0">
                <button type="button" class="btn btn-link btn-block card-btn py-2 px-5 text-lh-3 collapsed" data-toggle="collapse" data-target="#amenityOne" aria-expanded="false" aria-controls="amenityOne">
                    <span class="row align-items-center">
                        <span class="col-9">
                            <span class="font-weight-bold font-size-17 text-dark mb-3">Tiện ích</span>
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
        <div id="amenityOne" class="collapse show" aria-labelledby="amenityHeadingOne" data-parent="#amenityAccordion">
            <div class="card-body pt-0 mt-1 px-5 pb-4">
                <?php foreach ($amenities as $key => $amenity) : ?>
                    <?php
                    // Kiểm tra xem tên tiện ích có được chọn không
                    $isChecked = isset($_GET['amenity']) && in_array($amenity['ame_name'], $_GET['amenity']) ? 'checked' : '';
                    ?>
                    <div class="form-group font-size-14 text-lh-md text-secondary mb-3 flex-center-between">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" <?= $isChecked ?> value="<?= htmlspecialchars($amenity['ame_name']) ?>" name="amenity[]" class="custom-control-input amenity" id="<?= htmlspecialchars($key) ?>" />
                            <label class="custom-control-label" for="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($amenity['ame_name']) ?></label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>