<?
class Hotel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    // Lấy danh sách khách sạn với tùy chọn lọc, sắp xếp và phân trang
    public function getHotels($filter = [], $sort = '', $page = 1, $perPage = 15)
    {
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT h.*,
        c.cit_name ,
        d.dis_name,d.dis_address_map,
        JSON_ARRAYAGG(img.hti_name) AS images
        FROM hotel h
        LEFT JOIN city c ON h.hot_city_id = c.cit_id
        LEFT JOIN district d ON h.hot_district_id = d.dis_id
        LEFT JOIN hotel_image img ON h.hot_id = img.hti_hotel_id
        WHERE 1=1 ";

        // Áp dụng các bộ lọc
        // Escape các giá trị đầu vào để ngăn chặn SQL injection
        if (!empty($filter['s'])) {
            $s = $filter['s'];
            $sql .= " AND h.hot_name LIKE '%$s%' OR d.dis_name LIKE '%$s%'";
        }

        // Áp dụng lọc theo khoảng giá
        if (!empty($filter['price_range'])) {
            list($minPrice, $maxPrice) = explode(';', $filter['price_range']);
            $minPrice = intval($minPrice);
            $maxPrice = intval($maxPrice);
            $sql .= " AND h.hot_price BETWEEN $minPrice AND $maxPrice";
        }

        // Rating
        if (!empty($filter['rating'])) {
            $rating = intval($filter['rating']);
            $sql .= " AND h.hot_star = $rating";
        }

        // Kiểu KS
        if (!empty($filter['type'])) {
            $type = $filter['type'];
            $sql .= " AND h.hot_type = '$type'";
        }

        // Kiểu KS
        if (!empty($filter['district'])) {
            $district = $filter['district'];
            $sql .= " AND d.dis_name = '$district'";
        }

        if (!empty($filter['amenity'])) {
            // Escape các tên tiện ích và đưa chúng vào chuỗi
            $amenityNames = array_map(function ($amenity) {
                return "'" . $amenity . "'";
            }, $filter['amenity']);

            $amenityNamesString = implode(',', $amenityNames);

            $sql .= " AND h.hot_id IN (
                        SELECT ha.hta_hotel_id
                        FROM hotel_amenities ha
                        JOIN amenity a ON ha.hta_amenity_id = a.ame_id
                        WHERE a.ame_name IN ($amenityNamesString)
                        GROUP BY ha.hta_hotel_id
                        HAVING COUNT(DISTINCT a.ame_name) = " . count($filter['amenity']) . "
                      )";
        }


        $sql .= " GROUP BY h.hot_id, c.cit_name, d.dis_name";

        // Áp dụng sắp xếp
        if (!empty($sort)) {

            switch ($sort) {
                case 'price-asc':
                    $sql .= " ORDER BY h.hot_price ASC";
                    break;
                case 'price-desc':
                    $sql .= " ORDER BY h.hot_price DESC";
                    break;
                default:
                    $sql .= " ORDER BY h.hot_price ASC";
            }
        }

        // Thêm phân trang
        $sql .= " LIMIT " . intval($perPage) . " OFFSET " . intval($offset);

        // Thực hiện câu lệnh truy vấn
        $this->DB->query($sql);

        // Lấy kết quả dưới dạng mảng
        $hotels = $this->DB->toArray();

        // Lặp qua mảng hotel để thêm vào review và average
        foreach ($hotels as &$hotel) {
            $review = $this->getAverageReviewByHotel($hotel['hot_id']);
            $hotel['average_rating'] = $review['average_rating'];
            $hotel['total_reviews'] = $review['total_reviews'];
        }

        return [
            'hotels' => $hotels,
            'totals' => $this->getTotalHotels($filter)
        ];
    }

    // Lấy tổng số khách sạn theo filter
    public function getTotalHotels($filter = [])
    {

        $sql = "SELECT COUNT(DISTINCT h.hot_id) AS total
                FROM hotel h
                LEFT JOIN city c ON h.hot_city_id = c.cit_id
                LEFT JOIN district d ON h.hot_district_id = d.dis_id
                WHERE 1=1";

        // Áp dụng các bộ lọc
        // Escape các giá trị đầu vào để ngăn chặn SQL injection
        if (!empty($filter['s'])) {
            $s = $filter['s'];
            $sql .= " AND h.hot_name LIKE '%$s%' OR d.dis_name LIKE '%$s%'";
        }

        // Áp dụng lọc theo khoảng giá
        if (!empty($filter['price_range'])) {
            list($minPrice, $maxPrice) = explode(';', $filter['price_range']);
            $minPrice = intval($minPrice);
            $maxPrice = intval($maxPrice);
            $sql .= " AND h.hot_price BETWEEN $minPrice AND $maxPrice";
        }

        // Rating
        if (!empty($filter['rating'])) {
            $rating = intval($filter['rating']);
            $sql .= " AND h.hot_star = $rating";
        }

        // Kiểu KS
        if (!empty($filter['type'])) {
            $type = $filter['type'];
            $sql .= " AND h.hot_type = '$type'";
        }

        // Kiểu KS
        if (!empty($filter['district'])) {
            $district = $filter['district'];
            $sql .= " AND d.dis_name = '$district'";
        }

        if (!empty($filter['amenity'])) {
            // Escape các tên tiện ích và đưa chúng vào chuỗi
            $amenityNames = array_map(function ($amenity) {
                return "'" . $amenity . "'";
            }, $filter['amenity']);

            $amenityNamesString = implode(',', $amenityNames);

            $sql .= " AND h.hot_id IN (
                        SELECT ha.hta_hotel_id
                        FROM hotel_amenities ha
                        JOIN amenity a ON ha.hta_amenity_id = a.ame_id
                        WHERE a.ame_name IN ($amenityNamesString)
                        GROUP BY ha.hta_hotel_id
                        HAVING COUNT(DISTINCT a.ame_name) = " . count($filter['amenity']) . "
                      )";
        }

        // Thực hiện câu lệnh truy vấn
        $this->DB->query($sql);

        // Lấy kết quả dưới dạng mảng
        $total = $this->DB->toArray()[0]['total'];

        return $total;
    }

    // Lấy ra thông tin chi tiết khách sạn
    public function getDetail($slug)
    {
        $sql = "SELECT h.*,
        c.cit_name,
        d.dis_name,d.dis_address_map,
        JSON_ARRAYAGG(img.hti_name) AS images
        FROM hotel h
        LEFT JOIN city c ON h.hot_city_id = c.cit_id
        LEFT JOIN district d ON h.hot_district_id = d.dis_id
        LEFT JOIN hotel_image img ON h.hot_id = img.hti_hotel_id
        WHERE h.hot_slug = '$slug'
        GROUP BY h.hot_id, c.cit_name, d.dis_name";

        $this->DB->query($sql);

        $result =  $this->DB->getOne();
        if (!$result) {
            return false;
        }

        // Lấy ra danh sách tiện ích của khách sạn
        $result['amenities'] = $this->getAmenities($result['hot_id']);

        $review_result = $this->getAverageReviewByHotel($result['hot_id']);
        $result['reviews'] = $review_result['reviews'];
        $result['average_rating'] = $review_result['average_rating'];
        $result['total_reviews'] = $review_result['total_reviews'];
        $result['averages'] = $review_result['averages'];
        return $result;
    }

    // Lấy ra danh sách tiện ích theo khách sạn
    public function getAmenities($hotelId)
    {
        $sql = "SELECT a.*
                FROM hotel_amenities ha
                LEFT JOIN amenity a ON ha.hta_amenity_id = a.ame_id
                WHERE ha.hta_hotel_id = $hotelId";

        $this->DB->query($sql);
        return $this->DB->toArray();
    }

    // Lấy ra 15 danh sách khác sạn lân cận bỏ qua hotel hiện tại
    public function getNearbyHotels($district_id, $hotel_id)
    {
        $sql = "SELECT h.*,
        c.cit_name ,
        d.dis_name,d.dis_address_map
        FROM hotel h
        LEFT JOIN city c ON h.hot_city_id = c.cit_id
        LEFT JOIN district d ON h.hot_district_id = d.dis_id
        WHERE 1=1 AND h.hot_id <> $hotel_id AND h.hot_district_id = $district_id GROUP BY h.hot_id, c.cit_name, d.dis_name";

        $hotels =  $this->DB->query($sql)->toArray();
        // Lặp qua mảng hotel để thêm vào review và average
        foreach ($hotels as &$hotel) {
            $review = $this->getAverageReviewByHotel($hotel['hot_id']);
            $hotel['average_rating'] = $review['average_rating'];
            $hotel['total_reviews'] = $review['total_reviews'];
        }
        return $hotels;
    }

    // Lấy ra danh sách phòng 
    public function getRooms($hotelId)
    {
        $sql = "SELECT *
                FROM room
                WHERE roo_hotel_id = $hotelId";

        $this->DB->query($sql);
        return $this->DB->toArray();
    }

    // Lấy ra chi tiết phòng
    public function getRoomDetail($roomId)
    {
        $sql = "SELECT *
                FROM room
                WHERE roo_id = $roomId";

        $this->DB->query($sql);
        return $this->DB->getOne();
    }

    // Lấy dánh sách khách sạn theo city id
    public function getHotelsByCity($cityId)
    {
        $sql = "SELECT h.*,
        c.cit_name,
        d.dis_name,d.dis_address_map
        FROM hotel h
        LEFT JOIN city c ON h.hot_city_id = c.cit_id
        LEFT JOIN district d ON h.hot_district_id = d.dis_id
        WHERE 1=1 AND h.hot_city_id =" . $cityId;
        $hotels = $this->DB->query($sql)->toArray();
        return $hotels;
    }

    // Tính trung bình review của khách sạn
    public function getAverageReviewByHotel($hotelId)
    {
        $reviews  = $this->getAllReviewsByHotel($hotelId);
        // Tổng số review
        $total_reviews = count($reviews);

        // Tính trung bình điểm review của khách sạn đó
        $average_rating = 0;
        $averages = [
            "rev_cleanliness" => 0,
            "rev_amenities" => 0,
            "rev_money" => 0,
            "rev_service" => 0,
            "rev_customer_support" => 0,
            "rev_location" => 0
        ];

        if ($total_reviews > 0) {
            foreach ($reviews as $review) {
                $average_rating += $review['rev_average'];
            }
            $average_rating = number_format($average_rating / $total_reviews, 1);
            // Tính tổng cho mỗi hạng mục
            foreach ($reviews as $review) {
                $averages['rev_cleanliness'] += $review['rev_cleanliness'];
                $averages['rev_amenities'] += $review['rev_amenities'];
                $averages['rev_money'] += $review['rev_money'];
                $averages['rev_service'] += $review['rev_service'];
                $averages['rev_customer_support'] += $review['rev_customer_support'];
                $averages['rev_location'] += $review['rev_location'];
            }

            // Tính trung bình cho mỗi hạng mục
            foreach ($averages as $key => $average) {
                $averages[$key] = number_format($average / $total_reviews, 1); // Cập nhật giá trị trung bình
            }
        }
        return [
            "average_rating" => $average_rating,
            "averages" => $averages,
            "total_reviews" => $total_reviews,
            "reviews" => $reviews
        ];
    }

    // Lấy ra tât cả review của khách sạn 
    public function getAllReviewsByHotel($hotelId)
    {
        // Lấy ra danh sách review của khách sạn 4.5 trở lên
        $reviews = $this->DB->query('SELECT * FROM reviews WHERE rev_hotel_id = ' . $hotelId . ' AND rev_average > 4.5 LIMIT 10')->toArray();
        return $reviews;
    }

    // Lấy 10 khách sạn hot, khuyến mại, active
    public function getPopularHotels($city_id)
    {
        $sql = 'SELECT h.*, d.dis_address_map
            FROM hotel h
            JOIN district d ON h.hot_district_id = d.dis_id
            WHERE h.hot_hot = 1 AND h.hot_active = 1 AND h.hot_promotion = 1 AND  h.hot_page_cover IS NOT NULL AND h.hot_city_id = ' . $city_id . '
            LIMIT 10';
        $hotels = $this->DB->query($sql)->toArray();
        // Lặp qua mảng hotel để thêm vào review và average
        foreach ($hotels as &$hotel) {
            $review = $this->getAverageReviewByHotel($hotel['hot_id']);
            $hotel['average_rating'] = $review['average_rating'];
            $hotel['total_reviews'] = $review['total_reviews'];
        }
        return $hotels;
    }

    // Lấy danh sách khách sạn theo hạng sao
    public function getHotelsByStar($star)
    {
        $sql = 'SELECT h.*, d.dis_address_map FROM hotel h JOIN district d ON h.hot_district_id = d.dis_id WHERE h.hot_active = 1 AND h.hot_promotion = 1 AND h.hot_hot = 1 AND h.hot_star = ' . $star;
        $hotels = $this->DB->query($sql)->toArray();
        // Lặp qua mảng hotel để thêm vào review và average
        foreach ($hotels as &$hotel) {
            $review = $this->getAverageReviewByHotel($hotel['hot_id']);
            $hotel['average_rating'] = $review['average_rating'];
            $hotel['total_reviews'] = $review['total_reviews'];
        }
        return $hotels;
    }

    // Lấy danh sách khách sạn theo tiện ích
    public function getHotelsByAmenity($amenityId)
    {
        $sql = 'SELECT h.*, d.dis_address_map FROM hotel h JOIN district d ON h.hot_district_id = d.dis_id JOIN hotel_amenities ha ON h.hot_id = ha.hta_hotel_id WHERE h.hot_active = 1 AND h.hot_promotion = 1 AND ha.hta_amenity_id = ' . $amenityId;
        $hotels = $this->DB->query($sql)->toArray();
        // Lặp qua mảng hotel để thêm vào review và average
        foreach ($hotels as &$hotel) {
            $review = $this->getAverageReviewByHotel($hotel['hot_id']);
            $hotel['average_rating'] = $review['average_rating'];
            $hotel['total_reviews'] = $review['total_reviews'];
        }
        return $hotels;
    }
}
