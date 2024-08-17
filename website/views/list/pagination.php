<div class="text-center text-md-left font-size-14 mb-3 text-lh-1">Có <?= $totals ?> kết quả được tìm thấy</div>
<nav aria-label="Page navigation">
    <?
    if ($totalPages > 1) {
        echo createPagination($page, $totalPages);
    }
    ?>

</nav>