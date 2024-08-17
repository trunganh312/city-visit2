<div id="districtAccordion" class="accordion rounded-0 shadow-none border-top">
    <div class="border-0">
        <div class="card-collapse" id="districtHeadingOne">
            <h3 class="mb-0">
                <button type="button" class="btn btn-link btn-block card-btn py-2 px-5 text-lh-3 collapsed" data-toggle="collapse" data-target="#districtOne" aria-expanded="false" aria-controls="districtOne">
                    <span class="row align-items-center">
                        <span class="col-9">
                            <span class="font-weight-bold font-size-17 text-dark mb-3">Quận/huyện</span>
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
        <div id="districtOne" class="collapse show" aria-labelledby="districtHeadingOne" data-parent="#districtAccordion">
            <div class="card-body pt-0 mt-1 px-5 pb-4">
                <!-- Checkboxes -->
                <?
                foreach ($districts as $district) { ?>
                    <? $checked =  isset($_GET['district']) && $_GET['district'] == $district['dis_name'] ? 'checked' : ''; ?>
                    <div class="form-group font-size-14 text-lh-md text-secondary mb-3 flex-center-between">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" <?= $checked ?> class="custom-control-input district" value="<?= $district['dis_name'] ?>" id="<?= $district['dis_name'] ?>" />
                            <label class="custom-control-label" for="<?= $district['dis_name'] ?>"><?= $district['dis_name'] ?></label>
                        </div>
                    </div>
                <?php } ?>
                <!-- End Checkboxes -->
            </div>
        </div>
    </div>
</div>