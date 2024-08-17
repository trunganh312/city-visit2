<?
include('config_module.php');
$Admin->checkPermission('module_list');

/** --- Khai báo một số biến cơ bản --- **/
$page_title =   'Danh sách Module';
/** --- End of Khai báo một số biến cơ bản --- **/

/** --- DataTable --- **/
$Table  =   new DataTable($table, $field_id);
$Table->column('mod_name', 'Tên module', TAB_TEXT, true)
    ->column('mod_folder', 'Folder', TAB_TEXT, true)
    ->column('file', 'Tính năng của module', TAB_TEXT)
    ->column('mod_icon', 'Icon', TAB_TEXT)
    ->column('mod_order', 'Thứ tự', TAB_NUMBER, false, true)
    ->column('mod_active', 'Active', TAB_CHECKBOX, false, true)
    ->addED(true)
    ->setEditFileName('edit_module.php')
    ->setEditThickbox(['width' => 600, 'height' => 400, 'title' => 'Sửa thông tin module']);

$Table->addSQL("SELECT * FROM " . $table . " ORDER BY mod_order");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?= $Layout->loadHead($page_title) ?>
    <style>
        .col_2 {
            width: 20%;
        }

        .col_3 {
            width: 25%;
        }

        .col_4 {
            width: 5%;
        }

        .col_5 {
            width: 5%;
        }

        .col_6 {
            width: 5%;
        }

        .col_7 {
            width: 15%;
        }
    </style>
</head>

<body class="sidebar-mini listing_page">
    <?
    $Layout->setTitleButton('<a href="create_module.php?TB_iframe=true&width=600&height=400" class="thickbox" title="Thêm mới module"><i class="fas fa-plus-circle"></i> Thêm mới Module</a>');
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
        echo    $Table->createTR($stt, $row[$field_id]);
        echo    $Table->showFieldText('mod_name');
        echo    $Table->showFieldText('mod_folder');
    ?>
        <td style="width: 60%;">
            <table class="table-child-border">
                <?
                //Lấy DS các menu con của module
                $menu   =   $DB->query("SELECT *
                                        FROM module_file
                                        WHERE modf_module_id = " . $row[$field_id] . "
                                        ORDER BY modf_order")
                    ->toArray();
                $total_menu =   count($menu);
                //Nếu chưa có menu nào thì chỉ hiển thị nút để Add thêm
                if ($total_menu <= 0) {
                ?>
                    <tr>
                        <td class="text-center" style="border: none !important;">
                            <a class="thickbox" href="create_feature.php?module_id=<?= $row[$field_id] ?>&TB_iframe=true&width=600&height=400" title="Thêm tính năng cho module <?= $row['mod_name'] ?>">Thêm tính năng</a>
                        </td>
                    </tr>
                    <?
                } else {
                    $i  =   0;
                    foreach ($menu as $m) {
                        $i++;
                    ?>
                        <tr>
                            <td><?= $m['modf_name'] ?></td>
                            <td class="col_3"><?= $m['modf_file'] ?></td>
                            <td class="text-right col_4"><?= $m['modf_order'] ?></td>
                            <td class="text-center col_5">
                                <a class="thickbox" href="edit_feature.php?id=<?= $m['modf_id'] ?>&TB_iframe=true&width=600&height=400" title="Sửa tính năng của module <?= $row['mod_name'] ?>">
                                    <i class="far fa-edit"></i>
                                </a>
                            </td>
                            <td class="text-center col_6">
                                <a class="tick-active" href="/module/common/active.php?field=modf_active&id=<?= $m['modf_id'] ?>&type=module_feature" onclick="table_tick(this); return false;">
                                    <?= generate_checkbox_icon($m['modf_active']) ?>
                                </a>
                            </td>
                            <?
                            //Hiển thị nút để add thêm file 
                            if ($i == 1) {
                            ?>
                                <td rowspan="<?= $total_menu ?>" class="text-center col_7">
                                    <a class="thickbox" href="create_feature.php?module_id=<?= $row[$field_id] ?>&TB_iframe=true&width=600&height=400" title="Thêm tính năng cho module <?= $row['mod_name'] ?>">Thêm tính năng</a>
                                </td>
                            <?
                            }
                            ?>
                        </tr>
                <?
                    }
                }
                ?>
            </table>
        </td>
        <td class="text-center">
            <?
            if ($row['mod_icon'] != '') {
                echo    '<i class="' . $row['mod_icon'] . '"></i>';
            }
            ?>
        </td>
    <?
        echo    $Table->showFieldNumber('mod_order');
        echo    $Table->showFieldCheckbox('mod_active');
        echo    $Table->closeTR($row[$field_id]);
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