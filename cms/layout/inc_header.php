<?
global  $DB, $Admin, $cfg_path_theme_image;
?>
<nav class="main-header navbar navbar-expand navbar-white">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li id="toggle_panel" class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link admin_name" data-toggle="dropdown" href="#">
                <i class="fas fa-user"></i>
                <span><?= $Admin->name ?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="/module/admin/profile.php" class="dropdown-item">
                    <i class="fas fa-user-circle"></i> Trang cá nhân
                </a>
                <div class="dropdown-divider"></div>
                <a href="/logout.php" class="dropdown-item">
                    <i class="fas fa-sign-out-alt"></i> Thoát
                </a>
            </div>
        </li>
    </ul>
</nav>
<aside class="main-sidebar">
    <a href="/" class="brand-link">
        <img src="<?= $cfg_path_theme_image ?>logo-cms.png" alt="Logo" class="brand-image">
        <span class="brand-text font-weight-light hide">CRM</span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?
                //Lấy module và tính năng để active menu
                $module_active  =   $menu_active    =   '';

                $url            =   $_SERVER['REQUEST_URI'];
                $arr_url        =   explode('/module/', $url);

                if (isset($arr_url[1])) {
                    $url            =   $arr_url[1];
                    $arr_url        =   explode('/', $url);
                    $module_active  =   $arr_url[0];
                    $menu_active    =   $arr_url[1];
                }

                //Chỉ lấy ra các module và các file tính năng mà user này có quyền
                $arr_module    =   [];
                $list_modules  =   '0';

                /**
                 * Nếu làm tính năng phân quyền thì sẽ sử dụng code này 
                 **/
                /*
                $data   =   $DB->query("SELECT per_file, per_module_id
                                        FROM permission
                                        INNER JOIN permission_group_admin ON (per_id = pega_permission_id)
                                        INNER JOIN admin_group_admin ON (pega_group_id = aga_group_id)
                                        WHERE per_active = 1 AND per_file <> '' AND aga_admin_id = " . $Admin->id)
                                        ->toArray();
                if (!empty($data)) {
                    foreach ($data as $row) {
                        //Nếu chưa tồn tại phần tử Module ID trong mảng thì ghép ID của module vào câu SQL
                        if (!isset($arr_module[$row['per_module_id']])) {
                            $list_modules   .=  ',' . $row['per_module_id'];
                        }
                        //Gán các file vào mảng module tương ứng
                        $arr_module[$row['per_module_id']][]    =   $row['per_file'];
                    }
                }
                */

                /**
                 * Nếu KO có tính năng phân quyền thì sẻ dụng code này 
                 **/
                $data   =   $DB->query("SELECT *
                                        FROM module_file
                                        WHERE modf_active = 1")
                    ->toArray();
                if (!empty($data)) {
                    foreach ($data as $row) {
                        //Nếu chưa tồn tại phần tử Module ID trong mảng thì ghép ID của module vào câu SQL
                        if (!isset($arr_module[$row['modf_module_id']])) {
                            $list_modules   .=  ',' . $row['modf_module_id'];
                        }
                        //Gán các file vào mảng module tương ứng
                        $arr_module[$row['modf_module_id']][]   =   $row['modf_file'];
                    }
                }

                //Lấy ra các Module có quyền
                $data  =  $DB->query("SELECT mod_id, mod_name, mod_folder, mod_icon
                                       FROM module
                                       WHERE mod_active = 1" . (!$Admin->isSuperAdmin() ? " AND mod_id IN(" . $list_modules . ")" : "") . "
                                       ORDER BY mod_order")
                    ->toArray();

                foreach ($data as $row) {

                    //Chỉ CTO mới quản lý các cấu hình hệ thống
                    if ($row['mod_folder'] == 'system' && !$Admin->cto) {
                        continue;
                    }
                ?>
                    <li class="nav-item has-treeview <?= ($row['mod_folder'] == $module_active ? 'menu-open' : '') ?>">
                        <a href="#" class="nav-link <?= ($row['mod_folder'] == $module_active ? 'active' : '') ?>">
                            <i class="nav-icon <?= $row['mod_icon'] ?>"></i>
                            <p><?= $row['mod_name'] ?></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?
                            $data_child   =  $DB->query("SELECT *
                                                        FROM module_file
                                                        WHERE modf_module_id = " . $row['mod_id'] . " AND modf_active = 1 AND modf_parent_id = 0
                                                        ORDER BY modf_order")
                                ->toArray();

                            $count  =   0;
                            foreach ($data_child as $row_child) {

                                //Nếu là nhóm tính năng
                                if ($row_child['modf_file'] == '') {
                                    //Lấy ra các tính năng của nhóm
                                    $menu   =   $DB->query("SELECT *
                                                            FROM module_file
                                                            WHERE modf_module_id = " . $row['mod_id'] . " AND modf_active = 1 AND modf_parent_id = " . $row_child['modf_id'] . "
                                                            ORDER BY modf_order")
                                        ->toArray();
                                    //Cần duyệt trước mảng $menu này 1 lần để lấy menu-open hay ko
                                    //Biến dùng để check xem có đang thực hiện tính năng ở module này hay ko
                                    $is_open    =   false;
                                    $file_feature_active    =   ''; //Lấy tên file mà đang được thực thi để đoạn bên dưới cho class active
                                    $has_permission =   false;  //Cần 1 biến để đánh dấu xem Admin này có quyền nào thuộc nhóm này hay ko, nếu ko có thì ko hiển thị

                                    foreach ($menu as $m) {
                                        if ($row['mod_folder'] == $module_active && strpos($menu_active, $m['modf_file']) !== false) {
                                            $is_open    =   true;
                                            $file_feature_active    =   $m['modf_file'];
                                        }
                                        if (isset($arr_module[$row['mod_id']]) && in_array($m['modf_file'], $arr_module[$row['mod_id']])) {
                                            $has_permission =   true;
                                        }
                                        //echo    $m['modf_file'] . ': ' . ($has_permission ? 'True' : 'False') . '<br>';
                                    }
                                    //dump_data($arr_module[$row['mod_id']]);
                                    //echo    $m['modf_file'] . ': ' . ($has_permission ? 'True' : 'False') . '<br>';

                                    if ($Admin->isSuperAdmin() || $has_permission) {
                            ?>
                                        <li class="nav-item has-treeview <? if ($is_open) {
                                                                                echo 'menu-open';
                                                                                unset($is_open);
                                                                            } ?>">
                                            <a href="#" class="nav-link">
                                                <p><i class="fas fa-caret-right dot_bull"></i><?= $row_child['modf_name'] ?></p>
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                            <ul class="nav nav-treeview">
                                                <?
                                                foreach ($menu as $m) {
                                                    if ($Admin->isSuperAdmin() || (isset($arr_module[$row['mod_id']]) && in_array($m['modf_file'], $arr_module[$row['mod_id']]))) {
                                                ?>
                                                        <li class="nav-item">
                                                            <a href="/module/<?= $row['mod_folder'] ?>/<?= $m['modf_file'] ?>" class="nav-link <?= ($m['modf_file'] == $file_feature_active ? 'active' : '') ?>">
                                                                <p><i class="fas fa-long-arrow-alt-right dot_bull"></i><?= $m['modf_name'] ?></p>
                                                            </a>
                                                        </li>
                                                <?
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        </li>
                                    <?
                                    }
                                } else {

                                    if ($Admin->isSuperAdmin() || (isset($arr_module[$row['mod_id']]) && in_array($row_child['modf_file'], $arr_module[$row['mod_id']]))) {
                                        $is_open    =   false;
                                        if ($row['mod_folder'] == $module_active && strpos($menu_active, $row_child['modf_file']) !== false) {
                                            $is_open    =   true;
                                        }
                                    ?>
                                        <li class="nav-item">
                                            <a href="/module/<?= $row['mod_folder'] ?>/<?= $row_child['modf_file'] ?>" class="nav-link <?= ($is_open ? 'active' : '') ?>">
                                                <p><i class="fas fa-caret-right dot_bull"></i><?= $row_child['modf_name'] ?></p>
                                            </a>
                                        </li>
                            <?
                                    }
                                }
                            }
                            ?>
                        </ul>
                    </li>
                <?
                }
                ?>
            </ul>
        </nav>
    </div>
</aside>