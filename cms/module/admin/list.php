<?
include('config_module.php');
$Admin->checkPermission('admin_list');

/** --- Khai báo một số biến cơ bản --- **/
$page_title =   'Danh sách tài khoản Admin';
$table      =   'admin';
$field_id   =   'adm_id';
$has_edit   =   $Admin->hasPermission('admin_edit');
$has_view_log   =   $Admin->hasPermission('admin_view_log');

//Biến sử dụng cho TAB_SELECT
$adm_group  =   $list_group_admin;

/** --- End of Khai báo một số biến cơ bản --- **/

/** --- DataTable --- **/
$Table  =   new DataTable($table, $field_id);
if ($Admin->isSuperAdmin()) $Table->column('adm_id', 'ID');
$Table->column('adm_email', 'Email', TAB_TEXT, true);
$Table->column('adm_name', 'Họ tên', TAB_TEXT, true);
$Table->column('adm_phone', 'Điện thoại', TAB_TEXT, true);
$Table->column('adm_email_cskh', 'Email CSKH', TAB_TEXT, true);
$Table->column('adm_hotline', 'Hotline CSKH', TAB_TEXT, true);
$Table->column('adm_time_create', 'Ngày tạo', TAB_DATE, false, true);
$Table->column('adm_last_update', 'Last Update', TAB_DATE, false, true);
$Table->column('adm_last_login', 'Last Login', TAB_DATE, false, true);
$Table->column('adm_last_online', 'Last Online', TAB_DATE, false, true);
$Table->column('adm_group', 'Nhóm', TAB_SELECT);

if ($has_edit) {
    $Table->column('adm_active', 'Act', TAB_CHECKBOX, false, true);
    $Table->setEditThickbox(['width' => 800, 'height' => 500, 'title' => 'Sửa thông tin tài khoản']);
    $Table->addED(true);
}
$Table->addSearchData([
    'adm_group' => ['label' => 'Nhóm', 'type' => TAB_SELECT, 'query' => false]
]);

$sql_filter = $sql_join = "";
$group  =   getValue('adm_group');
if ($group > 0) {
    $sql_join   .=  "INNER JOIN admin_group_admin ON adm_id = aga_admin_id";
    $sql_filter .=  " AND aga_group_id = $group";
}

$Table->addSQL("SELECT *
                FROM admin
                $sql_join
                WHERE adm_cto <> 1 $sql_filter");

/** Tách riêng 1 câu query lấy ra group của các account để ko bị query trong vòng lặp **/
$arr_admin_group    =   [];
$data   =   $DB->query("SELECT adm_id, adgr_name
                        FROM admin
                        INNER JOIN admin_group_admin ON adm_id = aga_admin_id
                        INNER JOIN admin_group ON adgr_id = aga_group_id")
    ->toArray();
foreach ($data as $row) {
    //Nếu chưa có trong mảng thì gán group đầu tiên
    if (!isset($arr_admin_group[$row['adm_id']])) {
        $arr_admin_group[$row['adm_id']]    =   $row['adgr_name'];
    } else {
        //Nếu có trong mảng rồi thì nối thêm group tiếp theo
        $arr_admin_group[$row['adm_id']]    .=  ', ' . $row['adgr_name'];
    }
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= $Layout->loadHead($page_title); ?>
</head>

<body class="sidebar-mini listing_page">
    <?
    if ($Admin->hasPermission('admin_create')) $Layout->setTitleButton('<a href="create.php?TB_iframe=true&width=800&height=500" class="thickbox" title="Thêm mới tài khoản"><i class="fas fa-plus-circle"></i> Thêm mới</a>');
    $Layout->header($page_title);
    ?>
    <?= $Table->createTable() ?>
    <?
    //Hiển thị data
    $data   =   $DB->query($Table->sql_table)->toArray();
    $stt    =   0;

    foreach ($data as $row) {
        $Table->setRowData($row);
        $stt++;
    ?>
        <?= $Table->createTR($stt, $row[$field_id]); ?>
        <?
        if ($Admin->isSuperAdmin()) {
            echo    $Table->showFieldText('adm_id');
        }
        ?>
        <td>
            <?
            if ($Admin->cto) {
                echo    '<a href="login_by_cto.php?id=' . $row['adm_id'] . '&token=' . $Admin->genToken($row) . '" target="_blank">' . $row['adm_email'] . '</a>';
            } else {
                echo    $row['adm_email'];
            }
            ?>
        </td>
        <?
        echo    $Table->showFieldText('adm_name');
        echo    $Table->showFieldText('adm_phone');
        echo    $Table->showFieldText('adm_email_cskh');
        echo    $Table->showFieldText('adm_hotline');
        echo    $Table->showFieldDate('adm_time_create');
        ?>
        <td class="text-center">
            <p><?= date('d/m/Y', $row['adm_last_update']) ?></p>
            <p><?= date('H:i:s', $row['adm_last_update']) ?></p>
        </td>
        <td class="text-center">
            <p><?= date('d/m/Y', $row['adm_last_login']) ?></p>
            <p><?= date('H:i:s', $row['adm_last_login']) ?></p>
        </td>
        <td class="text-center">
            <p><?= date('d/m/Y', $row['adm_last_online']) ?></p>
            <p><?= date('H:i:s', $row['adm_last_online']) ?></p>
        </td>
        <td>
            <?
            if (isset($arr_admin_group[$row['adm_id']])) {
                echo    $arr_admin_group[$row['adm_id']];
            }
            ?>
        </td>
        <?
        if ($has_edit)  echo    $Table->showFieldCheckbox('adm_active');
        ?>
        <?= $Table->closeTR($row[$field_id]); ?>
    <?
    }
    ?>
    <?= $Table->closeTable(); ?>
    <?
    $Layout->footer();
    ?><?
        $Layout->loadMapInit();
        ?>
</body>

</html>