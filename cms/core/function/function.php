<?

/**
 * get_key_time_view()
 * Generate ra key cua array dung de render ra chart theo time va type truyen vao
 * @param mixed $time: integer
 * @param integer $type: 1: Theo ngay, 2: Theo tuan, 3: Theo thang
 * @return void integer $key
 */
function get_key_time_view($time, $type = 1)
{

    $key    =   0;

    switch ($type) {
            //Xem theo ngày
        case 1:
            $key    =   strtotime('today', $time);
            break;

            //Xem theo tuần
        case 2:
            $key    =   strtotime('monday this week', $time);
            break;

            //Xem theo tháng
        case 3:
            $key    =   strtotime('first day of this month 00:00:00', $time);
            break;

        default:
            $key    =   strtotime('today', $time);
            break;
    }

    return $key;
}

/**
 * get_label_item_chart()
 * Lấy label của chart khi xem theo ngày tuần tháng
 * @param mixed $time
 * @param integer $type
 * @return
 */
function get_label_item_chart($time, $type = 1)
{

    $label  =   'N/A';

    switch ($type) {
            //Xem theo ngày
        case 1:
            $label  =   date('d/m', $time);
            break;

            //Xem theo tuần
        case 2:
            $date   =   strtotime('monday this week', $time);
            $label  =   date('d', $date) . '-' . date('d/m', $date + 86400 * 7 - 1);
            break;

            //Xem theo tháng
        case 3:
            $label  =   date('m/Y', $time);
            break;

        default:
            $label  =   date('d/m', $time);
            break;
    }

    return $label;
}


/**
 * restruct_json_encoded()
 * Remove chuoi json bi loi thanh chuoi dung
 * @param mixed $string_json
 * @return string json
 */
function restruct_json_encoded($string_json)
{
    $string_json    =   str_replace('"[{"id"', '[{"id"', $string_json);
    $string_json    =   str_replace('"}]"', '"}]', $string_json);

    return $string_json;
}

// Lấy tẩt cả ngày trong khoảng thời gian
function create_date_range_array($iDateFrom, $iDateTo)
{
    $aryRange = [];

    array_push($aryRange, $iDateFrom);
    if ($iDateTo > $iDateFrom) {
        do {
            $iDateFrom += 86400; // add 24 hours
            array_push($aryRange, $iDateFrom);
        } while ($iDateFrom < $iDateTo);
    }
    return $aryRange;
}

// Tạo slug 
/**
 * Chuyển đổi chuỗi kí tự thành dạng slug dùng cho việc tạo friendly url.
 * @access    public
 * @param string
 * @return    string
 */
if (!function_exists('create_slug')) {
    function create_slug($string)
    {
        $search = array(
            '#(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#',
            '#(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#',
            '#(ì|í|ị|ỉ|ĩ)#',
            '#(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#',
            '#(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#',
            '#(ỳ|ý|ỵ|ỷ|ỹ)#',
            '#(đ)#',
            '#(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)#',
            '#(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)#',
            '#(Ì|Í|Ị|Ỉ|Ĩ)#',
            '#(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)#',
            '#(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)#',
            '#(Ỳ|Ý|Ỵ|Ỷ|Ỹ)#',
            '#(Đ)#',
            "/[^a-zA-Z0-9\-\_]/",
        );
        $replace = array(
            'a',
            'e',
            'i',
            'o',
            'u',
            'y',
            'd',
            'A',
            'E',
            'I',
            'O',
            'U',
            'Y',
            'D',
            '-',
        );
        $string = preg_replace($search, $replace, $string);
        $string = preg_replace('/(-)+/', '-', $string);
        $string = strtolower($string);
        return $string;
    }
}
