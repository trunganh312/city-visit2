<?
define('BRAND_NAME', 'Website');
define('BRAND_DOMAIN', 'Website.com');

/** ======= Cac kieu du lieu add vao form ======= **/
define('DATA_STRING', 0);   //Kieu String
define('DATA_INTEGER', 1);  //Kieu Integer
define('DATA_DOUBLE', 2);   //Kieu Double
define('DATA_TIME', 3);    //Kieu Time, lưu time unix

define('DATA_FORM', 0); //Du lieu duoc lay tu form
define('DATA_VARIABLE', 1); //Du lieu duoc lay tu bien
/** ======= End of cac kieu du lieu add vao form ======= **/

/** --- Các kiểu dữ liệu dùng trong class DataTable --- **/
define('TAB_TEXT', 1);    //Kieu Text
define('TAB_NUMBER', 2);    //Kieu số
define('TAB_SELECT', 3);     //Kiểu mảng danh sách
define('TAB_IMAGE', 4); //Kiểu hiển thị ảnh
define('TAB_DATE', 5);  //Kiểu hiển thị ngày tháng
define('TAB_CHECKBOX', 6);  //Kiểu checkbox

/** Các kiểu get dữ liệu dùng cho hàm getValue **/
define('GET_STRING', 'str');
define('GET_INT', 'int');
define('GET_DOUBLE', 'dbl');
define('GET_ARRAY', 'arr');
define('GET_GET', 'GET');
define('GET_POST', 'POST');
define('GET_SESSION', 'SESSION');
define('GET_COOKIE', 'COOKIE');
define('GET_JSON', 'JSON');

/** Password default **/
define('PWD_DEFAULT', 'CmsP2021');
define('SECRET_TOKEN', 'aw$IawlOqnS0Q');
define('REGISTER_SELF', 0); //Khách tự đk tài khoản
define('REGISTER_AUTO', 1); //Tạo tk tự động

/** --- Giới tính --- **/
define('SEX_MALE', 1);
define('SEX_FEMALE', 2);

/** --- Tên các thư mục lưu ảnh theo kích thước --- **/
define('SIZE_LARGE', 'large');
define('SIZE_MEDIUM', 'medium');
define('SIZE_SMALL', 'small');
define('SIZE_BIG', 'big');

/** Lấy current time cho đồng bộ **/
define('CURRENT_TIME', time());

/** Các trạng thái của booking **/
define('STT_NEW', 0);   //Đơn mới
define('STT_PROCESS', 1);   //Đơn đang được xử lý
define('STT_SUCCESS', 2);   //Đơn được xác nhận thành công
define('STT_COMPLETED', 3); //Đơn đã hoàn thành
define('STT_CANCEL', 4);   //Đơn hủy

/** Các hình thức thanh toán **/
define('PAYMENT_CASH', 1);
define('PAYMENT_BANK', 2);  //Chuyển khoản
define('PAYMENT_VISA', 4);  //Thẻ Visa
define('PAYMENT_GATE', 3);  //Thanh toán qua cổng thanh toán

/*Đơn vị tiền*/
define('CURRENCY_VND', 1); //Đồng Việt Nam
define('CURRENCY_USD', 2); //Đồng đô la

?>