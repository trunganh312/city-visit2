<?
include('config_module.php');
$Admin->checkPermission('admin_list_group');

/** --- Khai báo một số biến cơ bản --- **/
$page_title =   'Danh sách các Nhóm Admin';
$table      =   'admin_group';
$field_id   =   'adgr_id';
$has_edit   =   $Admin->hasPermission('admin_edit_group');
/** --- End of Khai báo một số biến cơ bản --- **/

//Tạo 1 biến trùng tên trường để vào để show ở table
$adgr_parent    =   $list_group_admin;

/** --- DataTable --- **/
$Table  =   new DataTable($table, $field_id);
$Table->column('adgr_name', 'Tên nhóm', TAB_TEXT, true, true)
    ->column('adgr_parent', 'Nhóm cấp trên', TAB_SELECT, true)
    ->column('adgr_note', 'Mô tả nhóm', TAB_TEXT);
if ($has_edit) {
    $Table->column('adgr_check_level', 'Level', TAB_CHECKBOX, false, true)
        ->column('adgr_active', 'Act', TAB_CHECKBOX, false, true)
        ->addED(true)
        ->setEditFileName('edit_group.php')
        ->setEditThickbox(['width' => 500, 'height' => 300, 'title' => 'Sửa thông tin nhóm']);
}
$Table->addSQL();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= $Layout->loadHead($page_title); ?>
</head>

<body class="sidebar-mini listing_page">
    <?
    if ($Admin->hasPermission('admin_create_group')) $Layout->setTitleButton('<a href="create_group.php?TB_iframe=true&width=500&height=300" class="thickbox" title="Thêm mới nhóm tài khoản"><i class="fas fa-plus-circle"></i> Thêm mới</a>');
    $Layout->header($page_title);
    ?>
    <?= $Table->showTableData() ?>
    <?
    $Layout->footer();
    ?><?
        $Layout->loadMapInit();
        ?>
</body>

</html>