<?

/**
 * Class DataTable
 * Created by SenEnter
 */

class DataTable
{

    private $arr_field          =   []; //Mảng lưu các trường dữ liệu được đưa vào class
    private $edit               =   false;  //Có tính năng sửa bản ghi hay ko
    private $delete             =   false;  //Có cho phép xóa bản ghi hay ko
    private $page_size          =   30;
    private $add_more_footer    =   []; //Nếu muốn hiển thị thêm tổng số nào đó ở footer của table (VD: Tổng tiền...)
    private $array_field_sum    =   []; //Ngoài tính tổng số bản ghi ra thì có 1 số trường hợp DS cần tính tổng theo field, VD tổng tiền
    private $edit_file_name     =   'edit.php';
    private $delete_file_name   =   'delete.php';
    private $active_file_name   =   '/module/common/active.php';
    private $array_field_active =   []; //Có nhiều module, danh sách có lựa chọn active ở nhiều file, nhiều trường khác nhau
    private $symbol_query       =   '?';    //Ký tự nối link Edit và ID...
    private $path_image         =   ''; //Đường dẫn lưu ảnh
    private $show_time_minute   =   false;   //Với các trường kiểu time thì có show chi tiết phút giay hay ko
    private $time_minute_format =   'H:i';
    private $show_minute_break  =   false;  //Cho hiển thị phút ở cùng 1 dòng hay cho xuống dòng.
    private $show_search_minute =   false;
    private $show_total_record  =   true;
    private $show_stt           =   true;

    private $total_record, $total_page, $field_id, $table, $current_page, $field_sort, $type_sort;

    public  $sql;       //Câu query tổng để lấy dữ liệu ra, ko có order by và limit
    public  $sql_table; //Câu SQL sẽ dùng để lấy dữ liệu ra chỉ để hiển thị của bảng (Bao gồm order by và limit)
    public  $sql_search;
    private $row_data;  //Mảng chứa data của bản ghi để hiện thị theo các trường dữ liệu trong các hàm ->show()
    private $search_data    =   [];   //Mảng lưu các field của form search
    private $html_form_search;  //Biến lưu đoạn HTML của form search
    private $field_multi_search;    //Có một số thông tin có thể tìm kiếm bằng nhiều trường, VD tìm thông tin KH có thể là tên, đt, email
    private $field_hidden       =   []; //Một số danh sách cần truyền thêm trường ẩn để khi submit form tìm kiếm ko bị chuyển sang danh sách khác (VD status=1)
    private $field_tooltip      =   [];
    private $edit_thickbox      =   []; //Một số listing cần thao tác khi click sửa thì mở popup
    private $add_html_search    =   ''; //Một số trường hợp cần add thêm field input vào form search nhưng input này ko thể tham gia trực tiếp vào query chính.
    private $add_html_footer    =   []; //Show them html o footer
    private $quick_menu         =   []; //Có các DS sẽ có thêm phần quicklink ở trên để tiện click luôn vào 1 danh sách lọc
    private $field_permission_log   =   '';
    private $show_view_log      =   true;
    private $DB;

    /**
     * DataTable::__construct()
     *
     * @param mixed $table
     * @param mixed $field_id
     * @return void
     */
    function __construct($table, $field_id)
    {

        $this->table    =   $table;
        $this->field_id =   $field_id;
        $this->DB       =   new Database;
    }


    /**
     * DataTable::column()
     * Dua cac column vao bang du lieu
     * @param mixed $field_name: Ten truong trogn bang, neu co sort va search thi ten truong bat buoc phai ching xac
     * @param mixed $label: Tieu de column
     * @param mixed $type: TAB_TEXT, TAB_SELECT...
     * @param bool $search: Co search hay ko
     * @param bool $sort: Co sort hay ko
     * @return void
     */
    function column($field_name, $label, $type = TAB_TEXT, $search = false, $sort = false)
    {

        //Gán vào mảng chưa các trường sẽ được hiển thị trogn bảng
        $this->arr_field[$field_name]   =   [
            'label'     =>  $label,
            'type'      =>  $type,
            'search'    =>  $search,
            'sort'      =>  $sort
        ];

        //Nếu có lựa chọn tìm kiếm thì gán vào mảng chứa các trường tìm kiếm
        if ($search) {
            $this->search_data[$field_name] =   [
                'label' =>  $label,
                'type'  =>  $type,
                'query' =>  true
            ];
        }

        return $this;
    }


    /**
     * DataTable::addSearchData()
     * Them cac field search vao trong form search
     * @param mixed $array_search($field_name => ['label' => $label, 'type' => $type_data, 'query' => true/false])
     * @return void
     */
    function addSearchData($array_search)
    {

        $this->search_data  =   array_merge($this->search_data, $array_search);

        return $this;
    }

    /**
     * DataTable::addFieldMultiSearch()
     * Them truong ma co the tim kiem bang nhieu column khac nhau trong table
     * @param mixed $array_field [field => [field_1, field_2...]]
     * @return void
     */
    function addFieldMultiSearch($array_field)
    {
        $this->field_multi_search   =   $array_field;
        return $this;
    }


    /**
     * DataTable::setFieldActive()
     * Set array chua field active de switch su dung chung trong file active.php
     * @param mixed $array_field: [field => type_name]
     * @return void
     */
    function setFieldActive($array_field)
    {
        $this->array_field_active   =   $array_field;
        return $this;
    }


    /**
     * DataTable::setFieldTooltip()
     * Set cac truong hien thi tooltip trong truong hop du lieu qua dai
     * @param mixed $array_field ['field_1', 'field_2']
     * @return void
     */
    function setFieldTooltip($array_field)
    {
        $this->field_tooltip    =   $array_field;
        return $this;
    }

    /**
     * DataTable::setFieldSum()
     * Set mang chua cac field can tinh tong de hien thi o footer
     * @param mixed $array_field    ['field' => 'label']
     * @return void
     */
    function setFieldSum($array_field)
    {
        if (!empty($array_field)) {
            foreach ($array_field as $field => $label) {
                $this->array_field_sum[$field]  =   ['label' => $label, 'amount' => 0];
            }
        }
        return $this;
    }

    /**
     * DataTable::setPageSize()
     * Set page size
     * @param mixed $page_size
     * @return void
     */
    function setPageSize($page_size)
    {
        $this->page_size    =   $page_size;
        return $this;
    }


    /**
     * DataTable::setEditFileName()
     * Set edit file name
     * @param mixed $file_name
     * @return void
     */
    function setEditFileName($file_name)
    {
        $this->edit_file_name   =   $file_name;

        if (strpos($this->edit_file_name, '?') !== false) {
            $this->symbol_query =   '&';
        }

        return $this;
    }

    /**
     * DataTable::setActiveFileName()
     * Set active file name
     * @param mixed $file_name: Bao gồm cả đường dẫn. E.x /module/common/active.php
     * @return void
     */
    function setActiveFileName($file_name)
    {
        $this->active_file_name =   $file_name;
        return $this;
    }

    /**
     * DataTable::setDeleteFileName()
     * 
     * @param mixed $file_name
     * @return void
     */
    function setDeleteFileName($file_name)
    {
        $this->delete_file_name =   $file_name;
        return $this;
    }

    /**
     * DataTable::setPathImage()
     * Set path cua image
     * @param mixed $path
     * @return void
     */
    function setPathImage($path)
    {
        $this->path_image   =   $path;
        return $this;
    }

    /**
     * DataTable::setShowTimeMinute()
     * Cho hien thi thoi gian minute hay ko
     * @param mixed $boolean
     * @return void
     */
    function setShowTimeMinute($boolean)
    {
        $this->show_time_minute =   $boolean;
        return $this;
    }

    /**
     * DataTable::setTimeMinuteFormat()
     * Set format of time minute se show ra
     * @param mixed $format: H:i:s or H:i
     * @return void
     */
    function setTimeMinuteFormat($format)
    {
        $this->time_minute_format   =   $format;
        return $this;
    }

    /**
     * DataTable::setShowMinuteBreak()
     * Cho hien thi phut o cung 1 dong hay xuong dong
     * @param mixed $boolean
     * @return void
     */
    function setShowMinuteBreak($boolean)
    {
        $this->show_minute_break    =   $boolean;
        return $this;
    }

    /**
     * DataTable::setShowSearchMinute()
     * Cho tim kiem thoi gian theo phut
     * @param mixed $boolean
     * @return void
     */
    function setShowSearchMinute($boolean)
    {
        $this->show_search_minute   =   $boolean;
        return $this;
    }

    /**
     * DataTable::setShowTotalRecord()
     * Co hien thi tong so ban ghi o footer hay ko
     * @param mixed $boolean
     * @return void
     */
    function setShowTotalRecord($boolean)
    {
        $this->show_total_record    =   $boolean;
        return $this;
    }

    /**
     * DataTable::setShowSTT()
     * Co show cot STT hay ko
     * @param mixed $boolean
     * @return void
     */
    function setShowSTT($boolean)
    {
        $this->show_stt =   $boolean;
        return $this;
    }

    /**
     * DataTable::setEditThickbox()
     * Set width, height, title cho thickbox neu muon click edit mo popup
     * @param mixed $config [width => 1, height => 2, title => 'Title']
     * @return void
     */
    function setEditThickbox($config)
    {
        $this->edit_thickbox    =   $config;
        return $this;
    }

    /**
     * DataTable::setFieldHidden()
     * Set cac truong can phai cho vao form search de giu dung danh sach theo param tren URL
     * @param mixed $array ['field_1', 'field_2']
     * @return void
     */
    function setFieldHidden($array)
    {
        $this->field_hidden =   $array;
        return $this;
    }

    /**
     * DataTable::setQuickMenu()
     * Set mang chua cac quick menu o tren title cua listing
     * @param mixed $array_menu ['url' => '', 'label' => '', 'class' => '']
     * @return void
     */
    function setQuickMenu($array_menu)
    {
        $this->quick_menu   =   $array_menu;
        return $this;
    }

    /**
     * DataTable::setShowLog()
     * Set có show nút view log hay ko
     * @param mixed $boolean
     * @return void
     */
    function setShowLog($boolean)
    {
        $this->show_view_log    =   $boolean;
        return $this;
    }

    /**
     * DataTable::setFieldPermissionLog()
     * Set tên trường check quyền xem log
     * @param mixed $field
     * @return
     */
    function setFieldPermissionLog($field)
    {
        $this->field_permission_log =   $field;
        return $this;
    }

    /**
     * DataTable::addHTMLSearch()
     * Add them truong search vao form search trong truong hop ko muon input nay tham gia truc tiep vao cau sql cua class
     * @param mixed $html
     * @return void
     */
    function addHTMLSearch($html)
    {
        $this->add_html_search  =   $html;
        return $this;
    }

    /**
     * DataTable::addHTMLFooter()
     * Show them html o footer
     * @param mixed $arr_html
     * @return void
     */
    function addHTMLFooter($arr_html)
    {
        $this->add_html_footer  =   $arr_html;
        return $this;
    }

    /**
     * DataTable::addSQL()
     * Dua cau query cua table vao, cau query nay ko co ORDER BY va LIMIT
     * Neu cau sql = '' thi mac dinh se la SELECT * FROM $table
     * @param mixed $sql
     * @return void
     */
    function addSQL($sql = '')
    {

        if ($sql == '') {
            $sql    =   "SELECT *
                            FROM " . $this->table;
        }

        $this->sql  =   $sql;

        //Bỏ đoạn ORDER và LIMIT nếu cố tình truyền vào
        $arr_sql    =   explode('LIMIT', $this->sql);
        $this->sql  =   $arr_sql[0];
        $arr_sql    =   explode('ORDER BY', $this->sql);
        $this->sql  =   $arr_sql[0];

        // bỏ qua GROUp BY

        $arr_groupby    =   explode('GROUP BY', $this->sql);

        $this->sql      = $arr_groupby[0];

        //Trường order by mặc định
        $default_orderby    =   isset($arr_sql[1]) ? $arr_sql[1] : $this->field_id . " DESC";

        //Câu query search
        $sql_search =   $this->getSQLSearch();
        //Gán lại vào câu query để lấy tổng số record
        $this->sql  .=  $sql_search;
        $this->sql  .= (isset($arr_groupby[1])) ? " GROUP BY $arr_groupby[1]" : ''; // nếu có group by thì thêm vào

        //Tinh tong so bang ghi va tong so page
        $this->calTotalRecord();
        $this->calTotalPage();
        // dump($this->sql);

        /** --- Order By --- **/
        $sql_order_by   =   " ORDER BY";
        $this->field_sort   =   strtolower(getValue('fieldsort', 'str', 'GET', ''));
        if ($this->field_sort != '') {
            $this->type_sort    =   strtolower(getValue('sort', 'str', 'GET', ''));
            if ($this->type_sort != 'asc' && $this->type_sort != 'desc')    $this->type_sort    =   'asc';

            //Kiểm tra xem field sort có là 1 trường trong bảng dữ liệu ko
            $column =   $this->DB->query("SHOW COLUMNS FROM " . $this->table)->toArray();
            $arr_column =   [];
            if (!empty($column)) {
                foreach ($column as $row) {
                    $arr_column[]   =   $row['Field'];
                }

                //Nếu tồn tại trường có tên như fieldsort thi mới cho vào câu query
                if (in_array($this->field_sort, $arr_column)) {
                    $sql_order_by   .=  " " . $this->field_sort . " " . $this->type_sort . ",";
                }
            }
        }
        //Mặc định sẽ sort theo ID
        $sql_order_by   .=  " " . $default_orderby;
        /** --- End of Order By --- **/

        /** --- LIMIT --- **/
        $this->current_page =   get_current_page('page');
        if ($this->current_page > $this->total_page)    $this->current_page =   $this->total_page;
        if ($this->current_page < 1)    $this->current_page =   1;

        $sql_limit  =   " LIMIT " . (($this->current_page - 1) * $this->page_size) . "," . $this->page_size;
        /** --- End of LIMIT --- **/
        //Câu query dùng để lấy dữ liệu hiển thị ra bảng (Ko phải lấy tất cả dữ liệu)
        $this->sql_table    =   $this->sql . $sql_order_by . $sql_limit;

        return $this;
    }


    /**
     * DataTable::getSQLSearch()
     * Tao ra cau query search va doan HTML cua form search
     * @return void
     */
    function getSQLSearch()
    {

        //Câu lệnh WHERE và các điều kiện khác từ form search
        $sql_search =   " WHERE 1";
        //Từ hàm này cũng sinh ra đoạn HTML ở form search luôn
        $html_search    =   '';

        //Nếu câu query có từ WHERE rồi thì nối thêm
        if (strpos($this->sql, "WHERE") > 0)    $sql_search =   "";

        // Search theo id
        if (!empty($_GET["record_id"])) {
            $sql_search .= " AND {$this->field_id} = {$_GET["record_id"]}";
        }

        //Nếu có tìm kiếm thì duyệt hết các field tìm kiếm để sinh ra html và câu query
        if (!empty($this->search_data)) {

            foreach ($this->search_data as $field => $arr) {

                //Có những trường hợp chỉ tạo input search thôi mà ko cho tham gia vào câu query (Để tự xử lý bên ngoài)
                if (!isset($arr['query'])) {
                    $arr['query']   =   false;
                }

                // Tùy theo các kiểu dữ liệu mà sinh ra các html và câu query search khác nhau
                switch ($arr['type']) {
                    case TAB_TEXT:
                        // Lấy giá trị tìm kiếm
                        $search_value   =   trim(getValue($field, 'str', 'GET', '', 1));

                        // Nếu input search này có cho phép tham gia trực tiếp vào câu query và có giá trị khác rỗng
                        if ($arr['query'] == true && $search_value != '') {
                            if (isset($this->field_multi_search[$field]) && !empty($this->field_multi_search[$field])) {
                                //Nếu có thể search từ nhiều column thì search OR
                                $sql_search .=  " AND (";
                                $total_field    =   count($this->field_multi_search[$field]);
                                $i  =   0;
                                $sql_search_or = [];
                                foreach ($this->field_multi_search[$field] as $col) {
                                    $i++;
                                    $sql_search_or[] =  $col . " LIKE '%" . $search_value . "%'";
                                }
                                $sql_search .= implode(' OR ', $sql_search_or);
                                $sql_search .=  ")";
                            } else {
                                $sql_search .=  " AND " . $field . " LIKE '%" . $search_value . "%'";
                            }
                        }

                        $html_search    .=  '<div>
                							     <input type="text" id="' . $field . '" name="' . $field . '" value="' . $search_value . '" placeholder="' . $arr['label'] . '" class="form-control" />
                						      </div>';
                        break;

                        //Search select
                    case TAB_SELECT:
                        //Để hiển thị được kiểu này thì cần phải có một mảng lưu các giá trị có tên biến trùng với tên trường dữ liệu
                        global $$field;
                        if (isset($$field)) {
                            $data   =   $$field;

                            //Lấy giá trị tìm kiếm
                            $search_value   =   getValue($field);

                            //Chỉ khi có $_GET của trường search và khác giá trị mặc định thì mới cho vào câu query, nếu ko thì cho giá trị bằng rỗng để ko bị selected trong select box
                            if ($arr['query'] == true && isset($_GET[$field]) && $search_value != -9999) {
                                $sql_search .=  " AND " . $field . " = " . $search_value;
                            }

                            //Nếu ko có $_GET thì cho giá trị lấy được = '' để tránh 1 option bị selected
                            if (!isset($_GET[$field])) {
                                $search_value   =   '';
                            }

                            $html_search    .=  '<div>
        							                 <select id="' . $field . '" name="' . $field . '" class="form-control" data-toggle="select2">';
                            $html_search    .=  '<option value="-9999">-- ' . $arr['label'] . ' --</option>';
                            foreach ($data as $key => $value) {
                                $html_search    .=  '<option value="' . $key . '"' . ($search_value === $key ? ' selected' : '') . '>
                									   ' . $value . '
                								    </option>';
                            }
                            $html_search    .=  '</select>
        						              </div>';
                        }
                        break;

                        //Search dạng ngày tháng
                    case TAB_DATE:
                        //Tạo ra daterangepicker, mặc định là xem từ đầu tháng
                        $date_range =   '';
                        if (isset($_GET[$field]) && $_GET[$field] != '') {
                            $date_range =   getValue($field, GET_STRING, GET_GET, '01/' . date('m/Y') . ($this->show_search_minute ? ' 00:00' : '') . ' - ' . date('d/m/Y' . ($this->show_search_minute ? ' H:i' : '')));

                            if ($arr['query'] == true) {
                                $time_range =   generate_time_from_date_range($date_range);
                                $sql_search .=  " AND " . $field . " BETWEEN " . $time_range['from'] . " AND " . $time_range['to'];
                            }
                        }

                        $html_search    .=  '<div>
                							     <input type="text" name="' . $field . '" value="' . $date_range . '" placeholder="' . $arr['label'] . '" class="form-control ' . ($this->show_search_minute ? 'date_time_range' : 'date_range') . '" size="20" autocomplete="off" />
                						      </div>';
                        break;
                }
            }
        }

        //Gán HTML của các input search để cho vào form search
        $this->html_form_search =   $html_search;

        $this->sql_search   =   $sql_search;

        //Return cau SQL search
        return $sql_search;
    }

    /**
     * DataTable::addED()
     * Cho phép sửa/xóa trong bảng
     * @param bool $edit
     * @param bool $delete
     * @return void
     */
    function addED($edit = false, $delete = false)
    {
        $this->edit     =   $edit;
        $this->delete   =   $delete;
        return $this;
    }


    /**
     * DataTable::calTotalRecord()
     * Tính tổng số bản ghi được tìm thấy của câu query tổng
     * @return integer
     */
    function calTotalRecord()
    {
        //Bẻ câu query để thay thế các trường select bằng hàm COUNT
        $arr_sql    =   explode('FROM ', $this->sql);
        if (!isset($arr_sql[1])) return 0;
        $arr_sql[1] = explode('GROUP ', $arr_sql[1])[0];
        //Ghép câu SELECT COUNT để tính tổng số bản ghi
        $sql_count  =   "SELECT COUNT(" . $this->field_id . ") AS total FROM " . $arr_sql[1];
        $this->total_record =   $this->DB->count($sql_count);

        return $this->total_record;
    }


    /**
     * DataTable::calTotalPage()
     * Tính tổng số trang của dữ liệu
     * @return integer
     */
    function calTotalPage()
    {
        $this->total_page   =   ceil($this->total_record / $this->page_size);

        return $this->total_page;
    }


    /**
     * DataTable::setRowData()
     * Gan row data vao Class de hien thi trong cac ham show theo field
     * @param mixed $row
     * @return void
     */
    function setRowData($row)
    {
        $this->row_data =   $row;
        return $this;
    }


    /**
     * DataTable::createTableData()
     * Generate ra HTML cua du lieu
     * @return HTML of du lieu
     */
    function createTableData()
    {

        $html   =   '';

        //Data of table
        $data   =   $this->DB->query($this->sql_table)->toArray();

        $html_data  =   '';

        if (!empty($data)) {
            $stt    =   0;
            foreach ($data as $row) {
                $stt++;

                //Gán row data vào class
                $this->setRowData($row);

                $html_data  .=  $this->createTR($stt, $row[$this->field_id]);

                //Hiển thị các dữ liệu chính từ các trường
                foreach ($this->arr_field as $field => $arr) {
                    switch ($arr['type']) {
                        case TAB_TEXT:  //Kiểu text
                            $html_data  .=  $this->showFieldText($field);
                            break;

                        case TAB_NUMBER:    //Kiểu số
                            $html_data  .=  $this->showFieldNumber($field);
                            break;

                        case TAB_IMAGE: //Kiểu hiển thị ảnh
                            $html_data  .=  $this->showFieldImage($field);
                            break;

                        case TAB_CHECKBOX:  //Kiểu checkbox (tick)
                            $html_data  .=  $this->showFieldCheckbox($field);
                            break;

                        case TAB_SELECT:  //Kiểu lấy ra value text từ một mảng
                            $html_data  .=  $this->showFieldArray($field);
                            break;

                        case TAB_DATE:  //Kiể ngày tháng
                            $html_data  .=  $this->showFieldDate($field);
                            break;
                    }
                }

                $html_data  .=  $this->closeTR($row[$this->field_id]);
            }
        }

        $html   .=  $html_data;

        return $html;
    }


    /**
     * DataTable::createFormSearch()
     * Generate ra doan HTML cua toan bo form search
     * @return
     */
    function createFormSearch()
    {

        $html   =   '';

        //Có một số danh sách lấy theo các trạng thái thì cần phải truyền thêm một trường hidden để giữa đúng trạng thái của danh sách.
        //VD DS Đơn đang xử lý, thành công, thất bại...

        $html_hidden    =   '';
        if (!empty($this->field_hidden)) {
            foreach ($this->field_hidden as $field) {
                if (isset($_GET[$field])) $html_hidden  .=  '<input type="hidden" name="' . $field . '" value="' . getValue($field, GET_STRING, GET_GET, '', 1) . '">';
            }
        }

        $this->html_form_search .=  $this->add_html_search;

        //Nếu có ít nhất 1 trường search thi mới show form search ra
        if ($this->html_form_search != '') {
            $html   .=  '<div class="row form_search">
                            <div class="col-sm-12">
            					<form action="' . $_SERVER["REQUEST_URI"] . '" class="clearfix">
                                            ' . $html_hidden . '
                                            ' . $this->html_form_search . '
                    						<div>
                                                <button type="submit" class="btn btn-block btn-primary btn-flat btn-xs">Tìm</button>
                                            </div>
                                </form>
                            </div>
                        </div>';
        }

        return $html;
    }


    /**
     * DataTable::createQuickMenu()
     * Show ra box quicklink
     * @return
     */
    function createQuickMenu()
    {
        $html   =   '';
        if (!empty($this->quick_menu)) {
            $html   .=  '<div class="row box_quick_menu">
                            <div class="col-sm-12">
                            <ul>';

            foreach ($this->quick_menu as $menu) {
                $html   .=  '<li><a href="' . $menu['url'] . '" class="' . $menu['class'] . '"><i class="fas fa-phone-square-alt"></i> ' . $menu['label'] . '</a></li>';
            }

            $html   .=      '</ul>
                            </div>
                        </div>';
        }

        return $html;
    }

    /**
     * DataTable::addRowTh()
     * Generate ra cac the <th>
     * @return HTML of <th>
     */
    function addRowTh()
    {

        $html   =  '';

        //Lấy URL hiện tại
        $url    =   get_url(['page', 'fieldsort', 'sort']);

        //Ký tự nối param page (? hoặc &)
        $symbol =   '?';
        if (strpos($url, '?') > 0)  $symbol =   '&';
        $url    .=  $symbol;

        //Add các column
        foreach ($this->arr_field as $field => $arr) {
            $html   .=  '<th class="col_' . $field . '">';
            $html   .=  $arr['label'];
            //Nếu có lựa chọn sort thi thêm icon sort
            if ($arr['sort']) {
                //Href
                $href       =   $url . 'fieldsort=' . $field . '&sort=' . ($this->type_sort == 'desc' && $this->field_sort == $field ? 'asc' : 'desc');
                $icon_sort  =   '<i class="fas fa-sort"></i>';
                $title_sort =   ' giảm dần';

                //Nếu đang sắp xếp theo field này thi mới hiển thị mũi tên lên hoặc xuống
                if ($this->field_sort == $field) {
                    if ($this->type_sort == 'asc') {
                        $icon_sort  =   '<i class="fas fa-sort-amount-up"></i>';
                        $title_sort =   ' tăng dần';
                    } else {
                        $icon_sort  =   '<i class="fas fa-sort-amount-down"></i>';
                    }
                }

                $html   .=  '<a class="has_sort" href="' . $href . '" title="Sắp xếp ' . $arr['label'] . $title_sort . '">' . $icon_sort . '</a>';
            }
            $html   .=  '</th>';
        }

        //Nếu cho phép sửa bản ghi
        if ($this->edit)    $html   .=  '<th class="col_icon">Sửa</th>';
        //Nếu cho phép xóa bản ghi
        if ($this->delete)    $html   .=  '<th class="col_icon">Xóa</th>';

        return $html;
    }


    /**
     * DataTable::createTR()
     * Tao the <tr> va cac <td> mac dinh
     * @param integer $stt
     * @param integer $id
     * @return HTML
     */
    function createTR($stt = 0, $id = 0, $add_html = '', $class = '')
    {

        global  $Admin;

        $html  =  '<tr id="tr_' . $id . '" class="' . ($stt % 2 == 0 ? 'even' : 'odd') . ' ' . $class . '"' . $add_html . '>';

        //STT
        if ($this->show_stt) {
            $html  .=  '<td align="center" class="stt">
                            ' . (isset($Admin) && $Admin->hasPermission($this->field_permission_log) && $this->show_view_log ? '<a href="/module/common/view_log_data.php?table=' . $this->table . '&id=' . $id . '&TB_iframe=true&width=800&height=600" class="thickbox" title="Lịch sử thay đổi dữ liệu">' . $stt . '</a>' : $stt) . '
                        </td>';
        }

        //Nếu cho phép xóa thi thêm cột checkbox
        if ($this->delete) {
            $html  .=  '<td align="center" class="cb"><input type="checkbox" name="record_id[]" value="' . $id . '" class="icb" /></td>';
        }

        return $html;
    }

    /**
     * DataTable::closeTR()
     *
     * @param integer $id
     * @return html
     */
    function closeTR($id = 0)
    {

        $html   =   '';

        if ($this->edit) {
            $html   .=  '<td class="edit" align="center">';
            //Nếu trường hợp mở popup edit
            if (!empty($this->edit_thickbox)) {
                $html   .=  '<a class="thickbox" href="' . $this->edit_file_name . $this->symbol_query . 'id=' . $id . '&TB_iframe=true&height=' . $this->edit_thickbox['height'] . '&width=' . $this->edit_thickbox['width'] . '" title="' . $this->edit_thickbox['title'] . '"><i class="far fa-edit"></i></a>';
            } else {
                $html   .=  '<a href="' . $this->edit_file_name . $this->symbol_query . 'id=' . $id . '&url=' . base64_encode($_SERVER['REQUEST_URI']) . '" title="Sửa bản ghi"><i class="far fa-edit"></i></a>';
            }
            $html   .=  '</td>';
        }

        //Nếu có xóa bản ghi
        if ($this->delete)    $html  .=  '<td class="del" align="center"><a class="delete" onclick="delete_one(' . $id . ');"><i class="far fa-trash-alt"></i></a></td>';

        $html  .=  '</tr>';

        return $html;
    }


    /**
     * DataTable::showFieldText()
     * Hien thi <td> cua truong du lieu kieu text
     * @param mixed $field: Ten truong can hien thi
     * @param mixed $align: left, right, center
     * @return HTML
     */
    function showFieldText($field, $align = 'left')
    {
        $html   =   '<td class="text-' . $align . ' col_' . $field . '">
						<span ' . (in_array($field, $this->field_tooltip) ? 'class="show_tooltip" data-toggle="tooltip" data-placement="top" title="' . $this->row_data[$field] . '"' : '') . '>' . $this->row_data[$field] . '</span>
					</td>';

        return $html;
    }

    /**
     * DataTable::showFieldText()
     * Hien thi <td> cua truong du lieu kieu tien vnd
     * @param mixed $field: Ten truong can hien thi
     * @param mixed $align: left, right, center
     * @return HTML
     */
    function showFieldVND($field, $align = 'left')
    {
        $formattedAmount = number_format($this->row_data[$field], 0, ',', ',');
        $html   =   '<td class="text-' . $align . ' col_' . $field . '">
						<span ' . (in_array($field, $this->field_tooltip) ? 'class="show_tooltip" data-toggle="tooltip" data-placement="top" title="' . $formattedAmount . '"' : '') . '>' . $formattedAmount . '</span>
					</td>';

        return $html;
    }

    /**
     * DataTable::showFieldNumber()
     * Hien thi <td> cua truong du lieu kieu number
     * @param mixed $field: Ten truong can hien thi
     * @return HTML
     */
    function showFieldNumber($field)
    {
        $html   =   '<td class="text-right col_' . $field . '">
						' . format_number($this->row_data[$field]) . '
					</td>';

        //Nếu có thêm tính tổng của trường này thì tính để hiển thị ở footer
        if (isset($this->array_field_sum[$field])) {
            $this->array_field_sum[$field]['amount']    +=  $this->row_data[$field];
        }

        return $html;
    }

    /**
     * DataTable::showFieldCheckbox()
     * Hien thi <td> cua truong du lieu kieu checkbox
     * @param mixed $field: Ten truong can hien thi
     * @return HTML
     */
    function showFieldCheckbox($field)
    {
        $html   =   '<td class="act text-center col_' . $field . '">
	                   <a href="' . $this->active_file_name . '?field=' . $field . '&id=' . $this->row_data[$this->field_id] . (isset($this->array_field_active[$field]) ? '&type=' . $this->array_field_active[$field] : '') . '" onclick="active_field(this); return false;">
                            ' . generate_checkbox_icon($this->row_data[$field]) . '
                        </a>
					</td>';

        return $html;
    }


    /**
     * DataTable::showFieldArray()
     * Hien thi <td> cua truong du lieu kieu array
     * @param mixed $field: Ten truong can hien thi
     * @return HTML of <td>
     */
    function showFieldArray($field)
    {
        //Phải có 1 biến có tên trùng với tên $field ở bên ngoài
        global  $$field;
        $data   =   $$field;

        $html   =   '<td class="col_' . $field . '">
						' . (isset($data[$this->row_data[$field]]) ? $data[$this->row_data[$field]] : '') . '
					</td>';

        return $html;
    }

    /**
     * DataTable::showFieldSelectMulti()
     * Hien thi <td> cua truong du lieu kieu array
     * @param mixed $field: Ten truong can hien thi
     * @return HTML of <td>
     */
    function showFieldSelectMulti($field)
    {
        //Phải có 1 biến có tên trùng với tên $field ở bên ngoài
        global  $$field;
        $data   =   $$field;

        $html   =   '<td class="col_' . $field . '">
                        ' . (isset($data[$this->row_data[$field]]) ? $data[$this->row_data[$field]] : '') . '
                    </td>';

        return $html;
    }

    /**
     * DataTable::showFieldImage()
     * Hien thi <td> cua truong du lieu kieu image
     * @param mixed $field: Ten truong can hien thi
     * @return void
     */
    function showFieldImage($field)
    {

        //Hiển thị ảnh small
        $src    =   '';
        if ($this->row_data[$field] != '') {
            $picture    =   $this->row_data[$field];
            $src    =   $this->path_image . $picture;
        }

        $html   =   '<td class="text-center show_picture">
						' . ($src != '' ? '<img src="' . $src . '" />' : '') . '
					</td>';

        return $html;
    }

    /**
     * DataTable::showFieldDate()
     * Hien thi <td> cua truong date/time
     * @param mixed $field: Ten truong can hien thi
     * @return HTML
     */
    function showFieldDate($field)
    {
        $html   =   '<td class="text-center col_' . $field . '">';
        if ($this->row_data[$field] > 0) {
            $html   .=  date('d/m/Y', $this->row_data[$field]);
            if ($this->show_time_minute) {
                if ($this->show_minute_break) {
                    $html   .=  '<br>';
                } else {
                    $html   .=  ' ';
                }
                $html   .=  date($this->time_minute_format, $this->row_data[$field]);
            }
        }
        $html   .=  '</td>';
        return $html;
    }


    /**
     * DataTable::createTable()
     *
     * @return
     */
    function createTable()
    {

        $html   =   '<div class="main_listing">';

        //Phần quicklink nếu có
        $html   .=  $this->createQuickMenu();

        //Phan form search
        $html   .=  $this->createFormSearch();

        //Phan title table
        $html   .=  '<div class="row">
                        <div class="col-sm-12 table-responsive">
                            <table id="data_table" class="table table-bordered table-hover dataTable table_full">';
        if ($this->delete_file_name != 'delete.php') {
            $html   .=  '<script>var file_delete = "' . $this->delete_file_name . '";</script>';
        }

        $html   .=  '<tr>';
        if ($this->show_stt)    $html   .=  '<th class="col_stt">#</th>';
        if ($this->delete)   $html   .=  '<th class="col_icon"><input type="checkbox" id="checkall" onclick="check_all(this);" /></th>';
        $html   .=  $this->addRowTh();
        $html   .=  '</tr>';

        return $html;
    }


    /**
     * DataTable::closeTable()
     *
     * @return
     */
    function closeTable()
    {

        //Close title table
        $html   =   '</table>
                    </div>
                </div>';

        //Hien thi phan phan trang
        $html   .=  '<div class="row page_break">
                        <div class="col-sm-12 col-md-8">';
        if ($this->show_total_record) {
            $html   .=  '<div class="footer_sum">Tổng số bản ghi: <b>' . format_number($this->total_record) . '</b></div>';
        }

        //Nếu có trường nào cần tính tổng nữa
        if (!empty($this->array_field_sum)) {
            foreach ($this->array_field_sum as $field => $data) {
                $html   .=  '<div class="footer_sum">' . $data['label'] . ': <b>' . format_number($data['amount']) . '</b></div>';
            }
        }

        //Show them html o footer
        if (!empty($this->add_html_footer)) {
            foreach ($this->add_html_footer as $label) {
                $html   .=  '<div class="footer_sum">' . $label . '</div>';
            }
        }

        $html   .=  '</div>';
        $html   .=  '<div class="col-sm-12 col-md-4">
	                   <div class="dataTables_paginate paging_simple_numbers">';
        if ($this->delete)   $html    .=  '<a onclick="delete_all();" class="delete_all"><i class="far fa-trash-alt"></i>Xóa tất cả đã chọn</a>';

        if ($this->total_page > 1) {

            $html   .=  $this->generatePagebar();
        }

        $html   .=  '</div>
                    </div>';
        $html   .=   '</div>';
        $html   .=   '</div>';

        return $html;
    }


    /**
     * DataTable::showTableData()
     * Hien thi du lieu
     * @return HTML of table data
     */
    function showTableData()
    {

        //Ghép các thành phần của table vào

        $html   =   '';
        $html   .=  $this->createTable();
        $html   .=  $this->createTableData();
        $html   .=  $this->closeTable();

        return $html;
    }

    /**
     * DataTable::generatePagebar()
     * Generate ra page bar
     * @return
     */
    function generatePagebar()
    {

        $total_page     =   $this->total_page;
        $current_page   =   get_current_page();

        if ($total_page > 1) {

            $page_start =   $current_page - 2;
            if ($page_start < 1)    $page_start =   1;

            //Đoạn html của các page
            $html_page  =   '<ul class="pagination">';
            $url        =   get_url();
            $url        .=  get_url_symbol_query($url);

            //Nếu trang hiện tại > 3 thì mới hiện nút "Đầu"
            if ($current_page > 3) {
                $html_page  .=  '<li class="paginate_button page-item previous">
                                    <a class="page-link" href="' . $url . 'page=1' . '">Trang đầu</a>
                                </li>
    		                     <li class="paginate_button page-item"><span class="page-link">...</span></li>';
            }

            //2 Trang liền trước của trang hiện tại
            for ($i = $page_start; $i < $current_page; $i++) {
                $html_page  .=  '<li class="paginate_button page-item">
                                    <a class="page-link" href="' . $url . 'page=' . $i . '">' . $i . '</a>
                                </li>';
            }

            //Trang hiện tại
            $html_page  .=  '<li class="paginate_button page-item active">
                                <a class="page-link" href="javascript:;">' . $current_page . '</a>
                            </li>';

            //2 Trang liền sau của trang hiện tại
            $next_2_page    =   $current_page + 2;
            if ($next_2_page > $total_page) $next_2_page  =   $total_page;
            for ($i = $current_page + 1; $i <= $next_2_page; $i++) {
                $html_page  .=  '<li class="paginate_button page-item">
                                    <a class="page-link" href="' . $url . 'page=' . $i . '">' . $i . '</a>
                                </li>';
            }

            //Nếu trang hiện tại nhỏ hơn tổng số trang tối thiểu là 3 page thì mới hiện nút Trang cuối
            if ($total_page - $current_page > 2) {
                $html_page  .=  '<li class="paginate_button page-item"><span class="page-link">...</span></li>
                                <li class="paginate_button page-item next" id="example2_next">
                    				<a class="page-link" href="' . $url . 'page=' . $total_page . '">Trang cuối</a>
                    			</li>';
            }

            $html_page  .=  '</ul>';

            //Return HTML
            return $html_page;
        }
    }
}
