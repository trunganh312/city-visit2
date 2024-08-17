<?
include_once $path_models . 'Hotel.php';
class HotelController
{
    private $hotelModel;

    public function __construct()
    {
        $this->hotelModel = new Hotel;
    }

    // SEARCH
    public function search()
    {
        $filter = [];
        $sort = '';

        // Search theo tên ks hoặc tên huyện
        if (!empty($_GET['s'])) {
            $filter['s'] = $_GET['s'];
        }

        // Filter theo hạng sao
        if (!empty($_GET['rating'])) {
            $filter['rating'] = $_GET['rating'];
        }

        // Filter theo kiểu khách sạn
        if (!empty($_GET['type'])) {
            $filter['type'] = $_GET['type'];
        }

        // Tìm theo khoảng giá
        if (!empty($_GET['price_range'])) {
            $filter['price_range'] = $_GET['price_range'];
        }

        // Tìm theo quận huyện
        if (!empty($_GET['district'])) {
            $filter['district'] = $_GET['district'];
        }

        // Lọc theo ID tiện nghi
        if (!empty($_GET['amenity'])) {
            $filter['amenity'] =  $_GET['amenity'];
        }

        // Lấy sort từ query string
        if (!empty($_GET['sort'])) {
            $sort = $_GET['sort'];
        }



        // Xử lý các tham số phân trang
        $page       = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Số lượng item trên 1 trang
        $perPage    = 15;

        // Data hotel 
        $data       =  $this->hotelModel->getHotels($filter,  $sort, $page, $perPage);
        // Danh sách KS
        $hotels     = $data['hotels'];



        // Tổng số KS được tìm thấy
        $totals = $data['totals'];

        // Tổng số trang
        $totalPages = ceil($totals / $perPage);

        require_once __DIR__ . '/../views/list/listing.php';
    }

    // GET DETAIL HOTEL
    public function getDetail($slug)
    {
        $hotel = $this->hotelModel->getDetail($slug);
        return $hotel;
    }

    // GET LIST HOTEL NEARBY
    public function getNearbyHotels($district_id, $hotel_id)
    {
        $hotels = $this->hotelModel->getNearbyHotels($district_id, $hotel_id);
        return $hotels;
    }

    // GET ROOMS
    public function getRooms($hotel_id)
    {
        $rooms = $this->hotelModel->getRooms($hotel_id);
        return $rooms;
    }

    // Get list hotel by city id
    public function getHotelsByCityId($city_id)
    {
        $hotels = $this->hotelModel->getHotelsByCity($city_id);
        return $hotels;
    }

    // Get 10 hotel hot, khuyến mại, được active
    public function getPopularHotels($city_id)
    {
        $hotels = $this->hotelModel->getPopularHotels($city_id);
        return $hotels;
    }

    // Lấy danh sách khách sạn theo hạng sao
    public function getHotelsByStar($star)
    {
        $hotels = $this->hotelModel->getHotelsByStar($star);
        return $hotels;
    }

    // Lấy danh sách khách sạn theo tiện ích
    public function getHotelsByAmenity($amenity_id)
    {
        $hotels = $this->hotelModel->getHotelsByAmenity($amenity_id);
        return $hotels;
    }
}
