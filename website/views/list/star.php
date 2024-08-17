    <!-- Accordiaon -->
    <div id="RatingAccordion" class="accordion rounded shadow-none border-bottom">
        <div class="card-collapse" id="shopRatingHeadingOne">
            <h3 class="mb-0">
                <button type="button" class="btn btn-link btn-block card-btn py-2 px-5 text-lh-3 collapsed" data-toggle="collapse" data-target="#shopRatingOne" aria-expanded="false" aria-controls="shopRatingOne">
                    <span class="row align-items-center">
                        <span class="col-9">
                            <span class="font-weight-bold font-size-17 text-dark mb-3">Hạng sao</span>
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
        <div id="shopRatingOne" class="collapse show" aria-labelledby="shopRatingHeadingOne" data-parent="#RatingAccordion">
            <div class="card-body pt-0 mt-1 px-5">
                <!-- Checkboxes -->
                <div class="form-group font-size-14 text-lh-md text-secondary mb-3">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" <?= isset($_GET['rating']) && $_GET['rating'] == 5 ? 'checked' : '' ?> value="5" class="rating custom-control-input" id="rating5" />
                        <label class="custom-control-label text-lh-inherit text-color-1" for="rating5">
                            <div class="d-inline-flex align-items-center font-size-13 text-lh-1 text-primary">
                                <div class="green-lighter ml-1 letter-spacing-2">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="form-group font-size-14 text-lh-md text-secondary mb-3">
                    <div class="custom-control custom-checkbox">
                        <input <?= isset($_GET['rating']) && $_GET['rating'] == 4 ? 'checked' : '' ?> type="checkbox" value="4" class="rating custom-control-input" id="rating4" />
                        <label class="custom-control-label text-lh-inherit text-color-1" for="rating4">
                            <div class="d-inline-flex align-items-center font-size-13 text-lh-1 text-primary">
                                <div class="green-lighter ml-1 letter-spacing-2">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="form-group font-size-14 text-lh-md text-secondary mb-3">
                    <div class="custom-control custom-checkbox">
                        <input <?= isset($_GET['rating']) && $_GET['rating'] == 3 ? 'checked' : '' ?> type="checkbox" value="3" class="rating custom-control-input" id="rating3" />
                        <label class="custom-control-label text-lh-inherit text-color-1" for="rating3">
                            <div class="d-inline-flex align-items-center font-size-13 text-lh-1 text-primary">
                                <div class="green-lighter ml-1 letter-spacing-2">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="form-group font-size-14 text-lh-md text-secondary mb-3">
                    <div class="custom-control custom-checkbox">
                        <input <?= isset($_GET['rating']) && $_GET['rating'] == 2 ? 'checked' : '' ?> type="checkbox" value="2" class="rating custom-control-input" id="rating2" />
                        <label class="custom-control-label text-lh-inherit text-color-1" for="rating2">
                            <div class="d-inline-flex align-items-center font-size-13 text-lh-1 text-primary">
                                <div class="green-lighter ml-1 letter-spacing-2">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="form-group font-size-14 text-lh-md text-secondary mb-3">
                    <div class="custom-control custom-checkbox">
                        <input <?= isset($_GET['rating']) && $_GET['rating'] == 1 ? 'checked' : '' ?> type="checkbox" value="1" class="rating custom-control-input" id="rating1" />
                        <label class="custom-control-label text-lh-inherit text-color-1" for="rating1">
                            <div class="d-inline-flex align-items-center font-size-13 text-lh-1 text-primary">
                                <div class="green-lighter ml-1 letter-spacing-2">
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END RATE -->