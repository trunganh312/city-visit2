<div class="tab-pane fade" id="pills-one-example1" role="tabpanel" aria-labelledby="pills-one-example1-tab" data-target-group="groups">
    <div class="row">
        <? foreach ($hotels as $hotel) : ?>
            <div class="col-md-6 col-lg-4 mb-3 mb-md-4 pb-1">
                <?= itemHotel($hotel) ?>
            </div>
        <? endforeach; ?>
    </div>
    <? include('pagination.php') ?>
</div>