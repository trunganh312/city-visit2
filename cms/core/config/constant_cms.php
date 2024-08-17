<?

/** Các group Admin, được lấy theo ID của admin_group **/
define('ADMIN_GROUP_MANAGER', 1);
define('ADMIN_GROUP_CONTENT', 2);
define('ADMIN_GROUP_CSKH', 3);
define('ADMIN_GROUP_SALE', 4);
define('ADMIN_GROUP_MKT', 5);

/** Mốc thời gian đầu ngày **/
define('TODAY_BEGIN', strtotime(date('m/d/Y')));

/** Các trạng thái xử lý với partner của booking **/
define('PART_NONE', 0);  //Chưa xử lý gì
define('PART_SENT', 1);  //Đã gửi thông tin đặt dịch vụ cho Partner
define('PART_CONFIRMED', 2);    //KS đã gửi xác nhận

/** --- Các kiểu dữ liệu dùng để lưu log --- **/
define('FIELD_TEXT', 1);   //Kiểu trường lưu log dạng text
define('FIELD_DATABASE', 2);    //Kiểu trường lưu log cần lấy ra text theo ID của 1 bảng trong database
define('FIELD_CONSTANT', 3);    //Kiểu trường lưu log dạng lấy theo constant
define('FIELD_TIME', 4);    //Kiểu thời gian

define('LOG_CREATE', 1);    //Log create record
define('LOG_UPDATE', 2);    //Log update
define('LOG_DELETE', 3);
define('LOG_VIEW', 4);  //Log các hành động view

/** Kích thước tối thiểu của ảnh đại diện **/
define('MIN_WIDTH_MAIN', 1200);
define('MIN_HEIGHT_MAIN', 800);

/** --- Loại ảnh hotel --- **/
define('TYPE_ROOM', 1);
define('TYPE_HOTEL', 2);
define('TYPE_AMENITY', 3);
define('TYPE_EAT', 4);
define('TYPE_SWIM', 5);
define('TYPE_OTHER', 6);
define('TYPE_NEAR', 7);

// Kiểu khách sạn
/** Tạo ra mảng type */
// Type là 1 trong các kiểu vd: Hotel, Resort, Homestay
$type_data = [
    'Khách sạn'             =>  'Khách sạn',
    'Resort'                =>  'Resort',
    'Homestay'              =>  'Homestay',
    'Villa'                 =>  'Villa',
    'Khu nghỉ dưỡng'        =>  'Khu nghỉ dưỡng',
    'Du thuyền'             =>  'Du thuyền',
    'Ecofarm'               =>  'Ecofarm',
    'Căn hộ'                =>  'Căn hộ',
    'Tổ hợp du lịch'        =>  'Tổ hợp du lịch',
];
/** End of Tạo ra mảng type */
