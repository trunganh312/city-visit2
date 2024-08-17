<?

/**
 * Class GenerateForm
 * Created by SenEnter
 */
class GenerateForm
{

    private $path_theme_image;
    private $form_column    =   1;  //Form đơn hay form 2 cột

    function __construct()
    {
        $this->path_theme_image =   '/theme/image/';
    }

    /**
     * GenerateForm::createForm()
     * Khoi tao form
     * @param integer $column: Hien thi form theo 1 cot hay 2 cot
     * @return string HTML
     */
    function createForm($column = 1)
    {

        $this->form_column  =   $column;

        $html   =   '<div class="main_form">
                        <form method="POST" action="" name="form_data" id="form_data" enctype="multipart/form-data" onsubmit="check_form(); return false;">';
        //Nếu form chỉ hiển thị 1 cột
        if ($column == 1) {
            $html   .=  '<div class="col-md-12">';
        } else {
            $html   .=  '<div class="col-md-6 float-left">
                            <div class="card">';
        }

        return $html;
    }


    /**
     * GenerateForm::breakColumn()
     * Tach thanh column 2 trong truong hop form chia thanh 2 column
     * @return
     */
    function breakColumn()
    {
        $html   =   '</div>
                    </div>
                    <div class="col-md-6 float-right">
                    <div class="card">';

        return $html;
    }


    /**
     * GenerateForm::closeForm()
     *
     * @return HTML close form
     */
    function closeForm()
    {

        $html   =  '</form>
                    </div>';

        return $html;
    }

    /**
     * GenerateForm::checkRequire()
     * Check dieu kien require de tra ve cac du lieu phuc vu cho cac ham generate ra input
     * @param mixed $require
     * @param string $label
     * @return [mark, class, data]
     */
    private function checkRequire($require, $label)
    {

        $return =   ['mark' => '', 'class' => '', 'data' => ''];

        if ($require) {
            $return['mark']     =   '<span class="mark-require">*</span>';
            $return['class']    =   ' required';
            $return['data']     =   ' data-required="' . $label . '"';
        }

        return $return;
    }

    /**
     * GenerateForm::showError()
     * Show ra loi khi submit form
     * @param string $error
     * @return string HTML
     */
    function showError($arr_error = [])
    {

        $html   =   '';

        if (!empty($arr_error)) {
            $html   .=  '<div class="form-group">';
            $html   .=  '<label>&nbsp;</label>';
            $html   .=  '<div class="form_input alert alert-danger">';
            $html   .=  '<ul>';
            foreach ($arr_error as $error) {
                $html   .=  '<li>' . $error . '</li>';
            }
            $html   .=  '</ul>';
            $html   .=  '</div>';
            $html   .=  '</div>';
        }

        return $html;
    }


    /**
     * GenerateForm::button()
     * Show ra nut submit form
     * @param string $title_button
     * @param string $hidden_name: Neu thay doi thi phai thay doi o class GenerateQuery
     * @param string $hidden_value: Neu thay doi thi phai thay doi o class GenerateQuery
     * @return HTML nut submit form
     */
    function button($title_button = 'Cập nhật', $hidden_name = 'action', $hidden_value = 'submitted')
    {

        $html   =   '';

        $button =   '<input type="submit" class="btn btn-success btn-sm btn_form" value="' . $title_button . '" />
                    <input type="hidden" name="' . $hidden_name . '" value="' . $hidden_value . '" />
                    <img class="img_loading_form hidden" src="' . $this->path_theme_image . 'loading_circle.gif">
                    ';

        //Nếu là form đơn thì hiển thị nút submit luôn cùng hàng với form control
        //if ($this->form_column == 1) {
        $html   .=  '<div class="form-group form-button">
                            <label>&nbsp;</label>
                            <div class="form_input">';
        $html    .=         $button;
        $html   .=      '</div>
                         </div>';

        $html   .=  '</div>';   //Đóng thẻ div của col-12

        if ($this->form_column == 2)    $html   .=  '</div>';   //Nếu 2 column thì phải đóng thêm 1 thẻ nữ

        return $html;
    }


    /**
     * GenerateForm::textHTML()
     * Hien thi noi dung text, ko phai input
     * @param mixed $label
     * @param mixed $content
     * @param string $style
     * @return void
     */
    function textHTML($label, $content)
    {

        $html   =   '<div class="form-group text_html">';
        $html   .=  '<label>' . (!empty($label) ? $label . ' :' : '') . '</label>';
        $html   .=  '<div class="form_input">';
        $html   .=  '<p>' . $content . '</p>';
        $html   .=  '</div>';
        $html   .=  '</div>';

        return $html;
    }


    /**
     * GenerateForm::text()
     *
     * @param mixed $label
     * @param mixed $name_control
     * @param mixed $value
     * @param bool $require true or false
     * @return HTML of input type=text
     */
    function text($label, $name_control, $value = '', $require = false, $add_note = '', $add_attr = '', $max_char = 0, $current_length = 0)
    {

        //Check require
        $check_require  =   $this->checkRequire($require, $label);

        //String set thêm các attribute cho control
        $str_attr   =   $check_require['data'];

        if ($add_attr != '') {
            $str_attr   .=  ' ' . $add_attr;
        }

        $html   =   '<div class="form-group control_' . $name_control . '">';
        $html   .=  '<label>' . $check_require['mark'] . $label . ' :</label>';
        $html   .=  '<div class="form_input">';
        $html   .=  '<input type="text" id="' . $name_control . '" name="' . $name_control . '" value="' . replaceQuot($value) . '" class="form-control' . $check_require['class'] . ($max_char > 0 ? ' max_char' : '') . '"' . $str_attr . '><span class="fuzzy">' . ($max_char > 0 ? '<span class="count_char">' . $current_length . '</span>/' . $max_char : '') . $add_note . '</span>';
        $html   .=  '</div>';
        $html   .=  '</div>';

        return $html;
    }


    /**
     * GenerateForm::number()
     *
     * @param mixed $label
     * @param mixed $name_control
     * @param string $value
     * @param bool $require
     * @param string $add_note
     * @return
     */
    function number($label, $name_control, $value = '', $require = false, $add_note = '', $add_html = '')
    {

        //Check require
        $check_require  =   $this->checkRequire($require, $label);
        $value  =   str_replace(',', '', $value);

        //HTML set width cua input
        $str_attr   =   $check_require['data'];

        $html   =   '<div class="form-group control_' . $name_control . '">';
        $html   .=  '<label>' . $check_require['mark'] . $label . ' :</label>';
        $html   .=  '<div class="form_input">';
        $html   .=  '<input type="text" id="' . $name_control . '" name="' . $name_control . '" value="' . format_number($value) . '" class="form-control number' . $check_require['class'] . '"' . $str_attr . '>' . $add_html . '<span class="fuzzy">' . $add_note . '</span>';
        $html   .=  '</div>';
        $html   .=  '</div>';

        return $html;
    }

    /**
     * GenerateForm::float()
     *
     * @param mixed $label
     * @param mixed $name_control
     * @param string $value
     * @param bool $require
     * @param string $add_note
     * @return
     */
    function float($label, $name_control, $value = '', $require = false, $min = '', $max = '', $add_note = '')
    {

        //Check require
        $check_require  =   $this->checkRequire($require, $label);
        $value  =   str_replace(',', '', $value);

        //HTML set width cua input
        $str_attr   =   $check_require['data'];

        $html   =   '<div class="form-group control_' . $name_control . '">';
        $html   .=  '<label>' . $check_require['mark'] . $label . ' :</label>';
        $html   .=  '<div class="form_input">';
        $html   .=  '<input type="number" min="' . $min . '" step="0.01" id="' . $name_control . '" name="' . $name_control . '" value="' . $value . '" class="form-control text-right' . $check_require['class'] . '"' . $str_attr . '><span class="fuzzy">' . $add_note . '</span>';
        $html   .=  '</div>';
        $html   .=  '</div>';

        return $html;
    }

    /**
     * GenerateForm::textSearch()
     *
     * @param mixed $label
     * @param mixed $name_control
     * @param string $value
     * @param string $default_text
     * @param bool $require
     * @param string $add_note
     * @param string $add_attr
     * @return
     */
    function textSearch($label, $name_control, $value = '', $default_text = '', $require = false, $add_note = '', $add_attr = '')
    {

        //Check require
        $check_require  =   $this->checkRequire($require, $label);

        //String set thêm các attribute cho control
        $str_attr   =   $check_require['data'];

        if ($add_attr != '') {
            $str_attr   .=  ' ' . $add_attr;
        }

        $html   =   '<div class="form-group control_' . $name_control . '">';
        $html   .=  '<label>' . $check_require['mark'] . $label . ' :</label>';
        $html   .=  '<div class="form_input">';
        $html   .=  '<input type="hidden" id="' . $name_control . '" name="' . $name_control . '" value="' . replaceQuot($value) . '" class="' . $check_require['class'] . '">';
        $html   .=  '<input type="text" id="search_' . $name_control . '" name="search_' . $name_control . '" value="' . replaceQuot(getValue('search_' . $name_control, 'str', 'POST', $default_text)) . '" autocomplete="off" class="form-control autosearch"' . $str_attr . '><span class="fuzzy">' . $add_note . '</span>';
        $html   .=  '</div>';
        $html   .=  '</div>';

        return $html;
    }


    /**
     * GenerateForm::datePicker()
     *
     * @param mixed $label
     * @param mixed $name_control
     * @param string $value
     * @param bool $require
     * @param string $add_note
     * @return
     */
    function datePicker($label, $name_control, $value = '', $require = false, $add_note = 'dd/mm/YYYY', $add_attr = '')
    {

        //Check require
        $check_require  =   $this->checkRequire($require, $label);

        //String set thêm các attribute cho control
        $str_attr   =   $check_require['data'];

        if ($add_attr != '') {
            $str_attr   .=  ' ' . $add_attr;
        }

        $html   =   '<div class="form-group control_' . $name_control . '">';
        $html   .=  '<label>' . $check_require['mark'] . $label . ' :</label>';
        $html   .=  '<div class="form_input">';
        $html   .=  '<input type="text" id="' . $name_control . '" name="' . $name_control . '" value="' . replaceQuot($value) . '" class="form-control datepicker' . $check_require['class'] . '"' . $str_attr . '><span class="fuzzy">' . $add_note . '</span>';
        $html   .=  '</div>';
        $html   .=  '</div>';

        return $html;
    }

    /**
     * GenerateForm::datePicker()
     *
     * @param mixed $label
     * @param mixed $name_control
     * @param string $value
     * @param bool $require
     * @param string $add_note
     * @return
     */
    function dateRangePicker($label, $name_control, $value = '', $require = false, $add_note = 'dd/mm/YYYY - dd/mm/YYYY', $add_attr = '')
    {

        //Check require
        $check_require  =   $this->checkRequire($require, $label);

        //String set thêm các attribute cho control
        $str_attr   =   $check_require['data'];

        if ($add_attr != '') {
            $str_attr   .=  ' ' . $add_attr;
        }

        $html   =   '<div class="form-group control_' . $name_control . '">';
        $html   .=  '<label>' . $check_require['mark'] . $label . ' :</label>';
        $html   .=  '<div class="form_input">';
        $html   .=  '<input type="text" id="' . $name_control . '" name="' . $name_control . '" value="' . replaceQuot($value) . '" class="form-control date_range' . $check_require['class'] . '" autocomplete="off"' . $str_attr . '><span class="fuzzy">' . $add_note . '</span>';
        $html   .=  '</div>';
        $html   .=  '</div>';

        return $html;
    }

    /**
     * GenerateForm::textarea()
     *
     * @param mixed $label
     * @param mixed $name_control
     * @param string $value
     * @param bool $require
     * @param string $add_note
     * @return
     */
    function textarea($label, $name_control, $value = '', $require = false, $add_note = '', $add_attr = '', $max_char = 0, $current_length = 0)
    {
        //Check require
        $check_require  =   $this->checkRequire($require, $label);

        //String set thêm các attribute cho control
        $str_attr   =   $check_require['data'];

        if ($add_attr != '') {
            $str_attr   .=  ' ' . $add_attr;
        }

        $exp_label  =   explode('<i>', $label);

        $html   =   '<div class="form-group control_' . $name_control . '">';
        $html   .=  '<label>' . $check_require['mark'] . $exp_label[0] . ' :' . (!empty($exp_label[1]) ? '<i>' . $exp_label[1] : '') . '</label>';
        $html   .=  '<div class="form_input">';
        $html   .=  '<textarea id="' . $name_control . '" name="' . $name_control . '" class="form-control' . $check_require['class'] . ($max_char > 0 ? ' max_char' : '') . '"' . $str_attr . '>' . replaceQuot($value) . '</textarea><span class="fuzzy">' . ($max_char > 0 ? '<span class="count_char">' . $current_length . '</span>/' . $max_char : '') . $add_note . '</span>';
        $html   .=  '</div>';
        $html   .=  '</div>';

        return $html;
    }


    /**
     * GenerateForm::checkbox()
     *
     * @param mixed $label
     * @param mixed $name_control
     * @param mixed $current_value
     * @param integer $value
     * @return HTML of input type=checkbox
     */
    function checkbox($label, $name_control, $current_value, $value = 1)
    {

        $html   =   '<div class="form-group control_' . $name_control . '">';
        //$html   .=  '<label>&nbsp;</label>';
        $html   .=   '<div class="form_input">
                        <label class="input_checkbox">' . $label . '
                            <input type="checkbox" id="' . $name_control . '" name="' . $name_control . '" value="' . $value . '"' . ($current_value == $value ? ' checked' : '') . '>
                            <span class="checkmark"></span>
                        </label>
                       </div>';
        $html   .=  '</div>';

        return $html;
    }

    /**
     * GenerateForm::radio()
     *
     * @param mixed $label
     * @param mixed $name_control
     * @param mixed $current_value
     * @param integer $value
     * @return HTML of input type=radio
     */
    function radio($label, $name_control, $current_value, $value = 1)
    {

        $html   =   '<div class="form-group control_' . $name_control . '">';
        //$html   .=  '<label>&nbsp;</label>';
        $html   .=   '<div class="form_input">
                        <label class="input_radio">
                            <input type="radio" id="' . $name_control . '" name="' . $name_control . '" value="' . $value . '"' . ($current_value == $value ? ' checked' : '') . '>
                            <span class="checkmark"></span>
                            ' . $label . '
                        </label>
                       </div>';
        $html   .=  '</div>';

        return $html;
    }


    /**
     * GenerateForm::select()
     *
     * @param mixed $label
     * @param mixed $name_control
     * @param mixed $array_data
     * @param mixed $current_value
     * @param bool $require
     * @param string $add_note
     * @param string $add_class
     * @return
     */
    function select($label, $name_control, $array_data, $current_value, $require = false, $add_note = '', $add_attr = '')
    {

        //Check require
        $check_require  =   $this->checkRequire($require, $label);

        //String set thêm các attribute cho control
        $str_attr   =   $check_require['data'];

        if ($add_attr != '') {
            $str_attr   .=  ' ' . $add_attr;
        }

        $html   =   '<div class="form-group control_' . $name_control . '" id="control_' . $name_control . '">';
        $html   .=  '<label>' . $check_require['mark'] . $label . ' :</label>
                        <div class="form_input">
                            <select id="' . $name_control . '" name="' . $name_control . '" class="form-control control_select' . $check_require['class'] . '" ' . $str_attr . '>';

        //Gán một option mặc định ban đầu
        $html   .=  '<option value="">--' . $label . '--</option>';

        foreach ($array_data as $key => $value) {
            $html   .=  '<option value="' . $key . '"' . ($key == $current_value ? ' selected' : '') . '>' . $value . '</option>';
        }

        $html   .=  '</select><span class="fuzzy">' . $add_note . '</span>
                  </div>
                </div>';

        return $html;
    }


    /**
     * GenerateForm::select2()
     * Generate ra selectbox select2
     * @param mixed $label
     * @param mixed $name_control
     * @param mixed $array_data
     * @param mixed $current_value
     * @param bool $require
     * @param string $add_note
     * @return
     */
    function select2($label, $name_control, $array_data, $current_value, $require = false, $add_note = '', $add_attr = '')
    {
        //Check require
        $check_require  =   $this->checkRequire($require, $label);

        //String set thêm các attribute cho control
        $str_attr   =   $check_require['data'];

        if ($add_attr != '') {
            $str_attr   .=  ' ' . $add_attr;
        }


        $html   =   '<div class="form-group control_' . $name_control . '">';
        $html   .=  '<label>' . $check_require['mark'] . $label . ' :</label>
                        <div class="form_input">
                            <select data-toggle="select2" id="' . $name_control . '" name="' . (is_array($current_value) ? ($name_control . '[]') : $name_control) . '" class="form-control control_select2' . $check_require['class'] . '" ' . $str_attr . '>';

        //Gán một option mặc định ban đầu
        if (!is_array($current_value)) $html   .=  '<option value="">' . $label . '</option>';

        foreach ($array_data as $key => $value) {
            $html   .=  '<option value="' . $key . '"' . ((is_array($current_value) ? in_array($key, $current_value) : $key == $current_value) ? ' selected' : '') . '>' . $value . '</option>';
        }

        $html   .=  '</select><span class="fuzzy">' . $add_note . '</span>
                  </div>
                </div>';

        return $html;
    }


    /**
     * GenerateForm::file()
     *
     * @param mixed $label
     * @param mixed $name_control
     * @param bool $require
     * @param string $add_note
     * @return
     */
    function file($label, $name_control, $require = false, $add_note = '')
    {
        //Check require
        $check_require  =   $this->checkRequire($require, $label);

        //String set thêm các attribute cho control
        $str_attr   =   $check_require['data'];

        $html   =   '<div class="form-group">';
        $html   .=  '<label>' . $check_require['mark'] . $label . '</label>';
        $html   .=   '<div class="form_input">
                        <input type="file" id="' . $name_control . '" name="' . $name_control . '" size="20" ' . $check_require['data'] . '><span class="fuzzy">' . $add_note . '</span>
                        </div>
                    </div>';

        return $html;
    }


    /**
     * GenerateForm::showImage()
     * Hien thi anh hien tai trong cac form Edit
     * @param mixed $src
     * @return
     */
    function showImage($src)
    {
        $html   =   '<div class="form-group">
                        <label>&nbsp;</label>
                        <div class="form_input form-show-image">
                            <img src="' . $src . '" />
                        </div>
                    </div>';
        return $html;
    }

    /**
     * GenerateForm::textEditor()
     *
     * @param mixed $label
     * @param mixed $name_control
     * @param string $value
     * @param bool $require
     * @return
     */
    function textEditor($label, $name_control, $value = '', $require = false, $add_note = '', $height = 300, $width = 'auto')
    {

        //Check require
        $check_require  =   $this->checkRequire($require, $label);

        $html   =   '<div class="form-group control_' . $name_control . '">';
        $html   .=  '<label>' . $check_require['mark'] . $label . ' :</label>';
        $html   .=  '<div class="form_input">
                        <textarea class="text__ckeditor" id="' . $name_control . '" name="' . $name_control . '" placeholder="Nhập nội dung ..." data-height="' . $height . '"  data-width="' . $width . '">' . $value . '</textarea>
                        <span class="fuzzy">' . $add_note . '</span>
                        </div>
                    </div>';

        return $html;
    }

    /**
     * GenerateForm::tempControl()
     * Luu lai doan HTML thinh thoang dung khi tao control thu cong
     * @return void
     */
    function tempControl()
    {
        $html   =   '<div class="form-group">
                        <label>
                            <span class="mark-require">*</span>
                            [Label] :
                        </label>
                        <div class="form_input">
                            [control]
                            <span class="fuzzy"></span>
                        </div>
                    </div>';
    }
}
