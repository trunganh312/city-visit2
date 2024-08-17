<?
ob_start();
session_start();

$path_root      =   $_SERVER['DOCUMENT_ROOT'];
$path_core      =    dirname($path_root, 1) . '/core/';
$path_cms       =   $path_root . '/';

require_once($path_core . 'env/ConfigEnv.php');
require_once($path_core . 'config/constant_core.php');
include_once($path_cms . 'core/config/constant_cms.php');
include_once($path_cms . 'core/function/function.php');
require_once($path_core . 'function/Function.php');
require_once($path_core . 'classes/Database.php');
require_once($path_core . 'model/Model.php');
require_once($path_core . 'classes/GenerateQuery.php');
require_once($path_cms . 'core/classes/Admin.php');

$action     =    getValue("action", GET_STRING, GET_POST, "", 1);
if (isset($_SESSION["login_error"])) unset($_SESSION["login_error"]);

$email      =   getValue("email", GET_STRING, GET_POST, "", 1);
$password   =   getValue("password", GET_STRING, GET_POST, "", 1);

if ($action == 'submitform') {

    $Admin  =   new Admin($email, $password);
    if ($Admin->logged) {
        redirect_url('/');
    }
} else {

    $Admin  =   new Admin('', '', '');
    if ($Admin->logged) {
        redirect_url('/');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= BRAND_NAME ?> CMS</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, initial-scale=1.0, user-scalable=no" id="viewport" />
    <link rel="icon" href="/favicon.ico" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;0,700;1,300&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="theme/css/login.css?v=3" />
</head>

<body>
    <div class="limiter">
        <div class="container-login100">
            <div class="login100-more" style="background-image: url('/theme/image/bg-login.jpg');"></div>
            <div class="wrap-login100 p-l-50 p-r-50 p-t-72 p-b-50">
                <form method="post" action="" class="login100-form validate-form">
                    <div class="title-login">
                        <span class="login100-form-title p-b-59">
                            <?= BRAND_DOMAIN ?>
                        </span>
                        <span class="login100-form-title p-b-59 bold-500">Đăng nhập tài khoản</span>
                    </div>
                    <div class="wrap-input100 validate-input">
                        <span class="label-input100">Email</span>
                        <input value="<?= ($email) ? $email : '' ?>" type="text" class="input100" name="email" tabindex="1" placeholder="Email" class="form_control">
                        <span class="focus-input100"></span>
                    </div>
                    <div class="wrap-input100 validate-input">
                        <span class="label-input100">Mật khẩu</span>
                        <input type="password" class="input100" name="password" tabindex="2" placeholder="Mật khẩu" class="form_control">
                        <span class="focus-input100"></span>
                    </div>
                    <?
                    if (isset($_SESSION['login_error'])) {
                    ?>
                        <div class="alert-validate">
                            <?= $_SESSION['login_error'] ?>
                        </div>
                    <?
                    }
                    ?>
                    <div class="container-login100-form-btn">
                        <div class="wrap-login100-form-btn">
                            <div class="login100-form-bgbtn"></div>
                            <input type="hidden" name="action" value="submitform" />
                            <button class="login100-form-btn">
                                Đăng nhập
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>