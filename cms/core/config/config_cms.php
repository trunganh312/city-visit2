<?
//Các biến đường dẫn theme
$cfg_path_theme         =   '/theme/';
$cfg_path_theme_image   =   $cfg_path_theme . 'image/';
$cfg_url_fontawesome    =   'https://fontawesome.com/v5/search';

/** Sex **/
$cfg_sex    =   [
    SEX_MALE    =>  'Nam',
    SEX_FEMALE  =>  'Nữ'
];

/** Các hình thức thanh toán các khoản chi tiền **/
$cfg_payment_method_spend   =   [
    PAYMENT_CASH    =>  'Tiền mặt',
    PAYMENT_BANK    =>  'Chuyển khoản',
    PAYMENT_VISA    =>  'Visa'
];

/** --- Các kiểu trường dữ liệu lưu log --- **/
$cfg_field_type =   [
    FIELD_TEXT      =>  'Text',
    FIELD_DATABASE  =>  'Database',
    FIELD_CONSTANT  =>  'Constant',
    FIELD_TIME      =>  'Thời gian'
];


$cfg_resize_image   =   [
    SIZE_LARGE  =>  ['maxwidth' => 1200, 'maxheight' => 800],
    SIZE_MEDIUM =>  ['maxwidth' => 810, 'maxheight' => 540],
    SIZE_SMALL  =>  ['maxwidth' => 450, 'maxheight' => 300]
];


/** Mảng dùng chung cho các label màu **/
$cfg_label_color    =   [
    0   =>  'olive',
    1   =>  'info',
    2   =>  'success',
    3   =>  'indigo',
    4   =>  'danger'
];


/** Trạng thái của request **/
$cms_cure_status    =   [
    STT_NEW     =>  '<span class="badge bg-olive">Request mới</span>',
    STT_PROCESS =>  '<span class="badge bg-info">Đang xử lý</span>',
    STT_SUCCESS =>  '<span class="badge bg-success">Đã tạo đơn</span>',
    STT_CANCEL  =>  '<span class="badge bg-danger">Đã hủy</span>'
];


/** Các màu dùng cho chart và thống kê **/
$cms_color  =   [
    0   =>  '#23b7e5',
    1   =>  '#ffc107',
    2   =>  '#27c24c',
    3   =>  '#f62394',
    4   =>  '#007ac7',
    5   =>  '#ff7d01',
    6   =>  '#8b20bb',
    7   =>  '#83ce01',
    8   =>  '#fffa00',
    9   =>  '#0024ba',
    10  =>  '#ff2000',
    11  =>  '#ffcf00'
];
