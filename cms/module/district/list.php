<?
include('./config_module.php');
$Admin->checkPermission('admin_list');

/** --- Khai báo một số biến cơ bản --- **/
$page_title =   'Danh sách khách sạn';
$has_edit   =   $Admin->hasPermission('admin_edit');
$has_delete  =   $Admin->hasPermission('admin_delete');
$table      =   'district';
$field_id   =   'dis_id';

//Biến sử dụng cho TAB_SELECT
/** --- End of Khai báo một số biến cơ bản --- **/

/** --- DataTable --- **/
$Table  =   new DataTable($table, $field_id);

$Table->column('dis_id', 'ID');
$Table->column('dis_name', 'Tên quận huyện', TAB_TEXT, true);
$Table->column('dis_name_other', 'Tên khác', TAB_TEXT, true);
$Table->column('cit_name', 'Thành phố', TAB_TEXT, true);
$Table->column('dis_address_map', 'Địa chỉ', TAB_TEXT, true);
$Table->column('dis_hot', 'Hot', TAB_CHECKBOX, false, true);

// Check xem có được sửa, xóa không
if ($has_edit && $has_delete) {
    $Table->column('dis_active', 'Act', TAB_CHECKBOX, false, true);
    // $Table->setEditThickbox(['width' => 800, 'height' => 500, 'title' => 'Sửa thông tin khách sạn']);
    $Table->addED(true, true);
}

$cit_name = getValue('cit_name', GET_STRING);

// Câu lệnh lấy ra hotel
$sql_query = "SELECT d.*, 
                c.cit_name  
                FROM district d
                LEFT JOIN city c ON d.dis_city_id = c.cit_id
                WHERE 1=1";

// Thêm điều kiện search cho thành phố
if (!empty($cit_name)) {
    $sql_query .= " AND c.cit_name LIKE '%" . $cit_name . "%'";
}

// Câu lệnh lấy ra khách sạn
$Table->addSQL($sql_query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= $Layout->loadHead($page_title); ?>
</head>

<body class="sidebar-mini listing_page">
    <?
    if ($Admin->hasPermission('admin_create')) $Layout->setTitleButton('<a href="create.php"><i class="fas fa-plus-circle"></i> Thêm mới</a>');
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
            echo    $Table->showFieldText('dis_id');
        }
        ?>
        <?
        echo    $Table->showFieldText('dis_name');
        echo    $Table->showFieldText('dis_name_other');
        echo    $Table->showFieldText('cit_name');
        echo    $Table->showFieldText('dis_address_map');
        echo    $Table->showFieldCheckbox('dis_hot');
        ?>
        <?
        if ($has_edit)  echo    $Table->showFieldCheckbox('dis_active');
        ?>
        <?= $Table->closeTR($row[$field_id]); ?>
    <?
    }
    ?>
    <?= $Table->closeTable(); ?>
    <?
    $Layout->footer();
    ?>
    <?
    $Layout->loadMapInit();
    ?>
</body>

</html>