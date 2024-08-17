<?
session_start();
mb_internal_encoding('UTF-8');
error_reporting(E_ALL);
ob_start();

$path_root      =   $_SERVER['DOCUMENT_ROOT'];
$path_core      =    dirname($path_root, 1) . '/core/';


require_once($path_core . 'env/ConfigEnv.php');
require_once($path_core . 'classes/Database.php');
require_once($path_core . 'model/Model.php');
require_once($path_core . 'function/function.php');
require_once($path_root . '/controllers/HotelController.php');
require_once($path_root . '/controllers/DistrictController.php');
require_once($path_root . '/controllers/CityController.php');
require_once($path_root . '/controllers/CommonController.php');
require_once($path_root . '/config/constant_api.php');

$DB = new Database;
$Model = new Model;
$HotelController = new HotelController($DB);
$DistrictController = new DistrictController($DB);
$CityController = new CityController($DB);
$CommonController = new CommonController($DB);
