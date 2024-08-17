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
require_once($path_core . 'function/function.php');
require_once($path_core . 'classes/Database.php');
require_once($path_core . 'model/Model.php');
require_once($path_core . 'classes/GenerateQuery.php');
require_once($path_cms . 'core/classes/Admin.php');

$Admin  =   new Admin;

$Admin->logout($path_cms . 'login.php');
