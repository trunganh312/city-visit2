<?
include('config_module.php');
$Admin->checkPermission('admin_edit');

/** --- Khai báo một số biến cơ bản --- **/
$table      =   'admin';
$field_id   =   'adm_id';
$page_title =   'Sửa thông tin Tài khoản Admin';
$record_id  =   getValue('id');
$record_info    =   $DB->query("SELECT * FROM " . $table . " WHERE " . $field_id . " = " . $record_id)->getOne();
if (empty($record_info)) {
    exit('Dữ liệu này không tồn tại!');
}
/** --- End of Khai báo một số biến cơ bản --- **/

//Lấy ra các Nhóm hiện tại của Admin
$list_current_group     =   $Admin->getListIDGroupOfAdmin($record_id, true);
$record_info['group']   =   $list_current_group;

/** --- Class query để insert dữ liệu --- **/
$Query  =   new GenerateQuery($table);
$Query->add('adm_name', DATA_STRING, '', 'Bạn chưa nhập họ tên')
    ->add('adm_email', DATA_STRING, '', 'Bạn chưa nhập Email', 'Email này đã tồn tại')
    ->add('adm_phone', DATA_STRING, '', 'Bạn chưa nhập số ĐT', 'Số ĐT này đã tồn tại')
    ->add('adm_hotline', DATA_STRING, '', '')
    ->add('adm_link_fb', DATA_STRING, '')
    ->add('adm_last_update', DATA_INTEGER, CURRENT_TIME)
    ->add('adm_user_id', DATA_INTEGER, 0);
/** --- End of Class query để insert dữ liệu --- **/

/** --- Submit form --- **/
if ($Query->submitForm()) {

    $password   =   getValue('password', 'str', 'POST', '');
    if ($password != '') {
        $Query->add('adm_password', DATA_STRING, $Admin->generatePassword($password, $record_info['adm_random']));
    }

    //Kiểm tra Email
    $email  =   getValue('adm_email', 'str', 'POST', '');
    if (!validate_email($email)) {
        $Query->addError('Email không hợp lệ');
    }

    /** Update lại các group của Admin **/
    //Lấy ID cua cac group
    $arr_value_group    =   getValue('group', GET_ARRAY, 'POST', []);

    if (empty($arr_value_group)) {
        $Query->addError('Bạn chưa chọn Nhóm');
    }

    //Update các group của Admin
    $group_insert   =   [];
    $group_delete   =   [];

    //Lấy ra các Group Thêm/Xóa
    foreach ($list_current_group as $id) {
        if (!in_array($id, $arr_value_group)) $group_delete[] =   $id;
    }
    foreach ($arr_value_group as $id) {
        if (!in_array($id, $list_current_group)) $group_insert[] =   '(' . $record_id . ',' . $id . ')';
    }

    /** Nếu là CSKH thì bắt buộc phải có email dùng để CSKH **/
    if (in_array(ADMIN_GROUP_CSKH, $arr_value_group)) {
        $Query->add('adm_email_cskh', GET_STRING, '', 'Bạn chưa nhập email CSKH');
    }

    //Validate form
    if ($Query->validate($field_id, $record_id)) {

        if ($DB->execute($Query->generateQueryUpdate($field_id, $record_id)) >= 0) {

            //Xóa hoặc Thêm các Nhóm của Admin
            if (!empty($group_delete)) {
                $DB->execute("DELETE FROM admin_group_admin WHERE aga_admin_id = " . $record_id . " AND aga_group_id IN(" . implode(',', $group_delete) . ")");
            }
            if (!empty($group_insert)) {
                $DB->execute("INSERT INTO admin_group_admin VALUES" . implode(',', $group_insert));
            }

            /** Lưu log **/
            $data_new   =   $DB->query("SELECT * FROM admin WHERE adm_id = $record_id")->getOne();
            $data_new['group']  =   $group_insert;
            unset($record_info['adm_password']);
            unset($data_new['adm_password']);
            $Log->genContent($record_info, $data_new)->createLog('admin', $record_id);

            set_session_toastr();
            reload_parent_window();
        } else {
            $Query->addError('Đã có lỗi xảy ra, vui lòng thử lại!');
        }
    }
}
/** --- End of Submit form --- **/

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?= $Layout->loadHead($page_title); ?>
    <style>
        .list_group_process label {
            width: 30%;
            display: inline-block;
            margin-right: 10px;
        }
    </style>
</head>

<body class="window-thickbox">
    <?
    $Layout->setPopup(true)->header($page_title);
    ?>
    <?
    //Tạo ra các biến sẵn được lấy ra từ bản ghi này để fill vào form
    $Query->generateVariable($record_info);

    //Lấy thông tin User nếu có
    $user_name  =   '';
    $row    =   $DB->query("SELECT use_name, use_email FROM user WHERE use_id = " . $record_info['adm_user_id'])->getOne();
    if (!empty($row)) {
        $user_name  =   $row['use_name'] . ' - ' . $row['use_email'];
    }

    //Show form data
    $Form   =   new GenerateForm;
    ?>
    <?= $Form->createForm() ?>
    <?= $Form->showError($Query->error) ?>
    <?= $Form->text('Họ tên', 'adm_name', $adm_name, true) ?>
    <?= $Form->text('Email', 'adm_email', $adm_email, true) ?>
    <?= $Form->text('Điện thoại', 'adm_phone', $adm_phone, true) ?>
    <?= $Form->text('Link FB', 'adm_link_fb', $adm_link_fb) ?>
    <div class="form-group">
        <label>
            <span class="mark-require">*</span>
            Bộ phận :
        </label>
        <div class="form_input list_group_process">
            <?
            foreach ($list_group_admin as $k => $v) {
            ?>
                <label><input type="checkbox" name="group[]" value="<?= $k ?>" <?= (in_array($k, $list_current_group) ? ' checked' : '') ?> />&nbsp;<?= $v ?></label>
            <?
            }
            ?>
        </div>
    </div>
    <?= $Form->text('Email CSKH', 'adm_email_cskh', $adm_email_cskh, false, 'Nếu là nhóm CSKH thì cần có email CSKH') ?>
    <?= $Form->text('Hotline CSKH', 'adm_hotline', $adm_hotline, false, 'Nếu là nhóm CSKH thì cần có số Hotline riêng') ?>
    <div class="form-group">
        <label>
            Mật khẩu :
        </label>
        <div class="form_input">
            <input type="password" id="password" name="password" class="form-control" />
            <span class="fuzzy">Nếu không đổi mật khẩu thì bỏ trống</span>
        </div>
    </div>
    <div class="form-group">
        <label>User web :</label>
        <div class="form_input">
            <div class="row">
                <input type="text" class="form-control search_auto" id="search_user" value="<?= $user_name ?>" autocomplete="off" data-target="user" />&nbsp;<i>Gõ tên hoặc email để tìm</i>
                <input type="hidden" name="adm_user_id" id="user_id" value="<?= $record_info['adm_user_id'] ?>" />
            </div>
        </div>
    </div>
    <?= $Form->button('Cập nhật') ?>
    <?= $Form->closeForm() ?>
    <?
    $Layout->footer();
    ?><?
        $Layout->loadMapInit();
        ?>
</body>

</html>