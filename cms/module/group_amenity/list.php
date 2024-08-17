<?
include('../../Core/Config/require_cms.php');
$Admin->checkPermission('admin_list');

/** --- Khai báo một số biến cơ bản --- **/
$page_title =   'Danh sách nhóm tiện ích';
$has_edit   =   $Admin->hasPermission('admin_edit');
$has_delete  =   $Admin->hasPermission('admin_delete');
$table      =   'amenity_group';
$field_id   =   'amg_id';
/** --- End of Khai báo một số biến cơ bản --- **/

/** --- DataTable --- **/
$Table  =   new DataTable($table, $field_id);

$Table->column('amg_id', 'ID');
$Table->column('amg_name', 'Tên nhóm tiện ích', TAB_TEXT, true);
$Table->column('amg_icon', 'Icon', TAB_TEXT);

// Check xem có được sửa, xóa không
if ($has_edit && $has_delete) {
    $Table->setEditThickbox(['width' => 800, 'height' => 150, 'title' => 'Sửa thông tin nhóm tiện ích']);
    $Table->addED(true, true);
}

// Câu lệnh lấy ra hotel
$sql_query = "SELECT * FROM amenity_group";

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
    if ($Admin->hasPermission('admin_create')) $Layout->setTitleButton('<a href="create.php?TB_iframe=true&width=800&height=150" class="thickbox" title="Thêm mới nhóm tiện ích"><i class="fas fa-plus-circle"></i> Thêm mới</a>');
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
            echo    $Table->showFieldText('amg_id');
        }
        ?>
        <?
        echo    $Table->showFieldText('amg_name');

        ?>
        <td class="text-center">
            <?
            if ($row['amg_icon'] != '') {
                echo    '<i class="' . $row['amg_icon'] . '"></i>';
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