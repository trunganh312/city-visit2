<?

// Import mọi thứ vào đây, khi dùng chỉ cần include file này vào là được

session_start();
mb_internal_encoding('UTF-8');
error_reporting(E_ALL);
ob_start();

$path_root_website      =   $_SERVER['DOCUMENT_ROOT'];

// View 
$path_website_view      = $path_root_website . '/views/';

// Controllers
$path_controllers       = $path_root_website . '/controllers/';

// public
$path_public            =   $path_root_website . '/public/';

$path_models           =   $path_root_website . '/models/';

// Config
$path_config            =   $path_root_website . '/config/';

$path_root              = dirname($path_root_website, 1);
$path_core              =   $path_root . '/core/';


require_once($path_core . 'env/ConfigEnv.php');
include_once($path_core . 'config/constant_core.php');
require_once($path_core . 'function/function.php');
require_once($path_core . 'classes/Database.php');
require_once($path_core . 'model/Model.php');
require_once($path_core . 'classes/Router.php');
require_once($path_core . 'classes/Image.php');
require_once($path_core . 'classes/Upload.php');
include_once($path_core . 'config/config_core.php');

require_once($path_root_website . '/components/classes/Layout.php');
require_once($path_root_website . '/config/function.php');
require_once($path_root_website . '/config/constant_web.php');
require_once($path_root_website . '/models/Hotel.php');
require_once($path_root_website . '/controllers/HotelController.php');

$Layout = new Layout;
$Router = new Router;
$HotelController = new HotelController;
