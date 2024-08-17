<div class="mb-5 d-flex " style="justify-content: flex-end;">
    <li class="nav-item d-flex align-items-center flex-shrink-0 flex-xl-shrink-1 ">
        <select class="js-select selectpicker dropdown-select bootstrap-select__custom-nav w-auto" data-style="btn-sm py-1 px-4 px-wd-5 font-weight-normal font-size-15  text-gray-1 d-flex align-items-center" name="sort">
            <option selected value="">Sắp xếp</option>
            <option value="price-asc" <?= isset($_GET['sort']) && $_GET['sort'] == 'price-asc' ? 'selected' : ''  ?>>Giá tăng dần</option>
            <option value="price-desc" <?= isset($_GET['sort']) && $_GET['sort'] == 'price-desc' ? 'selected' : ''  ?>>Giá giảm dần</option>
        </select>
    </li>
</div>