<?

/**
 * Các thông số của Environment
 */

/** --- Environment --- **/
define('ENV_ENVIRONMENT', 'dev');   //dev, pro

/** --- Database --- **/
define('ENV_DB_HOST', 'localhost');
define('ENV_DB_USERNAME', 'root');
define('ENV_DB_PASSWORD', '');
define('ENV_DB_DBNAME', 'db_name');
define('ENV_DB_SLOW_QUERY', 0.1);

/** --- Domain --- **/
define('DOMAIN_WEB', 'http://website.local');    //Ko có / ở cuối
define('DOMAIN_CMS', 'http://website.local');    //Ko có / ở cuối
define('WEBSITE_NAME', 'Website.com');

/** --- Email dùng cho class Mailer --- **/
define('MAILER_USERNAME', 'email@gmail.com');
define('MAILER_PASSWORD', 'password');
define('MAILER_DEV_TEST', 'email_dev@gmail.com');
