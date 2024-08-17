<?
include('config_module.php');

if (!$Admin->cto) {
    redirect_url('/');
}



$Admin->fakeLogin();
