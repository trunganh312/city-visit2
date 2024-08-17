<?
/**
 * Class GenerateQuery
 * Created by SenEnter
 */
class GenerateQuery extends Model {
    
	private	$array_data_field           =	[];
	private	$array_data_value           =	[];
	private	$array_data_type            =	[];
	private	$array_data_from            =	[];
	private	$array_data_default_value	=	[];
	private	$array_data_require			=	[];
	private	$array_msg_error_require	=	[];
	private	$array_data_unique			=	[];
	private	$array_msg_error_unique		=	[];
	private	$number_of_field            =	-1;
	private	$table_name;
	private	$removeTagHTML              =   true;  //Có remove các thẻ HTML hay ko
    private $action_name                =   'action';   //Tên của biên hidden submit form
    private $submitted                  =   'submitted';    //Giá trị của biến hidden submit form


    /**
     * GenerateQuery::__construct()
     *
     * @param mixed $table
     * @return void
     */
    function __construct($table) {
        
        parent::__construct();
        $this->table_name   =	$table;
        
    }

	/**
     * GenerateQuery::add() - Ham thuc hien gan ten cac truong (field) se duoc add vao cau query
	 * $field_name : Ten truong duoc gan
	 * $field_value : Ten control lay tu form hoac ten bien duoc lay tu file
	 * $data_type : Kieu du lieu (0: string, 1: integer, 2: double, 3: email)
	 * $default_value : Gia tri mac dinh
	 * $msg_err_require : Loi duoc dua ra man hinh neu khong nhap trong truong hop require = 1
	 * $msg_err_unique : Loi dua ra man hinh neu khong cho phep trung du lieu ($unique = 1)
	 */

	function add($field_name, $data_type, $default_value, $msg_error_require = "", $msg_error_unique = "") {
	   
        $field_name =   trim($field_name);
		//Kiem tra xem co truong nao duoc add 2 lan khong
		if(in_array($field_name, $this->array_data_field))	$this->error[] =   "Trường <b>" . $field_name . "</b> đã được thêm 2 lần";
        
        /** Ép kiểu string để tránh NULL khi đưa vào mảng sẽ bị rỗng **/
        if ($data_type == DATA_STRING) {
            $default_value  =   (string)$default_value;
        }
        
		//Gan cac gia tri vao cac array de dua vao cau lenh truy van
		$this->number_of_field++;
		$i	=	$this->number_of_field;
		$this->array_data_field[$i]             =	$field_name;
		$this->array_data_type[$i]              =	$data_type;
		$this->array_data_default_value[$i]     =	$default_value;
		$this->array_msg_error_require[$i]      =	$msg_error_require;
		$this->array_msg_error_unique[$i]       =	$msg_error_unique;
        
        return $this;
	}

    /**
     * GenerateQuery::setRemoveHTML()
     * Set class co remove cac the HTML cua cac truong du lieu khi submit form hay ko
     * @param boolean $remove
     * @return void
     */
    function setRemoveHTML($remove) {
        $this->removeTagHTML    =   $remove;
    }

    /**
     * GenerateQuery::checkError()
     * Check xem các dữ liệu truyền vào có hợp lệ hay ko
     * @return boolean
     */
    function validate($field_name = "", $record_id = 0) {

        //Kiểm tra các lỗi
        $this->checkData($field_name, $record_id);

        if (empty($this->error)) {
            return true;
        }

        return false;
    }


	/**
	 * GenerateQuery::checkTable()
	 * Check xem trường được add vào có tồn tại trong bảng dữ liệu hay ko
	 * @return Cac loi duoc gan thang truc tiep vao $this->error
	 */
	private function checkTable(){

        $table   =  $this->table_name;

        //Kiem tra xem cac truong trong cau lenh query co ton tai trong table ko
		$arr_field_table	=	array();
		$data =   $this->DB->query("SHOW COLUMNS FROM " . $table)->toArray();
		foreach($data as $row){
			$arr_field_table[]   =   $row['Field'];
		}

		foreach($this->array_data_field as $key => $value){
			if(!in_array($value, $arr_field_table)) $this->error[]   =   "Trường <b>" . $value . "</b> không tồn tại trong bảng <b>" . $table . "</b>";
		}

        //return $this->error;
	}


 	/**
 	 * GenerateQuery::removeHtmlSpecialTag()
 	 * Ham remove cac ky hieu <,> cua tag HTML neu removeTagHTML = true
 	 * @param mixed $str
 	 * @return
 	 */
 	private function removeHtmlSpecialTag($str){
 		$arrSearch	= array('<', '>', '"');
		$arrReplace	= array('&lt;', '&gt;', '&quot;');
		$str = str_replace($arrSearch, $arrReplace, $str);

		return $str;
 	}

	/**
     * GenerateQuery::generateVariable(){
	 * Ham tao ra cac bien tu cac gia tri truyen vao trong ham add (field)
     * VD: add 1 field la 'name' thi tao ra mot bien la $name co gia tri mac dinh hoac gia tri duoc lay tu form
     * @param $row: Truyen vao ban ghi trong truong hop sua thong tin record
	 */
    function generateVariable($row = []){

        //Đầu tiên là chuyển các field sang tên biến (trong trường hợp sửa thông tin bản ghi)
        if (!empty($row)) {
            foreach ($row as $key => $value) {
                global $$key;
                $$key   =   $value;
            }
        }

        //Với các biến được lấy dữ liệu từ form lên thì ưu tiên lấy giá trị từ form hơn
		for ($i = 0; $i <= $this->number_of_field; $i++){
			$temp = $this->array_data_field[$i];

			global $$temp;
            
			if (isset($_POST[$temp])) {
                $$temp = $_POST[$temp];
			} else {
			    if (isset($row[$temp])) {
                    $$temp  =   $row[$temp];
                } else {
                    $$temp = $this->array_data_default_value[$i];
                }
			}
		}
	}


    /**
     * GenerateQuery::generateQueryInsert()
     * Tạo ra câu SQL insert 1 bản ghi từ các dữ liệu được truyền vào từ hàm add()
     * @return string SQL insert
     */
    function generateQueryInsert(){

        $str_return =	"";
        $str_field  =	implode(",", $this->array_data_field);
        $str_value  =	"";

 		for($i = 0; $i <= $this->number_of_field; $i++){

 			//Lấy giá trị của field
            $value  =   $this->getValueOfField($i);

 			//Gán biến ban đầu chưa remove HTML để insert kiểu bài viết chi tiết
            $temp_value =  $value;

            //Neu remove HTML
 			if($this->removeTagHTML)	$value	=	$this->removeHtmlSpecialTag($value);

 			switch($this->array_data_type[$i]){

 				case DATA_STRING:	//Kieu string
                    $str_value	.=	"'" . $value . "',";
			 		break;

		 		case DATA_INTEGER:
                    $value      =   str_replace(',', '', $value);
                    $str_value	.=	intval($value) . ",";  //Kieu Integer
			 		break;

		 		case DATA_TIME:
                    $str_value	.=	str_totime($value) . ",";  //Kieu Integer
			 		break;

                case DATA_DOUBLE:
                    $value      =   str_replace(',', '', $value);
                    $str_value	.=	doubleval($value) . ",";   //Kieu double
			 		break;

 			}
 		}

 		$str_value	=	substr($str_value, 0, strlen($str_value) - 1);

		//Câu lệnh insert
		$str_return	=	"INSERT INTO " . $this->table_name . "(" . $str_field . ") VALUES(" . $str_value . ")";

		return $str_return;
 	}


 	/**
 	 * GenerateQuery::generateQueryUpdate()
 	 * Tạo ra câu SQL update 1 record từ các dữ liệu được add từ hàm add()
 	 * @param mixed $field_name
 	 * @param mixed $field_value
 	 * @return string query update
 	 */
 	function generateQueryUpdate($field_name, $field_value){
 		$str_value	=	"";
 		$str_return	=	"";
        
 		for($i = 0; $i <= $this->number_of_field; $i++){
 			$str_value	.=	$this->array_data_field[$i] . " = ";

 			$value  =   $this->getValueOfField($i);

 			//Gán biến ban đầu chưa remove HTML để insert kiểu bài viết chi tiết
            $temp_value =  $value;
            
 			//Neu lua chon loai bo cac the html <,>
			if($this->removeTagHTML)	$value	=	$this->removeHtmlSpecialTag($value);

            //Gan vao cau lenh update
			switch($this->array_data_type[$i]){
				case DATA_STRING:   //Kieu string
                    $str_value	.=	"'" . $value . "',";
					break;

				case DATA_INTEGER:
                    $value      =   str_replace(',', '', $value);
                    $str_value	.=	intval($value) . ",";  //Kieu Integer
			 		break;

		 		case DATA_TIME:
                    $str_value	.=	str_totime($value) . ",";  //Kieu Integer
			 		break;

                case DATA_DOUBLE:
                    $value      =   str_replace(',', '', $value);
                    $str_value	.=	doubleval($value) . ",";   //Kieu double
			 		break;
			}	//end switch

 		}	//end for

		$str_value	=	substr($str_value, 0, strlen($str_value) - 1);
		//Cau lenh update
		$str_return	=	"UPDATE " . $this->table_name . " SET " . $str_value . " WHERE " . $field_name . " = " . $field_value . " LIMIT 1";

		return	$str_return;
 	}


    /**
     * GenerateQuery::checkData()
     * Check lỗi các dữ liệu truyền vào qua hàm add()
     * @param string $field_name
     * @param integer $record_id
     * @return Các lỗi (nếu có) được gán vào $error
     */
    function checkData($field_name = '', $record_id = 0){

        //Đầu tiên là check lỗi từ việc add các trường dữ liệu
        $this->checkTable();    //Khi gọi hàm này thì lỗi được gán vào $this->error
        
 		for($i = 0; $i <= $this->number_of_field; $i++){
            //Lay gia tri de check require and unique
 			$value  =   $this->getValueOfField($i);

 			//Kiem tra neu khong cho phep truong nay co gia tri rong hoac null
 			if($this->array_msg_error_require[$i] != ''){
                switch($this->array_data_type[$i]) {
                    case DATA_STRING:
                        //Trường hợp ko cho phép rỗng
                        if($value == "") {
                            $this->error[]  =   $this->array_msg_error_require[$i];
                        }
                        break;

                    case DATA_INTEGER:
                        //Truong hop integer ko cho phep nho hon mot gia tri mac dinh
                        if (intval($value) <= $this->array_data_default_value[$i]) {
                            $this->error[]  =   $this->array_msg_error_require[$i];
                        }
                        break;

                    case DATA_TIME:
                        //Trường hợp bắt buộc phải nhập thời gian
                        if (str_totime($value) < $this->array_data_default_value[$i]) {
                            $this->error[]  =   $this->array_msg_error_require[$i];
                        }
                        break;

                    case DATA_DOUBLE:
                        //Kieu double ko duoc phep nho hon gia tri mac dinh
                        if (doubleval($value) <= doubleval($this->array_data_default_value[$i])) {
                            $this->error[]  =   $this->array_msg_error_require[$i];
                        }
                        break;
                }

 			}

            //Nếu phải check trùng dữ liệu
 			if($this->array_msg_error_unique[$i] != ""){

                //Câu query select dữ liệu để check xem đã có bản ghi nào có giá trị như vậy chưa
                $sql_edit	=	"";

                //Remove magic quote
                $value   =  $this->removeMagicQuote($value);

 				//Neu trong truong hop sua record thi chi check trung voi cac record con lai

 				if($field_name != "" && $record_id > 0)	$sql_edit	.=	" AND " . $field_name . " <> " . $record_id;

 				$row   =   $this->DB->query("SELECT " . $this->array_data_field[$i] . "
                                                FROM	" . $this->table_name . "
												WHERE " . $this->array_data_field[$i] . " = '" . $value . "'" . $sql_edit . " LIMIT 1")
                                                ->getOne();
			 	if(!empty($row)){
			 		$this->error[]    =   $this->array_msg_error_unique[$i];
				}
 			}
 		}

        //Trả về lỗi check dữ liệu
 		//return	$this->error;
 	}


    /**
     * GenerateQuery::submitForm()
     *
     * @return
     */
    function submitForm() {
        $action =   getValue($this->action_name, 'str', 'POST', '');
        if ($action == $this->submitted) {
            return true;
        }
        return false;
    }


    /**
     * GenerateQuery::getValueOfField()
     * Lay ve gia tri cua 1 truong tu ham Add(), gia tri duoc lay tu form hoac tu bien
     * @param mixed $field_stt
     * @return Value of field
     */
    function getValueOfField($field_stt) {
        
        //Neu khong ton tai thi return '' luon
        if (!isset($this->array_data_default_value[$field_stt])
            || !isset($this->array_data_field[$field_stt])
            //|| !isset($this->array_data_from[$field_stt])
            )    return '';

        //Gán mặc định
        $value  =   $this->array_data_default_value[$field_stt];
        
        //Nếu có giá trị POST của Field thì lấy theo POST
		if(isset($_POST[$this->array_data_field[$field_stt]]))	$value	=	$_POST[$this->array_data_field[$field_stt]];
        
        //Remove JS
        $value  =   removeJS($value);
        
        //Remove magic quote
        $value  =   $this->removeMagicQuote($value);
        
        //Trim
        $value  =   trim($value);
        
        //Trả về giá trị của field
        return $value;
    }

    /**
     * GenerateQuery::removeMagicQuote()
     *
     * @param string $string
     * @return string
     */
    private function removeMagicQuote($string = "") {
        //remove quote;
        $string =   str_replace("\\", "", $string);
        $string =   str_replace("\'", "'", $string);
        $string =   str_replace("'", "''", $string);

        return $string;
    }

}
