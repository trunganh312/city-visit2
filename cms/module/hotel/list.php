<?
include('./config_module.php');
$Admin->checkPermission('admin_list');

/** --- Khai báo một số biến cơ bản --- **/
$page_title =   'Danh sách khách sạn';
$has_edit   =   $Admin->hasPermission('admin_edit');
$has_delete  =   $Admin->hasPermission('admin_delete');
$table      =   'hotel';
$field_id   =   'hot_id';

//Biến sử dụng cho TAB_SELECT
/** --- End of Khai báo một số biến cơ bản --- **/

/** --- DataTable --- **/
$Table  =   new DataTable($table, $field_id);

$Table->column('hot_id', 'ID');
$Table->column('hot_name', 'Tên khách sạn', TAB_TEXT, true);
$Table->column('hot_name_other', 'Tên khác', TAB_TEXT, true);
$Table->column('cit_name', 'Thành phố', TAB_TEXT, true);
$Table->column('dis_name', 'Quận/huyện', TAB_TEXT, true);
$Table->column('hot_price', 'Giá', TAB_TEXT, false, true);
$Table->column('hot_star', 'Hạng sao', TAB_SELECT, false, true);
$Table->column('hot_promotion', 'Khuyến mại', TAB_CHECKBOX, false, true);
$Table->column('hot_hot', 'Hot', TAB_CHECKBOX, false, true);

// Check xem có được sửa, xóa không
if ($has_edit && $has_delete) {
    $Table->column('hot_active', 'Act', TAB_CHECKBOX, false, true);
    // $Table->setEditThickbox(['width' => 800, 'height' => 500, 'title' => 'Sửa thông tin khách sạn']);
    $Table->addED(true, true);
}

// Câu lệnh lấy ra hotel
$sql_query = "SELECT h.*, 
                c.cit_name,
                d.dis_name 
                FROM hotel h
                LEFT JOIN city c ON h.hot_city_id = c.cit_id
                LEFT JOIN district d ON h.hot_district_id = d.dis_id
                WHERE 1=1";

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
            echo    $Table->showFieldText('hot_id');
        }
        ?>
        <?
        echo    $Table->showFieldText('hot_name');
        echo    $Table->showFieldText('hot_name_other');
        echo    $Table->showFieldText('cit_name');
        echo    $Table->showFieldText('dis_name');
        echo    $Table->showFieldVND('hot_price');
        echo    $Table->showFieldText('hot_star');
        echo    $Table->showFieldCheckbox('hot_promotion');
        echo    $Table->showFieldCheckbox('hot_hot');
        ?>
        <?
        if ($has_edit)  echo    $Table->showFieldCheckbox('hot_active');
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