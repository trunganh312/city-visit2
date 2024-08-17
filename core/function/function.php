<?
/**
 * is_dev()
 * Check dev OR pro environment
 * @return boolean
 */
function is_dev() {
    if (ENV_ENVIRONMENT == 'dev') {
        return true;
    }
    return false;
}


/**
 * is_pro()
 * Check dev OR pro environment
 * @return boolean
 */
function is_pro() {
    if (ENV_ENVIRONMENT == 'pro') {
        return true;
    }
    return false;
}


/**
 * Lấy base URL của site
 */
function base_url()
{
    return sprintf(
        "%s://%s/",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['SERVER_NAME']
    );
}


/**
 * getValue()
 * Lấy giá trị của 1 input
 *
 * @param mixed  $value_name:   Tên của input
 * @param string $data_type:    GET_INT, GET_STRING, GET_DOUBLE, GET_ARRAY
 * @param string $method:       GET_POST, GET_GET, GET_SESSION, GET_COOKIE
 * @param int    $default_value: Giá trị mặc định nếu ko có input $value_name
 * @param int    $advance:      1: removeInjection, 3: replaceQuot, 2: htmlspecialbo
 *
 * @return
 */
function getValue($value_name, $data_type = GET_INT, $method = GET_GET, $default_value = 0, $advance = 0)
{
    $value =   $default_value;
    
    switch ($method) {
        case GET_GET:
            if (isset($_GET[$value_name])) {
                $value = $_GET[$value_name];
            }
            break;

        case GET_POST:
            if (isset($_POST[$value_name])) {
                $value = $_POST[$value_name];
            }
            break;

        case GET_COOKIE:
            if (isset($_COOKIE[$value_name])) {
                $value = $_COOKIE[$value_name];
            }
            break;

        case GET_SESSION:
            if (isset($_SESSION[$value_name])) {
                $value = $_SESSION[$value_name];
            }
            break;
        
        case GET_JSON:
            $result = json_decode(file_get_contents('php://input'), true);
            if (isset($result[$value_name])) {
                $value = $result[$value_name];
            }
            break;

        default:
            if (isset($_GET[$value_name])) {
                $value = $_GET[$value_name];
            }
            break;
    }
    
    //Xử lý dữ liệu cho chuẩn
    switch ($data_type) {
        case GET_INT:
            $value  =   str_replace(',', '', $value);
            $value  =   (int)$value;
            if ("INF" == strval($value)) {  //Nếu số nằm ngoài phạm vi xử lý
                return 0;
            }
            break;

        case GET_STRING:
            $value  =   trim(strval($value));
            switch ($advance) {
                case 1:
                    //Remove injection cho cau query
                    $value = replaceMQ($value);
                    break;
                case 2:
                    $value = replaceQuot($value);
                    break;
                case 3:
                    $value = htmlspecialbo($value);
                    break;
            }
            break;

        case GET_DOUBLE:
            $value  =   str_replace(',', '', $value);
            $value  =   (double)$value;
            if ("INF" == strval($value)) {
                return 0;
            }
            break;

        case GET_ARRAY:
            $value  =   (array) $value;
            break;
    }

    return $value;
}


/**
 * get_url()
 * Get URL va loai tru cac param.
 *
 * @param mixed $remove_param
 * @param boo   $domain:      Co lay domain hay ko
 *
 * @return
 */
function get_url($remove_param = ['page'], $domain = false)
{
    //Lấy REQUEST_URI
    $url_full   =   $_SERVER['REQUEST_URI'];

    //Bẻ ký tự ? để lấy URL gốc
    $break      =   explode('?', $url_full);
    $url_return =   $break[0];
    $param      =   $_GET;

    //Loại bỏ param
    foreach ($remove_param as $p) {
        if (isset($param[$p])) {
            unset($param[$p]);
        }
    }

    if (!empty($param)) {
        $url_return .=  '?' . http_build_query($param);
        $url_return =   rawurldecode($url_return);
    }
    //Nếu có lấy domain
    if ($domain) {
        $url_return    =   substr(base_url(), 0, -1) . $url_return;
    }

    //return URL
    return $url_return;
}


/**
 * generate_param_text()
 * Replace dấu cách thành dấu + để truyền lên URL
 * @param mixed $string
 * @return
 */
function generate_param_text($string) {
    return str_replace(' ', '+', $string);
}


/**
 * generate_url_filter()
 * 
 * @param mixed $add_param
 * @param mixed $remove_param
 * @param string $url_full
 * @return
 */
function generate_url_filter ($add_param = [], $remove_param = [], $url_full = '') {
    
    //Mảng các param mặc định sẽ bị remove đi
    $remove_default =   [
                        'page',
                        'utm_web'
                        ];
    
    $is_uri =   false;
    $url    =   '';
    
    //Nếu ko truyền vào URL thì sẽ lấy theo URI và cần phải nối thêm domain vào đầu URL
    if ($url_full == '') {
        $url_full   =   $_SERVER['REQUEST_URI'];
        $is_uri     =   true;
    }
    
    //Bẻ ký tự ? để lấy URL gốc
    $break  =   explode('?', $url_full);
    $url    =   $break[0];
    $param  =   $_GET;
    
    //Loại bỏ param
    $remove =   array_merge($remove_default, $remove_param);
    foreach ($remove as $p) {
        if (isset($param[$p]))    unset($param[$p]);
    }
    
    //Nếu trường hợp $value là 1 array thì cần phải xử lý loại bỏ/thêm value của array vào param
    foreach ($add_param as $p => $value) {
        
        if (is_array($value)) {
            if (!empty($value)) {
                
                if (!isset($param[$p])) {
                    //Nếu chưa có thì khởi tạp array của param
                    $param[$p]  =   $value;
                } else {
                    
                    //Nếu đã có array của parram trên URL thì check tiếp: Đã có value của param rồi thì hủy đi, chưa có thì thêm vào
                    if (is_array($param[$p])) {
                        foreach ($value as $v) {
                            $key    =   array_search($v, $param[$p]);
                            if ($key !== false) {
                                unset($param[$p][$key]);
                            } else {
                                $param[$p][]    =   $v;
                            }
                            //unset($add_param[$p]);
                            if (!empty($param[$p])) {
                                $param[$p]  =   array_values($param[$p]);
                            }
                        }
                        
                    } else {
                        //Nếu chưa có (hoặc param ko phải là array) thì khởi tạo param
                        $param[$p]  =   $value;
                    }
                }
                //unset($add_param[$p]);
            }
            
        } else {
            //Unset param này đi để gán lại giá trị
            //if ($p == 'loai-tour')  exit($param[$p] . '-' . $value);
            //if (isset($param[$p])) {
                //unset($add_param[$p]);
                //unset($param[$p]);
                $param[$p]  =   $value;
            //}
        }
    }
    //dump($param);
    //dump($add_param);
    //Thêm các param mới
    //$param  =   array_merge($param, $add_param);
    if (!empty($param)) {
        $url    .=  '?' . http_build_query($param);
        $url    =   rawurldecode($url);
    }
    
    //Nếu chưa phải là Full URL (Bao gồm cả domain) thì nối thêm domain vào
    if($is_uri) $url =   DOMAIN_WEB . $url;
    
    //return URL
    return $url;
}


/**
 * get_url_symbol_query()
 * Lay ky tu de noi query cua URL.
 *
 * @param mixed $url
 *
 * @return ? OR & OR ''
 */
function get_url_symbol_query($url)
{
    $symbol =   '';

    if (false !== strpos($url, '?')) {
        if ('?' != substr($url, -1) && '&' != substr($url, -1)) {
            $symbol =   '&';
        }
    } else {
        $symbol =   '?';
    }

    return $symbol;
}


/**
 * str_totime()
 * Convert time tu String sang Integer
 * @param string $string dd/mm/YYYY [H:i:s]
 * @return integer
 */
function str_totime($string = ''){
    
    $time_return    =   0;
    
    $string  =  trim($string);
	if($string == '')  return $time_return;
    
    $string =   str_replace('-', '/', $string);
	
    //Bẻ dấu cách trong trường hợp có thêm giờ (dd/mm/YYY H:i:s)
    $arr_string     =   explode(' ', $string);
    $string_date    =   $arr_string[0];
    $string_hour    =   isset($arr_string[1]) ? $arr_string[1] : '';
    
    //Bẻ chuỗi ngày
    $arr_date   =   explode('/', $string_date); 
    
	if(count($arr_date) == 3){
        $day    =   (int)$arr_date[0];
        $month  =   (int)$arr_date[1];
        $year   =   (int)$arr_date[2];
        
        //Kiểm tra ngày hợp lệ thì convert
        if (checkdate($month, $day, $year)) {
			$time_return =   strtotime($month . '/' . $day . '/' . $year . ' ' . $string_hour);
		}
	}
	
	return intval($time_return);
}


/**
 * generate_time_from_date_range()
 * Generate ra integer time tu daterangepicker.
 * @param mixed $date_range
 * @return ['from' => from, 'to' => to]
 */
function generate_time_from_date_range($date_range, $end_day = true)
{
    $time_from  =   0;
    $time_to    =   0;

    $exp    =   explode('-', $date_range);
    if (isset($exp[0]) && isset($exp[1])) {
        $time_from  =   str_totime($exp[0]);
        $time_to    =   str_totime($exp[1]);
    }

    //Nếu lấy đến cuối ngày thì phải cộng với 86399
    if ($end_day) {
        $time_to    =   strtotime(date('m/d/Y', $time_to)) + 86399;
    }

    return  [
        'from'  => $time_from,
        'to'    => $time_to,
    ];
}


/**
 * replaceMQ()
 * 
 * @param mixed $text
 * @return
 */
function replaceMQ($text){
	
    $text =   str_replace("\\", "", $text);
    $text =   str_replace("\'", "'", $text);
    $text =   str_replace("'", "''", $text);
    
	return $text;
}


/**
 * removeInjection()
 * Remove cac ki tu injection
 * @param mixed $text
 * @return string
 */
function removeInjection($text){
	$text	= str_replace("\'", "'", $text);
	$text	= str_replace("'", " ", $text);
    $text	= str_replace(";", " ", $text);
    $text	= str_replace("=", " ", $text);
    
	return trim($text);
}


/** --- Close or Reload when close thickbox --- **/
function close_tb_window($remove_el = '')
{
    $str    =   '<script type="text/javascript">';

    //Nếu có xóa element của parent
    if ('' != $remove_el) {
        $str .= 'var el_remove = parent.document.getElementById("' . $remove_el . '");
                    el_remove.parentNode.removeChild(el_remove);';
    }

    $str .= 'window.parent.tb_remove();
                </script>';
    echo    $str;
    exit();
}


/**
 * reload_parent_window()
 * Reload parent
 * @param string $el
 * @return void
 */
function reload_parent_window($el = '')
{
    echo '<script type="text/javascript">
            parent.location.href = parent.location.href' . ($el != '' ? ' + "?' . $el . '"' : '') . ';
         </script>';
    exit();
}


/**
 * removeAccent()
 * Replace cac ky tu Tieng Viet thanh ko dau
 * @param mixed $str
 * @return string Chuoi ko dau
 */
function removeAccent($str){
    
    //Một số âm lặp 2 lần ("ê", "ô", "ị"...) vì là 2 bộ gõ khác nhau
    
	$marSearch	=	["à","á","ạ","ả","ã","â","ầ","ấ","ậ","ẩ","ẫ","ă","ằ","ắ","ặ","ẳ","ẵ","â",
					"è","é","ẹ","ẻ","ẽ","ê","ề","ế","ệ","ể","ễ","ê",
					"ì","í","ị","ỉ","ĩ","ị",
					"ò","ó","ọ","ỏ","õ","ô","ồ","ố","ộ","ổ","ỗ","ơ","ờ","ớ","ợ","ở","ỡ","ồ","ò",
					"ù","ú","ụ","ủ","ũ","ư","ừ","ứ","ự","ử","ữ",
					"ỳ","ý","ỵ","ỷ","ỹ",
					"đ",
					"À","Á","Ạ","Ả","Ã","Â","Ầ","Ấ","Ậ","Ẩ","Ẫ","Ă","Ằ","Ắ","Ặ","Ẳ","Ẵ",
					"È","É","Ẹ","Ẻ","Ẽ","Ê","Ề","Ế","Ệ","Ể","Ễ",
					"Ì","Í","Ị","Ỉ","Ĩ",
					"Ò","Ó","Ọ","Ỏ","Õ","Ô","Ồ","Ố","Ộ","Ổ","Ỗ","Ơ","Ờ","Ớ","Ợ","Ở","Ỡ",
					"Ù","Ú","Ụ","Ủ","Ũ","Ư","Ừ","Ứ","Ự","Ử","Ữ",
					"Ỳ","Ý","Ỵ","Ỷ","Ỹ",
					"Đ",
					"'"];
	$marReplace	=	["a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a",
					"e","e","e","e","e","e","e","e","e","e","e","e",
					"i","i","i","i","i","i",
					"o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o",
					"u","u","u","u","u","u","u","u","u","u","u",
					"y","y","y","y","y",
					"d",

					"A","A","A","A","A","A","A","A","A","A","A","A","A","A","A","A","A",
					"E","E","E","E","E","E","E","E","E","E","E",
					"I","I","I","I","I",
					"O","O","O","O","O","O","O","O","O","O","O","O","O","O","O","O","O",
					"U","U","U","U","U","U","U","U","U","U","U",
					"Y","Y","Y","Y","Y",
					"D",
					""];
                    
	return str_replace($marSearch, $marReplace, $str);
}


/**
 * to_slug()
 * Tạo ra một bí danh(alias) cho tên
 * @param mixed $str
 * @return alias
 */
function to_slug($str) {
    
    $str = trim(mb_strtolower($str));
    $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
    $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
    $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
    $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
    $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
    $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
    $str = preg_replace('/(đ)/', 'd', $str);
    
    //Riêng ký tự ' thì ko replace thành rỗng
    $str    =   str_replace("'", "-", $str);
    
    $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
    $str = preg_replace('/([\s]+)/', '-', $str);
    
    for ($i = 1; $i <= 5; $i++) {
        $str    =   str_replace('--', '-', $str);
    }
    
    return $str;
}


/**
 * remove_special_char()
 * Remvoe các ký tự đặc biệt
 * @param mixed $string
 * @return
 */
function remove_special_char($string) {
    return  preg_replace('/[^a-zA-Z0-9\s]/', '', $string);
}


/**
 * generate_data_search()
 * Remove cụm text thành ko dấu để cho vào các trường search data
 * @param mixed $string
 * @return
 */
function generate_data_search($string) {
    return mb_strtolower($string . ' ' . remove_special_char(removeAccent($string)));
}


/**
 * removeHTML()
 * 
 * @param mixed $string
 * @param bool $keytab
 * @return
 */
function removeHTML($string, $keytab = true){
	$string = preg_replace ('/<script.*?\>.*?<\/script>/si', ' ', $string); 
	$string = preg_replace ('/<style.*?\>.*?<\/style>/si', ' ', $string); 
	$string = preg_replace ('/<.*?\>/si', ' ', $string); 
	$string = str_replace ('&nbsp;', ' ', $string);
	$string = mb_convert_encoding($string, "UTF-8", "UTF-8");
	if($keytab) $string = str_replace (array(chr(9),chr(10),chr(13)), ' ', $string);
    $string =   trim($string);
	for($i = 0; $i <= 5; $i++) $string = str_replace ('  ', ' ', $string);
    
	return $string;
}


/**
 * removeJS()
 * 
 * @param mixed $string
 * @return
 */
function removeJS($string) {
    $string =   preg_replace ('/<script.*?\>.*?<\/script>/si', ' ', $string);
    return $string;
}


/**
 * removeLink()
 * 
 * @param mixed $string
 * @return
 */
function removeLink($string, $keep_owner = true){
	$string =   preg_replace('#<a.*?>(.*?)</a>#is', '\1', $string);
    
	return $string;
}


/**
 * removeOutsideLink()
 * Remove link/ảnh dẫn đến các page khác
 * @param mixed $string
 * @return string
 */
function removeOutsideLink($string, $domain) {
    
    $string =   preg_replace('#<img [^>]*\bsrc=([\'"])http.?://((?<!' . $domain . ')[^\'"])+\1 *.*?>#i', '', $string);
    
    return $string;
}


/**
 * Replace dau nhay trong textbox
 */
function replaceQuot($string){
	$string = str_replace('\"', '"', $string);
	$string = str_replace("\'", "'", $string);
	$string = str_replace("\&quot;", "&quot;", $string);
	$string = str_replace("\\\\", "\\", $string);
   
	$arrSearch	= array('<', '>', '\"', '"');
	$arrReplace	= array('&lt;', '&gt;', '&quot;', '&quot;');
	$string = str_replace($arrSearch, $arrReplace, $string);
   
	return $string;
}


/**
 * Ham remove cac ky hieu <,> cua tag HTML
 */
function htmlspecialTag($str){
	$arrSearch	= array('<', '>', '"');
	$arrReplace	= array('&lt;', '&gt;', '&quot;');
	$str = str_replace($arrSearch, $arrReplace, $str);
	return $str;
}


/**
 * Ham remove ky tu dac biet
 */

function replaceFCK($string, $type=0){
	$array_fck	= array ("&Agrave;", "&Aacute;", "&Acirc;", "&Atilde;", "&Egrave;", "&Eacute;", "&Ecirc;", "&Igrave;", "&Iacute;", "&Icirc;",
								"&Iuml;", "&ETH;", "&Ograve;", "&Oacute;", "&Ocirc;", "&Otilde;", "&Ugrave;", "&Uacute;", "&Yacute;", "&agrave;",
								"&aacute;", "&acirc;", "&atilde;", "&egrave;", "&eacute;", "&ecirc;", "&igrave;", "&iacute;", "&ograve;", "&oacute;",
								"&ocirc;", "&otilde;", "&ugrave;", "&uacute;", "&ucirc;", "&yacute;",
								);
	$array_text	= array ("À", "Á", "Â", "Ã", "È", "É", "Ê", "Ì", "Í", "Î",
								"Ï", "Ð", "Ò", "Ó", "Ô", "Õ", "Ù", "Ú", "Ý", "à",
								"á", "â", "ã", "è", "é", "ê", "ì", "í", "ò", "ó",
								"ô", "õ", "ù", "ú", "û", "ý",
								);
	if($type == 1) $string = str_replace($array_fck, $array_text, $string);
	else $string = str_replace($array_text, $array_fck, $string);
	return $string;
}


/**
 * round_number()
 * 
 * @param mixed $number
 * @return
 */
function round_number($number) {
   $value   =  round($number / 1000) * 1000;
   
   return $value;
}


/**
 * format_number()
 * 
 * @param mixed $number
 * @param integer $num_decimal
 * @param string $split
 * @return
 */
function format_number($number, $num_decimal = 2, $split = ".", $remove_decimal = true){
    
    //Remove dấu , của số đi
    if ($remove_decimal) {
        $number =   floatval(str_replace(',', '', $number));
    }
    
	$break_thousands	=	$split;
	$break_retail		=	($split == "." ? "," : ".");
	$return    =   number_format($number, $num_decimal, $break_thousands, $break_retail);
	$stt	= -1;
	for($i = $num_decimal; $i > 0; $i--){
		$stt++;
		if(intval(substr($return, -$i, $i)) == 0){
			$return = number_format($number, $stt, $break_thousands, $break_retail);
			break;
		}
	}
	return $return;
}


/**
 * replace_keyword()
 * Xoa cac ky tu nguy hiem cua tu khoa search
 * @param mixed $keyword
 * @param integer $lower
 * @return string $keyword
 */
function replace_keyword($keyword, $lower = true){
	
    if ($lower) $keyword   =   mb_strtolower($keyword, "UTF-8");
    
    //Remove các ký tự phá ngoặc SQL    
	$keyword   =   replaceMQ($keyword);
    
    //Các ký tự sẽ bị xóa khỏi keyword
    $arrRep     =   array("'", '"', "-", "+", "=", "*", "?", "/", "!", "~", "#", "@", "%", "$", "^", "&", "(", ")", ";", ":", "\\", ".", ",", "[", "]", "{", "}", "‘", "’", '“', '”');
    $keyword    =   str_replace($arrRep, " ", $keyword);
    
    //Xóa các dấu cách đôi thành dấu cách đơn
    for ($i = 1; $i < 5; $i++) {
        $keyword    =   str_replace("  ", " ", $keyword);
    }
    
	return trim($keyword);
}


/**
 * generate_array_keyword()
 * Tach keyword ra thanh mang chua cac tu khoa rieng le
 * @param string $keyword
 * @return $array_keyword = ['tu', 'khoa', 'tim', 'kiem']
 */
function generate_array_keyword($keyword = "", $max_word = 10){
	
    $array_keyword  =   [];
    
    /** --- Xóa các ký tự ko cho phép --- **/
    //$keyword    =   replace_keyword($keyword);
    
    //Tìm kiếm cả ko dấu
    $keyword_kodau  =   removeAccent($keyword);
	
    //Nếu chuỗi ko dấu khác với chuỗi gốc thì mới nối vào
    if ($keyword_kodau != $keyword) $keyword    =   $keyword . " " . $keyword_kodau;
	
    //Bẻ chuỗi
    $break  =   explode(' ', $keyword);
    $i  =   0;
    foreach ($break as $word) {
        $word   =   trim($word);
        //Chỉ tìm kiếm với các từ có từ 2 chữ cái trở lên
        if (mb_strlen($word, 'UTF-8') > 1) {
            $array_keyword[]    =   $word;
            $i++;
            //Từ khóa tìm kiếm tối đa là 10 thôi
            if ($i == 10)   break;
        }
    }
	
    return $array_keyword;
}


/**
 * cutstring()
 * Cat mot chuoi theo so luong ky tu
 * @param mixed $str
 * @param mixed $length
 * @param string $char: Ky tu noi them vao phan bi cat di
 * @return string
 */
function cutstring($str, $length, $char = "..."){
    $strlen =   mb_strlen($str, "UTF-8");
	if($strlen <= $length) return $str;
    
    $substr =   mb_substr($str, 0, $length, "UTF-8");
	$substr    =   trim($substr) . $char;
    
    return $substr;
}


/**
 * alert()
 * 
 * @param string $str
 * @return void
 */
function alert($str = ""){
   header('Content-Type: text/html; charset=utf-8');
   echo  '<script> alert("' . $str . '"); </script>';
}


/**
 * get_domain_name()
 * Lay ten mien cua URL (Ten mien chinh)
 * @param mixed $url (VD https://sub.ten.domain.com)
 * @param bool $tld: Co lay subdomain hay ko
 * @return string domain.com
 */
function get_domain_name($url, $tld = false) {
    $pieces = parse_url($url);
    $domain = isset($pieces['host']) ? $pieces['host'] : '';
    if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $m)) {
        return ($tld === true) ? substr($m['domain'], ($pos = strpos($m['domain'], '.')) !== false ? $pos + 1 : 0) : $m['domain'];
    }
    
    return '';
}


/**
 * get_root_path()
 * Lay thu muc root cua domain ()
 * @return root path: D:/xampp/htdocs/ProjectName/
 */
function get_root_path() {
    $root_path  =   $_SERVER['DOCUMENT_ROOT'];
    
    if (substr($root_path, -1) != '/')  $root_path  .=  '/';
    
    return $root_path;
}


/**
 * save_log().
 *
 * @param mixed $filename (Bao gom extension)
 * @param mixed $content
 *
 * @return void
 */
function save_log($filename, $content, $info = true)
{
    $log_path   =  $_SERVER["DOCUMENT_ROOT"] . '/log/';
    
    $file_path  =  $log_path . $filename;

    $handle  =   @fopen($file_path, "a");
    //Nếu ko được thì mở thêm ../
    if (!$handle) {
        $handle = @fopen($_SERVER["DOCUMENT_ROOT"] . '/../log/' . $filename, "a");
    }

    //Nếu 2 lần mà ko mở được thì exit
    if (!$handle) {
        die('Cannot save log!');
    }

    if ($info) {
        $content =  date("d/m/Y H:i:s") . " " . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"] . "\n" . "IP:" . @$_SERVER['REMOTE_ADDR'] . "\n" . $content;
        if (!empty($_SERVER['HTTP_REFERER'])) {
            $content    .=  "\nReferer: " . $_SERVER['HTTP_REFERER'];
        }
    }

    fwrite($handle, $content . "\n=======================================================\n");
    fclose($handle);
}


/**
 * create_file()
 * Tao mot file tren he thong
 * @param mixed $file_name: Bao gom ca extension + [Path]
 * @param mixed $content
 * @return void
 */
function create_file($file_name, $content) {
    $path       =   $_SERVER["DOCUMENT_ROOT"];
    $path_file  =   $path . $file_name;
    
    $handle =   fopen($path_file, "w+");
	//Nếu ko được thì mở thêm lần nữa
	if (!$handle) $handle =   fopen($path_file, "w+");
    
    //Nếu 2 lần mà ko mở được thì exit
	if (!$handle) save_log('error_create_file.cfn', 'File error: ' . $path_file);
    
    fwrite($handle, $content);
    fclose($handle);
}


/**
 * redirect_url()
 * Redirect URL binh thuong
 * @param mixed $url
 * @return void
 */
function redirect_url($url){
	header( "Location: " . $url);
    exit();
}


/**
 * redirect_parent()
 * Redirect URL khi đang mở thickbox
 * @param mixed $url
 * @return void
 */
function redirect_parent($url){
	echo    '<script>window.top.location.href = "' . $url . '";</script>';
    exit();
}


/**
 * redirect301()
 * Redirect URL 301
 * @param mixed $url
 * @return void
 */
function redirect301($url) {
    header( "Location: " . $url, 301);
    exit();
}


/**
 * redirect_correct_url()
 * Redirect các URL sai về URL đúng trong các trường hợp đối tượng bị thay đổi URL do đổi tên hoặc fake URL
 * @param mixed $url
 * @return void
 */
function redirect_correct_url($url) {
    if (!empty($_SERVER['REQUEST_URI'])) {
        
        //DOMAIN_WEB bắt buộc phải ko có '/' ở cuối
        $full_url   =   DOMAIN_WEB . $_SERVER['REQUEST_URI'];
        $exp    =   explode('?', $full_url, 2);
        if (trim($exp[0]) != trim($url)) {
            save_log('redirect_301.cfn', $full_url);
            redirect301($url . (isset($exp[1]) ? '?' . $exp[1] : ''));
        }
    }
}


/**
 * dump()
 * Dump data de test loi o local.
 *
 * @param mixed $data
 *
 * @return
 */
function dump($data)
{

    if (!is_dev()) {
        return false;
    }

    $name       = "";
    $back_track = debug_backtrace();
    $caller     = array_shift($back_track);
    foreach ($GLOBALS as $var_name => $value) {
        if ($value === $data) {
            $name = $var_name;
            break;
        }
    }

    echo '<pre style="position: relative;float: left; z-index: 99999; background: black; color: #FFF; width: 100%; max-height: 600px; overflow: auto; padding: 5px; border-top: 3px solid #d31a1a;">';
    echo '<span style="display:block; text-align: center; background: #D6D61F; font-weight: 600; color: #111;">DUMP IN (' . $caller['file'] . ' -- line: ' . $caller['line'] . ')</span>';
    //echo "<span style='display:block; text-align: center;font-weight: 600;padding: 4px 0px;color: #00B8FF;'>$" . $name . "</span>";

    switch (gettype($data)) {
        case "boolean":
        case "object":
            var_dump($data);
            break;

        case "array":
            print_r($data);
            break;

        default:
            echo $data;
            break;
    }

    echo '</pre>';
}


/**
 * dd()
 * Dump and exit
 * @param mixed $data
 * @return void
 */
function dd($data) {
    dump($data);
    exit;
}


/**
 * get_current_page()
 * Lay trang hien tai
 * @param string $param
 * @return integer $current_page
 */
function get_current_page($param = 'page') {
    $current_page   =   getValue($param);
    if ($current_page < 1)  $current_page   =   1;
    if ($current_page > 9999)   $current_page   =   9999;
    
    return $current_page;
}

/**
 * generate_pagebar()
 * Generate HTML cua pagebar
 * @param integer $total_record
 * @param integer $page_size
 * @return
 */
function generate_pagebar($total_record, $page_size = 12, $param_remove = [], $show_total = true) {
    
    $html_page  =   '';
    
    if ($total_record > 0) {
        
        $total_page     =   ceil($total_record / $page_size);
        $page_current   =   get_current_page();
        
        $html_page  .=  '<div class="pagination moderm-pagination" id="moderm-pagination" data-layout="normal">';
        
        if ($total_page > 1) {
    
            $page_start =   $page_current - 2;
            if ($page_start < 1)    $page_start =   1;
    
            //Lấy URL đươc loại bỏ param page
            $url    =   get_url(array_merge($param_remove, ['page']));
            $symbol =   get_url_symbol_query($url);
            
            $html_page  .=   '<ul class="page-numbers">';
    
            //Nếu trang hiện tại > 1 thì mới hiện nút "Previous"
            if ($page_current > 1) {
                $html_page  .=  '<li class="page_first">
                                    <a href="' . $url . ($page_current > 2 ? $symbol . 'page=' . ($page_current - 1) : '') . '" class="page-numbers prev"><i class="fas fa-angle-left"></i></a>
                                </li>';
            }
            
            //2 Trang liền trước của trang hiện tại
            for ($i = $page_start; $i < $page_current; $i++) {
                $html_page  .=  '<li>
                                    <a href="' . $url . ($i >= 2 ? $symbol . 'page=' . $i : '') . '" class="page-numbers">' . $i . '</a>
                                </li>';
            }
            
            //Trang hiện tại
            $html_page  .=  '<li>
                                <a href="javascript:;" class="page-numbers current">' . $page_current . '</a>
                            </li>';
            
            $url    .=  $symbol;
            //2 Trang liền sau của trang hiện tại
            $next_2_page    =   $page_current + 2;
            if ($next_2_page > $total_page) $next_2_page  =   $total_page;
            for ($i = $page_current + 1; $i <= $next_2_page; $i++) {
                $html_page  .=  '<li>
                                    <a href="' . $url . 'page=' . $i . '" class="page-numbers">' . $i . '</a>
                                </li>';
            }
    
            //Nếu trang hiện tại nhỏ hơn tổng số trang thì mới hiện nút Next
            if ($page_current < $total_page) {
                $html_page  .=  '<li>
                    				<a href="' . $url . 'page=' . ($page_current + 1) . '" class="page-numbers next"><i class="fas fa-angle-right"></i></a>
                    			</li>';
            }
    
            $html_page  .=  '</ul>';
            
        }
        
        if ($show_total)    $html_page  .=  '<span class="count-string">Có ' . format_number($total_record) . ' kết quả được tìm thấy</span>';
        $html_page  .=  '</div>';
    }
    
    //Return HTML
    return $html_page;
}


/**
 * encode_base_json()
 * Encode mot array thanh chuoi Json
 * @param mixed $array
 * @return string json
 */
function encode_base_json($array = []) {
    
    if (!empty($array)) {
        $string  =  base64_encode(json_encode($array));
        return $string;
    }
  
    return '';
}


/**
 * decodeBaseJson()
 * Decode 1 chuoi json thanh array
 * @param mixed $string
 * @return array
 */
function decode_base_json($string) {
      
    if ($string != '') {
    
        $string  =  base64_decode($string);
        $return  =  json_decode($string, true);
        
        if (is_array($return)) {
            return $return;
        }
    }
    
    return [];
}


/**
 * validate_name()
 * Validate Ten
 * @param mixed $name
 * @return boolean
 */
function validate_name($name)
{

    $name   =   removeAccent($name);

    //Tối đa 32 ký tự
    if (trim($name) == '' || strlen($name) > 32) {
        return false;
    }

    if (!preg_match("/^[a-zA-Z\s]*$/", $name)) {
        return false;
    }

    return true;
}

/**
 * validate_email()
 * Validate email
 * @param mixed $email
 * @return boolean
 */
function validate_email($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    
    return true;
}

/**
 * validate_phone()
 * Validate so DT
 * @param mixed $phone
 * @return boolean
 */
function validate_phone($phone) {
    
    if (!preg_match("/^(\+)?([0-9 \.\-]{10,15})$/", $phone)) {
		return false;
	}
    
    return true;
}

/**
 * validate_password()
 * Validate mat khau
 * @param mixed $password
 * @param integer $min_char
 * @return Boolean
 */
function validate_password($password, $min_char = 6) {
    if (mb_strlen($password, 'UTF-8') < $min_char) {
        return false;
    }
    
    return true;
}


/**
 * convert_phone_number()
 * Convert số ĐT từ đầu 0 => 84 và ngược lại
 * @param mixed $phone
 * @param string $type: 0 OR 84
 * @return string $phone
 */
function convert_phone_number($phone, $type = '0') {
    
    $phone  =   clear_phone_number($phone);
        
    //Đầu tiên quy về dạng 0xxxxxx
    $phone  =   preg_replace('/^84/', '0', $phone);
    
    if ($type == '0') {
        return $phone;
    } else {
        $phone  =   '84' . (int)$phone;
    }
    
    return $phone;
}

/**
 * clear_phone_number()
 * Clear các ký tự đặt biệt của số ĐT
 * @param mixed $phone
 * @return
 */
function clear_phone_number($phone)
{
    $char_remove    =   ['.', '-', ' ', '+'];
    foreach ($char_remove as $char) {
        $phone  =   str_replace($char, '', $phone);
    }

    return $phone;
}

/**
 * get_microtime()
 * Lay thoi gian hien tai de tinh toan thoi gian load trang
 * @return
 */
function get_microtime() {
    list($usec, $sec) =  explode(" ", microtime());
    
    return ((float)$usec + (float)$sec);
}


/**
 * show_time_load()
 * Hiển thị thời gian load trang
 * @param mixed $time
 * @return
 */
function show_time_load($time) {
    return '<p class="time_load">Time: ' . $time . '</p>';
}

/**
 * get_extension()
 * Lay duoi file
 * @param mixed $filename
 * @return string
 */
function get_extension($filename){
    $ext =   substr($filename, strrpos($filename, ".") + 1);
    return	strtolower($ext);
}


/**
 * check_ratio_image()
 * 
 * @param mixed $width
 * @param mixed $height
 * @param string $ratio
 * @return
 */
function check_ratio_image($width, $height, $ratio = '3:2') {
    if (!empty($ratio)) {
        $exp    =   explode(':', $ratio);
        $w  =   (int)$exp[0];
        $h  =   (int)$exp[1];
        if ($h > 0) {
            $ratio_require  =   round($w / $h, 2);  //Tỷ lệ được tính ra số thập phân
            
            //Tính tỷ lệ thực tế của ảnh
            $ratio_actual   =   round($width / $height, 2);
            if (abs($ratio_actual - $ratio_require) > 0.005) {
                return false;
            }
        }
    }
    
    return true;
}


/**
 * @param int $bytes Number of bytes (eg. 25907)
 * @param int $precision [optional] Number of digits after the decimal point (eg. 1)
 * @return string Value converted with unit (eg. 25.3KB)
 */
function format_bytes($bytes, $precision = 2) {
    $unit = ["B", "KB", "MB", "GB"];
    $exp = floor(log($bytes, 1024)) | 0;
    return round($bytes / (pow(1024, $exp)), $precision).$unit[$exp];
}


/**
 * set_session_toastr()
 * Tạo ra biến session dùng cho toastr
 * @param string $name
 * @param string $value
 * @return alias
 */
function set_session_toastr($name = 'result', $value = 'success') {
    $_SESSION[$name]    =   $value;
}


/**
 * toastr()
 * Show toastr của JS
 * @param string $msg_success
 * @param string $msg_fail
 * @param string $session_name
 * @param string $session_value
 * @return
 */
function toastr($msg_success = 'Cập nhật thành công', $msg_fail = 'Cập nhật không thành công', $session_name = 'result', $session_value = 'success') {
    
    $toast  =   '';
    
    if (isset($_SESSION[$session_name])) {
        $toast  .=  '<script>';
        if ($_SESSION[$session_name] == $session_value) {
            $toast  .=  'toastr.success("' . $msg_success . '");';
        } else {
            $toast  .=  'toastr.error("' . $msg_success . '");';
        }
        $toast  .=  '</script>';
        unset($_SESSION[$session_name]);
    }
    
    return $toast;
}


/**
 * generate_checkbox_icon()
 * Generate ra icon cua truong checkbox.
 *
 * @param mixed $value
 * @return str icon
 */
function generate_checkbox_icon($value)
{
    $icon   =   '<i class="' . (1 == $value ? 'fas fa-check-square' : 'far fa-square') . '"></i>';

    return $icon;
}


/**
 * generate_checkbox_icon()
 * Generate ra option chọn tháng của số năm truyền vào
 *
 * @param mixed $y_next
 * @param mixed $time
 * @return 
 */
function generate_optgroup_month($y_next = 4, $time = CURRENT_TIME) {
    $value_select = date('Y-n');
	//Tạm bỏ đi để cho đội content copy giá từ 2021
    //$y_now = date('Y', $time);
    $y_now  =   2022;
    $y_next =   $y_now + $y_next;
    
	//$m_now = date('m', $time);

	$html_char = [];
	for ($i = $y_now; $i <= $y_next; $i++) { 
		$html_char[] = "<optgroup label=\"Năm $i\">";
		for ($i2 = 1; $i2 <= 12; $i2++) {
            //if ($i == 2021 && $i2 < 12) continue;
			$html_char[] = "<option value=\"$i-$i2\"". ($value_select == "$i-$i2" ? 'selected' : '') ." >Tháng $i2/$i</option>";
		}
		//if($m_now != 1) $m_now = 1;
		$html_char[] = "</optgroup>";
	}
	return implode('', $html_char);
}


/**
 * generate_month_select()
 * 
 * @param string $current_month
 * @param integer $year_next
 * @return
 */
function generate_month_select($current_month = '', $year_next = 2) {
    if (empty($current_month))  $current_month  =   date('m/Y');
    $y_now      =   2023;
    $y_end      =   $y_now + $year_next;
    $html_char  =   [];
	for ($year = $y_now; $year <= $y_end; $year++) { 
		$html_char[] = "<optgroup label=\"Năm $year\">";
		for ($month = 1; $month <= 12; $month++) {
            if ($month < 10)    $month  =   '0' . $month;
			$html_char[] = "<option value=\"$month/$year\"". ($current_month == "$month/$year" ? 'selected' : '') ." >Tháng $month/$year</option>";
		}
		//if($m_now != 1) $m_now = 1;
		$html_char[] = "</optgroup>";
	}
	return implode('', $html_char);
}


/**
 * array_get()
 * Lấy value trong array theo key tryền vào
 * @param array $input Mảng chứa dữ liệu
 * @param string $key Tên index cần lấy trong mảng, lấy value trong mảng đa triều index cách nhau bằng dấu . (key.key2)
 * @param mixed $default Giá trị mặc định khi không tìm thấy index chỉ định
 * @return mixed Giá trị của index
 */
function array_get($input, $key = null, $default = null) {

    if (is_null($key)) {
        return $input;
    }

    $arr = explode('.', $key);
    foreach ($arr as $k) {
        $input = isset($input[$k]) ? $input[$k] : null;
    }

    if (is_null($input)) {
        return $default;
    }

    return $input;
}


/**
 * show_money()
 * 
 * @param mixed $money
 * @param string $symbol
 * @return
 */
function show_money($money, $symbol = '₫') {
    return format_number($money) . '' . $symbol;
}


/**
 * get_client_ip()
 * Get Client IP address
 * @return string IP
 */
function get_client_ip()
{
    $ipaddress = 'N/A';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    
    $exp    =   explode(',', $ipaddress);
    
    return trim($exp[0]);
}


/**
 * is_google_ip()
 * Check xem IP có phải của Google bot ko
 * @return bool
 */
function is_google_ip() {
    
    $ip =   get_client_ip();
    $list   =   ['66.249'];
    $exp    =   explode('.', $ip);
    
    if (isset($exp[1])) {
        return in_array($exp[0] . '.' . $exp[1], $list);
    }
    
    return false;
}

/**
 * is_bot()
 * 
 * @return
 */
function is_bot() {
    if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|curl|dataprovider|search|get|spider|find|java|majesticsEO|google|yahoo|teoma|contaxe|yandex|libwww-perl|facebookexternalhit/i', $_SERVER['HTTP_USER_AGENT'])) {
        return true;
    }
    return false;
}


/**
 * generate_navbar()
 * Generate html cua breadcrum
 * @param mixed $arr_link
 * @return html
 */
function generate_navbar($arr_link, $hide_last = false) {
    
    $html   =   '<ul>';
    $html   .=  '<li>
                    <a href="' . DOMAIN_WEB . '" title="Trang chủ">Trang chủ</a>
                    <i class="fas fa-chevron-right"></i>
                </li>';
    if ($hide_last) array_pop($arr_link);
    
    if (!empty($arr_link)) {
        foreach ($arr_link as $item) {
            $html   .=  '<li>';
            if (!empty($item['link'])) {
                $html   .=  '<a href="' . $item['link'] . param_box_web(35, true) . '" title="' . $item['text'] . '">' . $item['text'] . '</a>';
            } else {
                $html   .=  '<span>' . $item['text'] . '</span>';
            }
            $html   .=  '<i class="fas fa-chevron-right"></i>';
            $html   .=  '</li>';
        }
        
    }
    $html   .=  '</ul>';
    return $html;
}


/**
 * response()
 * Mẫu response chung của API
 *
 * @param int $code Mã code thông báo
 * @param void $data Dữ liệu trả về cho client
 * @param bool $error Có phải chế độ in lỗi k
 * @param bool $exit Dừng process hay return
 *
 * @return void
 */
function response($data = [], $code = REQUEST_SUCCESS, $exit = true)
{
    $output = [
        "ResponseCode"    => $code,
        "RequestTime"     => CURRENT_TIME
    ];

    if ($code === REQUEST_ERROR) {
        $output["Errors"]   = $data;
    } else {
        $output["Data"]     = $data;
    }

    if ($exit) {
        // http_response_code($code);
        echo json_encode($output);
        exit;
    } else {
        return $output;
    }
}


/**
 * calculate_percent()
 * Tinh phan tram cua 2 so
 * @param mixed $number
 * @param mixed $total
 * @param integer $length
 * @return
 */
function calculate_percent($number, $total, $length = 2) {
    if ($total > 0) {
        return round($number * 100 / $total, $length);
    }

    return 0;
}


/**
 * getIntegerArrayID()
 * Generate ra mảng chứa các ID từ mảng ID ban đầu để tránh bị fake lỗi injection
 * @param mixed $array_id
 * @return [id]
 */
function getIntegerArrayID($array_id) {
    
    $ids    =   [];
    
    if (!empty($array_id)) {
        foreach ($array_id as $id) {
            $id =   (int)$id;
            if ($id > 0)    $ids[]  =   $id;    //Tránh lỗi dữ liệu do fake injection
        }
    }
    
    //Return
    return $ids;
}


/**
 * get_number()
 * 
 * @param mixed $value
 * @return
 */
function get_number($value)
{
    return str_replace(',', '', $value);
}


?>
