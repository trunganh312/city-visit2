<?
include('./config_module.php');
$Admin->checkPermission('admin_list');

/** --- Khai báo một số biến cơ bản --- **/
$page_title =   'Danh sách tiện ích';
$has_edit   =   $Admin->hasPermission('admin_edit');
$has_delete  =   $Admin->hasPermission('admin_delete');
$table      =   'amenity';
$field_id   =   'ame_id';
/** --- End of Khai báo một số biến cơ bản --- **/

/** --- DataTable --- **/
$Table  =   new DataTable($table, $field_id);

$Table->column('ame_id', 'ID');
$Table->column('ame_name', 'Tên tiện ích', TAB_TEXT, true);
$Table->column('amg_name', 'Tên nhóm', TAB_TEXT, true);
$Table->column('ame_icon', 'Icon', TAB_TEXT);

// Check xem có được sửa, xóa không
if ($has_edit && $has_delete) {
    $Table->setEditThickbox(['width' => 800, 'height' => 200, 'title' => 'Sửa thông tin tiện ích']);
    $Table->addED(true, true);
}

$amg_name = getValue('amg_name', GET_STRING);

// Câu lệnh lấy ra hotel
$sql_query = "SELECT a.*, 
                g.amg_name
                FROM amenity a
                LEFT JOIN amenity_group g ON g.amg_id = a.ame_group_id
                WHERE 1=1";

// Tìm kiếm theo tên nhóm
if (!empty($amg_name)) {
    $sql_query .=  " AND g.amg_name LIKE '%" . $amg_name . "%'";
}

// Câu lệnh lấy ra tiện ích
$Table->addSQL($sql_query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= $Layout->loadHead($page_title); ?>
</head>

<body class="sidebar-mini listing_page">
    <?
    if ($Admin->hasPermission('admin_create')) $Layout->setTitleButton('<a href="create.php?TB_iframe=true&width=800&height=200" class="thickbox" title="Thêm mới tiện ích"><i class="fas fa-plus-circle"></i> Thêm mới</a>');
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
            echo    $Table->showFieldText('ame_id');
        }
        ?>
        <?
        echo    $Table->showFieldText('ame_name');
        echo    $Table->showFieldText('amg_name');
        ?>
        <td class="text-center">
            <?
            if ($row['ame_icon'] != '') {
                echo    '<i class="' . $row['ame_icon'] . '"></i>';
            }
            ?>
        </td>
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