<?
include('config_module.php');
$Admin->checkPermission('admin_create');

/** --- Khai báo một số biến cơ bản --- **/
$page_title =   'Thêm mới tài khoản Admin';
/** --- End of Khai báo một số biến cơ bản --- **/

/** --- Class query để insert dữ liệu --- **/
$Query  =   new GenerateQuery('admin');
$Query->add('adm_name', DATA_STRING, '', 'Bạn chưa nhập họ tên')
    ->add('adm_email', DATA_STRING, '', 'Bạn chưa nhập Email', 'Email này đã tồn tại')
    ->add('adm_phone', DATA_STRING, '', 'Bạn chưa nhập số ĐT', 'Số ĐT này đã tồn tại')
    ->add('adm_hotline', DATA_STRING, '', '')
    ->add('adm_link_fb', DATA_STRING, '')
    ->add('adm_time_create', DATA_INTEGER, CURRENT_TIME)
    ->add('adm_last_update', DATA_INTEGER, CURRENT_TIME)
    ->add('adm_user_id', DATA_INTEGER, 0);
/** --- End of Class query để insert dữ liệu --- **/

//Lấy ID cua cac group
$arr_value_group    =   getValue('group', GET_ARRAY, 'POST', []);

/** --- Submit form --- **/
if ($Query->submitForm()) {

    /** --- Password --- **/
    //Admin random number
    $adm_random =   $Admin->generateAdminRandom();
    $Query->add('adm_random', DATA_INTEGER, $adm_random);

    $password   =   getValue('password', 'str', 'POST', '');
    if ($password == '')    $password   =   PWD_DEFAULT;    //Password mặc định
    $Query->add('adm_password', DATA_STRING, $Admin->generatePassword($password, $adm_random));

    //Kiểm tra Email
    $email  =   getValue('adm_email', 'str', 'POST', '');
    if (!validate_email($email)) {
        $Query->addError('Email không hợp lệ');
    }

    //Check nhóm
    if (empty($arr_value_group)) {
        $Query->addError('Bạn chưa chọn Nhóm');
    }

    /** Nếu là CSKH thì bắt buộc phải có email dùng để CSKH **/
    if (in_array(ADMIN_GROUP_CSKH, $arr_value_group)) {
        $Query->add('adm_email_cskh', GET_STRING, '', 'Bạn chưa nhập email CSKH');
    }

    //Validate form
    if ($Query->validate()) {

        $admin_id   =   $DB->executeReturn($Query->generateQueryInsert());

        if ($admin_id > 0) {

            //Insert các group của Admin
            $group_value    =   [];
            foreach ($arr_value_group as $id) {
                $group_value[]  =   '(' . $admin_id . ',' . $id . ')';
            }

            if (!empty($group_value))   $DB->execute("INSERT INTO admin_group_admin VALUES" . implode(',', $group_value));

            /** Lưu log **/
            $data_new   =   $DB->query("SELECT * FROM admin WHERE adm_id = $admin_id")->getOne();
            $data_new['group']  =   $group_value;
            unset($data_new['adm_password']);
            // $Log->setDataNew($data_new)->setContent('Tạo mới tài khoản')->createLog('admin', $admin_id, LOG_CREATE);

            set_session_toastr();
            reload_parent_window();
        } else {
            $Query->addError('Có lỗi xảy ra khi tạo mới bản ghi');
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
    //Tạo ra các biến sẵn để fill vào form dựa vào các hàm add trường dữ liệu ở trên (GenerateQuery)
    $Query->generateVariable();

    //Lấy thông tin User nếu có
    $user_name  =   '';
    $user_id    =   getValue('adm_user_id', GET_INT, GET_POST);
    if ($user_id > 0) {
        $row    =   $DB->query("SELECT use_name, use_email FROM user WHERE use_id = " . $user_id)->getOne();
        if (!empty($row)) {
            $user_name  =   $row['use_name'] . ' - ' . $row['use_email'];
        }
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
                <label><input type="checkbox" name="group[]" value="<?= $k ?>" <?= (in_array($k, $arr_value_group) ? ' checked' : '') ?> />&nbsp;<?= $v ?></label>
            <?
            }
            ?>
        </div>
    </div>
    <?= $Form->text('Email CSKH', 'adm_email_cskh', getValue('adm_email_cskh', GET_STRING, GET_POST, ''), false, 'Nếu là nhóm CSKH thì cần có email CSKH') ?>
    <?= $Form->text('Hotline CSKH', 'adm_hotline', $adm_hotline, false, 'Nếu là nhóm CSKH thì cần có số Hotline riêng') ?>
    <div class="form-group">
        <label>
            Mật khẩu :
        </label>
        <div class="form_input">
            <input type="password" id="password" name="password" class="form-control" />
            <span class="fuzzy">Nếu không nhập thì sẽ lấy mật khẩu mặc định: <?= PWD_DEFAULT ?></span>
        </div>
    </div>
    <div class="form-group">
        <label>User web :</label>
        <div class="form_input">
            <div class="row">
                <input type="text" class="form-control search_auto" id="search_user" value="<?= $user_name ?>" autocomplete="off" data-target="user" />&nbsp;<i>Gõ tên hoặc email để tìm</i>
                <input type="hidden" name="adm_user_id" id="user_id" value="<?= $user_id ?>" />
            </div>
        </div>
    </div>
    <?= $Form->button('Thêm mới') ?>
    <?= $Form->closeForm() ?>
    <?
    $Layout->footer();
    ?><?
        $Layout->loadMapInit();
        ?>
</body>

</html>