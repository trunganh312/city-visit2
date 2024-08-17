<?
include('./config_module.php');
$Admin->checkPermission('admin_list');

/** --- Khai báo một số biến cơ bản --- **/
$page_title =   'Danh sách phòng';
$has_edit   =   $Admin->hasPermission('admin_edit');
$has_delete  =   $Admin->hasPermission('admin_delete');
$table      =   'room';
$field_id   =   'roo_id';

//Biến sử dụng cho TAB_SELECT
/** --- End of Khai báo một số biến cơ bản --- **/

/** --- DataTable --- **/
$Table  =   new DataTable($table, $field_id);

$Table->column('roo_id', 'ID');
$Table->column('roo_name', 'Tên phòng', TAB_TEXT, true);
$Table->column('hot_name', 'Tên khách sạn', TAB_TEXT, true);
$Table->column('roo_size', 'Diện tích(m2)', TAB_TEXT);
$Table->column('roo_size_person', 'Số người', TAB_TEXT);
$Table->column('roo_bed', 'Hạng giường', TAB_TEXT, true);
$Table->column('roo_view', 'View', TAB_TEXT, true);
$Table->column('roo_price', 'Giá', TAB_TEXT, false, true);
$Table->column('roo_description', 'Mô tả', TAB_TEXT, false, true);
$Table->column('roo_promotion', 'Khuyến mại', TAB_CHECKBOX, false, true);
$Table->column('roo_breakfast', 'Ăn sáng', TAB_CHECKBOX, false, true);

// Check xem có được sửa, xóa không
if ($has_edit && $has_delete) {
    $Table->column('roo_active', 'Act', TAB_CHECKBOX, false, true);
    // $Table->setEditThickbox(['width' => 800, 'height' => 500, 'title' => 'Sửa thông tin khách sạn']);
    $Table->addED(true, true);
}

// Câu lệnh lấy ra hotel
$sql_query = "SELECT r.*, 
                h.hot_name
                FROM room r
                LEFT JOIN hotel h ON h.hot_id = r.roo_hotel_id
                WHERE 1=1 ORDER BY r.roo_id";

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
            echo    $Table->showFieldText('roo_id');
        }
        ?>
        <?
        echo    $Table->showFieldText('roo_name');
        echo    $Table->showFieldText('hot_name');
        echo    $Table->showFieldText('roo_size');
        echo    $Table->showFieldText('roo_size_person');
        echo    $Table->showFieldText('roo_bed');
        echo    $Table->showFieldText('roo_view');
        echo    $Table->showFieldVND('roo_price');
        echo    $Table->showFieldText('roo_description');
        echo    $Table->showFieldCheckbox('roo_promotion');
        echo    $Table->showFieldCheckbox('roo_breakfast');
        ?>
        <?
        if ($has_edit)  echo    $Table->showFieldCheckbox('roo_active');
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