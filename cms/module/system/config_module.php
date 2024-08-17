<?
include('../../Core/Config/require_cms.php');

/** Chỉ có CTO mới có quyền sử dụng module cấu hình hệ thống **/
if (!$Admin->cto) {
    redirect_url('/');
}

$patch_picture_setting = '../../picture/logo/';

$table      =   'module';
$field_id   =   'mod_id';
?>