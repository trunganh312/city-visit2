<?
include('../../Core/Config/require_cms.php');
$Admin->checkPermission('admin_list');

/** --- Khai báo một số biến cơ bản --- **/
$page_title =   'Danh sách thành phố';
$has_edit   =   $Admin->hasPermission('admin_edit');
$has_delete  =   $Admin->hasPermission('admin_delete');
$table      =   'city';
$field_id   =   'cit_id';

/** --- DataTable --- **/
$Table  =   new DataTable($table, $field_id);

$Table->column('cit_id', 'ID');
$Table->column('cit_name', 'Tên thành phố', TAB_TEXT, true);
$Table->column('cit_name_other', 'Tên khác', TAB_TEXT, true);


if ($has_edit && $has_delete) {
    $Table->column('cit_active', 'Act', TAB_CHECKBOX, false, true);
    $Table->setEditThickbox(['width' => 800, 'height' => 500, 'title' => 'Sửa thông tin thành phố']);
    $Table->addED(true, true);
}

$Table->addSQL("SELECT * FROM city ORDER BY cit_name ASC");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= $Layout->loadHead($page_title); ?>
</head>

<body class="sidebar-mini listing_page">
    <?
    if ($Admin->hasPermission('admin_create')) $Layout->setTitleButton('<a href="create.php?TB_iframe=true&width=800&height=500" class="thickbox" title="Thêm mới thành phố"><i class="fas fa-plus-circle"></i> Thêm mới</a>');
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
            echo    $Table->showFieldText('cit_id');
        }
        ?>
        <?
        echo    $Table->showFieldText('cit_name');
        echo    $Table->showFieldText('cit_name_other');
        ?>
        <?
        if ($has_edit)  echo    $Table->showFieldCheckbox('cit_active');
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