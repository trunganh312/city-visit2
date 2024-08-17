<?
include('config_module.php');

/** --- Khai báo một số biến cơ bản --- **/
$table      =   'admin';
$field_id   =   'adm_id';
$page_name  =   'Tài khoản';    //Hiển thị trên Tab trình duyệt cho ngắn gọn
$page_title =   'Cập nhật thông tin tài khoản'; //Thẻ H1 của page
$record_id  =   $Admin->id;

$record_info    =   $DB->query("SELECT * FROM " . $table . " WHERE " . $field_id . " = " . $record_id)->getOne();
if (empty($record_info)) {
    exit('Dữ liệu này không tồn tại!');
}

/** --- End of Khai báo một số biến cơ bản --- **/

/** --- Class query để insert dữ liệu --- **/
$Query  =   new GenerateQuery($table);
$Query->add('adm_phone', DATA_STRING, '', 'Bạn chưa nhập số điện thoại')
    ->add('adm_sex', DATA_INTEGER, 0, 'Bạn chưa chọn giới tính')
    ->add('adm_address', DATA_STRING, '', 'Bạn chưa nhập địa chỉ');

/** --- End of Class query để insert dữ liệu --- **/

/** --- Submit form --- **/
if ($Query->submitForm()) {

    /** --- Đổi password --- **/
    $new_password       =   getValue('new_password', 'str', 'POST', '');

    if ($new_password != '') {
        $current_pwd        =   getValue('current_pwd', 'str', 'POST', '');
        $new_password_cf    =   getValue('new_password_confirm', 'str', 'POST', '');

        if ($Admin->generatePassword($current_pwd, $record_info['adm_random']) != $record_info['adm_password']) {
            $Query->addError('Mật khẩu hiện tại không đúng');
        }

        if ($new_password != $new_password_cf) {
            $Query->addError('Mật khẩu xác nhận không trùng khớp');
        }

        if (strlen($new_password) < 6) {
            $Query->addError('Mật khẩu mới phải có tối thiểu 6 ký tự');
        } else {
            $adm_password   =   $Admin->generatePassword($new_password, $record_info['adm_random']);
            $Query->add('adm_password', DATA_STRING, $adm_password, 'Vui lòng nhập Mật khẩu');
        }
    }

    //Validate form
    if ($Query->validate($field_id, $record_id)) {

        if ($DB->execute($Query->generateQueryUpdate($field_id, $record_id)) >= 0) {
            set_session_toastr();
            redirect_url($_SERVER['REQUEST_URI']);
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
    <?= $Layout->loadHead($page_name) ?>
</head>

<body class="sidebar-mini">
    <?
    $Layout->header($page_title);

    //Tạo ra các biến sẵn để fill vào form dựa vào các hàm add trường dữ liệu ở trên (GenerateQuery)
    $Query->generateVariable($record_info);

    //Show form data
    $Form   =   new GenerateForm;
    ?>
    <?= $Form->createForm() ?>
    <?= $Form->showError($Query->error) ?>
    <?
    //Check xem có phải là chưa đổi mật khẩu mặc định hay ko
    if ($Admin->generatePassword(PWD_DEFAULT, $Admin->info['adm_random']) == $Admin->info['adm_password']) {
    ?>
        <div class="row note_page">
            <h5 class="error text-center" style="width: 100%;">Vui lòng đổi mật khẩu trước khi sử dụng CMS!</h5>
        </div>
    <?
    }
    ?>
    <?= $Form->textHTML('Họ tên', $adm_name) ?>
    <?= $Form->textHTML('Email', $adm_email) ?>
    <?= $Form->textHTML('Nhóm', isset($list_group_admin[$adm_group]) ? $list_group_admin[$adm_group] : 'N/A') ?>
    <?= $Form->select('Giới tính', 'adm_sex', $cfg_sex, $adm_sex, true) ?>
    <?= $Form->text('Địa chỉ', 'adm_address', $adm_address, true) ?>
    <?= $Form->text('Điện thoại', 'adm_phone', $adm_phone, true) ?>
    <div class="form-group control_current_pwd">
        <label>Mật khẩu hiện tại :</label>
        <div class="form_input">
            <input type="password" id="current_pwd" name="current_pwd" class="form-control"><span class="fuzzy"></span>
        </div>
    </div>
    <div class="form-group control_current_pwd">
        <label>Mật khẩu mới :</label>
        <div class="form_input">
            <input type="password" id="new_password" name="new_password" class="form-control"><span class="fuzzy">Tối thiểu 6 ký tự. Lưu ý tắt bộ gõ Tiếng Việt trước khi nhập.</span>
        </div>
    </div>
    <div class="form-group control_current_pwd">
        <label>Nhập lại mật khẩu mới :</label>
        <div class="form_input">
            <input type="password" id="new_password_confirm" name="new_password_confirm" class="form-control"><span class="fuzzy"></span>
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