<?

/**
 * Class Layout
 * Created by SenEnter
 */

class Layout
{
  public $path_theme;

  /**
   * Layout::__construct()
   * 
   * @return void
   */
  function __construct()
  {
    $this->path_theme   =   base_url_web()  . 'public/';
  }

  // Load header
  public function loadHead($title =  'Trang chủ')
  {
    $head   =  '<title>' . $title . '</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, initial-scale=1.0, user-scalable=no" id="viewport" />
        <meta name="robots" content="NOINDEX" />
        <link rel="icon" href="' . $this->getFavicon()  . '" type="image/x-icon" />';
    $head .=
      ' 
            <!-- Google Fonts -->
            <link href="https://fonts.googleapis.com/css?family=Lato&display=swap" rel="stylesheet" />
            <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
     
            <!-- CSS Implementing Plugins -->
            <link rel="stylesheet" href="' . base_url_web() . 'public/vendor/font-awesome/css/fontawesome-all.min.css" />
            <link rel="stylesheet" href="' . base_url_web() . 'public/css/font-mytravel.css" />
            <link rel="stylesheet" href="' . base_url_web() . 'public/vendor/animate.css/animate.min.css" />
            <link rel="stylesheet" href="' . base_url_web() . 'public/vendor/hs-megamenu/src/hs.megamenu.css" />
            <link
            rel="stylesheet"
            href="' . base_url_web() . 'public/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css"
            />
            <link rel="stylesheet" href="' . base_url_web() . 'public/vendor/fancybox/jquery.fancybox.css"/>
            <link rel="stylesheet" href="' . base_url_web() . 'public/vendor/flatpickr/dist/flatpickr.min.css" />
            <link
            rel="stylesheet"
            href="' . base_url_web() . 'public/vendor/bootstrap-select/dist/css/bootstrap-select.min.css"
            />
            <link rel="stylesheet" href="' . base_url_web() . 'public/vendor/slick-carousel/slick/slick.css" />
            <link rel="stylesheet" href="' . base_url_web() . 'public/vendor/dzsparallaxer/dzsparallaxer.css" />
            <link rel="stylesheet" href="' . base_url_web() . 'public/vendor/ion-rangeslider/css/ion.rangeSlider.css" />
            <link rel="stylesheet" href="' . base_url_web() . 'public/vendor/custombox/dist/custombox.min.css" />
            
            <!-- CSS MyTravel Template -->
            <link rel="stylesheet" href="' . base_url_web() . 'public/css/theme.css" />
            <link rel="stylesheet" href="' . base_url_web() . 'public/css/star-rating.css" />
            <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.5.1/uicons-regular-straight/css/uicons-regular-straight.css">
            <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.5.1/uicons-regular-rounded/css/uicons-regular-rounded.css">

            ';

    return $head;
  }

  /**
   * Layout::getFavicon()
   * Lay favicon theo tung environment de nhan dien, tranh bi test nham moi truong
   * @return
   */
  function getFavicon()
  {
    $favicon    =   '/favicon.png';
    return  $favicon;
  }

  // Load header
  public function loadHeader()
  {
    include($_SERVER['DOCUMENT_ROOT'] . '/components/header.php');
  }


  // Load header
  public function loadHeaderList()
  {
    include($_SERVER['DOCUMENT_ROOT'] . '/components/header_list.php');
  }

  // Load footer
  public function loadfooter()
  {
    include($_SERVER['DOCUMENT_ROOT'] . '//components/footer.php');
    echo $this->loadScriptFooter();
    echo '<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDIfSyryL0vRpxCCDilpmgnYhC98A_E8EQ&callback=initializeMap&libraries=places&v=weekly" defer></script>';
  }

  // Load script footer
  public function loadScriptFooter()
  {
    $script =  '
        <!-- JS Global Compulsory -->
        <script src="' . base_url_web() . 'public/vendor/jquery/dist/jquery.min.js"></script>
        <script src="' . base_url_web() . 'public/vendor/jquery-migrate/dist/jquery-migrate.min.js"></script>
        <script src="' . base_url_web() . 'public/vendor/popper.js/dist/umd/popper.min.js"></script>
        <script src="' . base_url_web() . 'public/vendor/bootstrap/bootstrap.min.js"></script>

        <!-- JS Implementing Plugins -->
        <script src="' . base_url_web() . 'public/vendor/gmaps/gmaps.min.js"></script>
        <script src="' . base_url_web() . 'public/vendor/ion-rangeslider/js/ion.rangeSlider.min.js"></script>
        <script src="' . base_url_web() . 'public/vendor/custombox/dist/custombox.min.js"></script>
        <script src="' . base_url_web() . 'public/vendor/custombox/dist/custombox.legacy.min.js"></script>
        <script src="' . base_url_web() . 'public/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="' . base_url_web() . 'public/vendor/hs-megamenu/src/hs.megamenu.js"></script>
        <script src="' . base_url_web() . 'public/vendor/jquery-validation/dist/jquery.validate.min.js"></script>
        <script src="' . base_url_web() . 'public/vendor/flatpickr/dist/flatpickr.min.js"></script>
        <script src="' . base_url_web() . 'public/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
        <script src="' . base_url_web() . 'public/vendor/slick-carousel/slick/slick.js"></script>
        <script src="' . base_url_web() . 'public/vendor/dzsparallaxer/dzsparallaxer.js"></script>
        <script src="' . base_url_web() . 'public/vendor/fancybox/jquery.fancybox.min.js"></script>
        <script src="' . base_url_web() . 'public/vendor/appear.js"></script>

        <!-- JS MyTravel -->
        <script src="' . base_url_web() . 'public/js/hs.core.js"></script>
        <script src="' . base_url_web() . 'public/js/ggmap.js"></script>
        <script src="' . base_url_web() . 'public/js/api.js"></script>
        <script src="' . base_url_web() . 'public/js/star-rating.js"></script>
        <script src="' . base_url_web() . 'public/js/components/hs.header.js"></script>
        <script src="' . base_url_web() . 'public/js/components/hs.unfold.js"></script>
        <script src="' . base_url_web() . 'public/js/components/hs.validation.js"></script>
        <script src="' . base_url_web() . 'public/js/components/hs.show-animation.js"></script>
        <script src="' . base_url_web() . 'public/js/components/hs.range-datepicker.js"></script>
        <script src="' . base_url_web() . 'public/js/components/hs.selectpicker.js"></script>
        <script src="' . base_url_web() . 'public/js/components/hs.go-to.js"></script>
        <script src="' . base_url_web() . 'public/js/components/hs.slick-carousel.js"></script>
        <script src="' . base_url_web() . 'public/js/components/hs.quantity-counter.js"></script>
        <script src="' . base_url_web() . 'public/js/components/hs.range-slider.js"></script>
        <script src="' . base_url_web() . 'public/js/components/hs.g-map.js"></script>
        <script src="' . base_url_web() . 'public/js/components/hs.modal-window.js"></script>
        <script src="' . base_url_web() . 'public/js/components/hs.malihu-scrollbar.js"></script>

        <script src="' . base_url_web() . 'public/js/components/hs.fancybox.js"></script>
        <script src="' . base_url_web() . 'public/js/components/hs.scroll-nav.js"></script>
        <script src="' . base_url_web() . 'public/js/components/hs.sticky-block.js"></script>

        <!-- JS Plugins Init. -->
        <script>
        $(window).on("load", function () {
        // initialization of HSMegaMenu component
        $(".js-mega-menu").HSMegaMenu({
        event: "hover",
        pageContainer: $(".container"),
        breakpoint: 1199.98,
        hideTimeOut: 0,
        });

        // Page preloader
        setTimeout(function () {
          $("#jsPreloader").fadeOut(500);
        }, 800);
      });

      $(document).on("ready", function () {
        // initialization of header
        $.HSCore.components.HSHeader.init($("#header"));

        // initialization of google map
        function initMap() {
          $.HSCore.components.HSGMap.init(".js-g-map");
        }

        // initialization of unfold component
        $.HSCore.components.HSUnfold.init($("[data-unfold-target]"));

        // initialization of autonomous popups
        $.HSCore.components.HSModalWindow.init("[data-modal-target]", ".js-modal-window", {
          autonomous: true,
        });

        // initialization of show animations
        $.HSCore.components.HSShowAnimation.init(".js-animation-link");

        // initialization of datepicker
        $.HSCore.components.HSRangeDatepicker.init(".js-range-datepicker");

        // initialization of forms
        $.HSCore.components.HSRangeSlider.init(".js-range-slider");

        // initialization of select
        $.HSCore.components.HSSelectPicker.init(".js-select");

        // initialization of malihu scrollbar
        $.HSCore.components.HSMalihuScrollBar.init($(".js-scrollbar"));

        // initialization of quantity counter
        $.HSCore.components.HSQantityCounter.init(".js-quantity");

        // initialization of slick carousel
        $.HSCore.components.HSSlickCarousel.init(".js-slick-carousel");

        // initialization of go to
        $.HSCore.components.HSGoTo.init(".js-go-to");
      });
        </script>
        ';
    return $script;
  }

  // Load map init in website
  function loadMapInit($hotels = [], $hotel = null)
  {
    echo '<script>
        // Khởi tạo map
        async function initializeMap() {
            await initMap(' . $hotels . ', ' . $hotel . ');
        }
        initializeMap();
    </script>';
  }
}
