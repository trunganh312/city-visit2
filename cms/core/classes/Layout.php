<?

/**
 * Class Layout
 * Created by SenEnter
 */

class Layout
{

    public $path_theme;
    private $popup  =   false;
    private $page_title =   'CMS';
    private $note_title =   '';
    private $title_button   =   []; //Các nút hiển thị trên title ở góc phải (VD nút Thêm mới)

    /**
     * Layout::__construct()
     * 
     * @return void
     */
    function __construct()
    {
        $this->path_theme   =   base_url()  . 'theme/';
    }

    /**
     * Layout::setPopup()
     * 
     * @param mixed $bool
     * @return void
     */
    function setPopup($bool)
    {
        $this->popup    =   $bool;
        return $this;
    }

    /**
     * Layout::setTitleButton()
     * Add thêm các button/link ở góc phải trên title của page
     * @param mixed $button: string OR array
     * @return void
     */
    function setTitleButton($button)
    {
        $this->title_button =   $button;
        return $this;
    }

    /**
     * Layout::loadHead()
     * Load title, cac file CSS... cua the <head>
     * @param string $title
     * @param string $add_more string. VD: <link rel="stylesheet" href="file.css" />
     * @return string html cua the <head>
     */
    function loadHead($title = 'CMS', $add_more = '')
    {
        global  $cfg_theme_version_css;
        global  $cfg_website;

        $css    =   ['all', 'daterangepicker', 'summernote', 'thickbox', 'select2', 'select2-bootstrap4.min', 'toastr', 'adminlte', 'table', 'style', 'font', 'waitMe.min', 'jquery-ui', 'jquery-autocomplete', 'jquery.uploader', 'quill'];

        $head   =  '<title>' . $title . '</title>
                    <meta charset="UTF-8" />
                    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, initial-scale=1.0, user-scalable=no" id="viewport" />
                    <meta name="robots" content="NOINDEX" />
                    <link rel="icon" href="' . $this->getFavicon()  . '" type="image/x-icon" />';

        foreach ($css as $file) {
            $head   .=  '<link rel="stylesheet" href="' . $this->path_theme . 'css/' . $file . '.css' . (isset($cfg_theme_version_css[$file]) ? '?v=' . $cfg_theme_version_css[$file] : '') . '" />';
        }

        //Nếu add thêm file
        $head   .=  $add_more;
        $head .= '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />';
        $head .= '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/brands.min.css" integrity="sha512-sVSECYdnRMezwuq5uAjKQJEcu2wybeAPjU4VJQ9pCRcCY4pIpIw4YMHIOQ0CypfwHRvdSPbH++dA3O4Hihm/LQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />';
        $head .= '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/fontawesome.min.css" integrity="sha512-P9vJUXK+LyvAzj8otTOKzdfF1F3UYVl13+F8Fof8/2QNb8Twd6Vb+VD52I7+87tex9UXxnzPgWA3rH96RExA7A==" crossorigin="anonymous" referrerpolicy="no-referrer" />';
        $head .= '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/regular.min.css" integrity="sha512-d2x1oQUT6HACW9UlXxWI6XrIBDrEE5z2tit/+kWEdXdVYuift7sm+Q6ucfGWQr1F0+GD9/6eYoYDegw2nm05Vw==" crossorigin="anonymous" referrerpolicy="no-referrer" />';
        return $head;
    }

    /**
     * Layout::getFavicon()
     * Lay favicon theo tung environment de nhan dien, tranh bi test nham moi truong
     * @return
     */
    function getFavicon()
    {
        $favicon    =   'favicon.png';

        if (is_dev()) {
            $favicon    =   'theme/image/favicon.ico';
        }

        return '/' . $favicon;
    }


    /**
     * Layout::titlePage()
     * 
     * @param mixed $page_title
     * @param mixed $add_link: String hoặc Array: Chen them doan link, button
     * @return HTML of title page
     */
    function titlePage($page_title, $add_link = [])
    {
        $html_title =   '<div class="container-fluid title_header">
        					<div class="row">
        						<div class="col-sm-5">
        							<h1 class="m-0 text-dark">' . $page_title . '</h1>
        						</div>';

        $html_title .=  '<div class="col-sm-7 add_link_more">
							<ol class="breadcrumb float-sm-right">';
        if (gettype($add_link) == 'array') {
            foreach ($add_link as $link) {
                $html_title .=  '<li class="breadcrumb-item">
									' . $link . '
								</li>';
            }
        } else {
            $html_title .=  '<li class="breadcrumb-item">
								' . $add_link . '
							</li>';
        }

        $html_title   .=  '</ol>
						</div>';

        $html_title .=  '</div>
        				</div>';

        return $html_title;
    }

    /**
     * Layout::setNoteTitle()
     * Một số trang DS cần có thêm dòng ghi chú ở dưới title
     * @param mixed $note_title
     * @return void
     */
    function setNoteTitle($note_title)
    {
        $this->note_title   =   $note_title;
        return $this;
    }


    /**
     * Layout::loadFooter()
     * Load cac file JS footer
     * @param string $add_more string <script src="' . $this->path_theme . 'js/jquery.min.js"></script>
     * @return HTML of cac the script
     */
    function loadFooter($add_more = '')
    {

        global  $cfg_theme_version_js;

        $js =   [
            'jquery',
            'jquery-ui',
            'bootstrap',
            'jeditable',
            'moment',
            'inputmask',
            'daterangepicker',
            'countdown',
            'summernote',
            'select2',
            'toastr',
            'customfile',
            'adminlte',
            'thickbox',
            'jquery-autocomplete',
            'table',
            'main',
            'waitMe.min',
            'upload',
            'previewImage',
            'jquery.uploader.min',
            'slug',
            'quill'
        ];

        $footer =   '';

        foreach ($js as $file) {
            $footer .=  '<script src="' . $this->path_theme . 'js/' . $file . '.js' . (isset($cfg_theme_version_js[$file]) ? '?v=' . $cfg_theme_version_js[$file] : '') . '"></script>';
        }

        $footer .=  $add_more;

        //Microtime
        global  $micro_time_start;
        global  $Admin;

        if (isset($micro_time_start) && isset($Admin) && $Admin->boss) {
            $micro_time_end =   get_microtime();
            $micro_time_load    =   $micro_time_end - $micro_time_start;

            $footer .=  '<p style="text-align:center;margin: 5px 0;">' . $micro_time_load . '</p>';
        }

        //Load toastr JS
        $footer .=  toastr();

        return $footer;
    }

    /**
     * Layout::header()
     * Show ra content của phần header và sidebar
     * @return void
     */
    function header($page_title)
    {

        $this->page_title   =   $page_title;

        echo    '<div class="wrapper">';
        if (!$this->popup) {
            include($_SERVER['DOCUMENT_ROOT'] . '/layout/inc_header.php');
        }

        echo    '<div class="content-wrapper">';
        if (!$this->popup) {
            echo    $this->titlePage($this->page_title, $this->title_button);
        }

        echo    '<div class="container-fluid main_content">';

        //Nếu có ghi chú của title
        if ($this->note_title != '') {
            echo    '<div class="row title_note">
						' . $this->note_title . '
                    </div>';
        }
    }

    /**
     * Layout::footer()
     * Show ra content của footer
     * @param string $add_more: Chèn thêm đoạn HTML hoặc JS
     * @return void
     */
    function footer($add_more = '')
    {
        echo    '</div>
                </div>
                </div>';

        echo    $this->loadFooter($add_more);
        echo    $this->loadCkeditor();
        echo '<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDIfSyryL0vRpxCCDilpmgnYhC98A_E8EQ&callback=initializeMap&libraries=places&v=weekly" defer></script>';
    }


    function loadMapInit($lat = 0, $lng = 0)
    {
        echo '<script>
        // Khởi tạo map
        async function initializeMap() {
            await initMap(' . $lat . ', ' . $lng . ');
        }
        initializeMap();
    </script>';
    }

    function loadCkeditor()
    {
        $js_paths = [
            // "ckeditor/ckeditor.js",
            "ckeditor/init.js",
            "ggmap/map.js"
        ];
        global  $cfg_theme_version_js;

        foreach ($js_paths as $file) {
            echo '<script src="' . $this->path_theme  . $file . (isset($cfg_theme_version_js[$file]) ? '?v=' . $cfg_theme_version_js[$file] : '') . '"></script>';
        }
    }

    // Load kiêu khách sạn
    function loadRoomType()
    {
        $arrType = [
            TYPE_HOTEL => 'Khách sạn',
            TYPE_EAT => 'Ăn uống',
            TYPE_NEAR => 'Địa điểm xung quanh',
            TYPE_ROOM => 'Phòng',
            TYPE_SWIM => 'Bơi',
            TYPE_AMENITY => 'Tiện ích',
            TYPE_OTHER => 'Khác'
        ];
        $html = '';
        foreach ($arrType as $key => $value) {
            $html .= '<li>
            <i>' . $value . '</i>
            <input type="text" id="demo' . $key . '" value="' . $key . '" />
            </li>';
        }
        return $html;
    }
}
