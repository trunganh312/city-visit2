<?php
// models/Hotel.php

class Hotel
{
    private $DB;
    private $table_name = "hotel";

    public $hot_name;
    public $hot_lat;
    public $hot_lng;
    public $hot_content;
    public $hot_page_cover;
    public $hot_active;
    public $hot_name_other;
    public $hot_priority;
    public $hot_hot;
    public $hot_address_map;
    public $hot_district_id;
    public $hot_city_id;
    public $hot_price;
    public $hot_star;
    public $hot_promotion;
    public $hot_slug;


    public function __construct($DB)
    {
        $this->DB = $DB;
    }

    /** KHÁCH SẠN */
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
            $sql .= " AND h.hot_name LIKE '%$s%'";
        }

        // Áp dụng lọc theo khoảng giá
        if (!empty($filter['price_range'])) {
            list($minPrice, $maxPrice) = explode(';', $filter['price_range']);
            $minPrice = intval($minPrice);
            $maxPrice = intval($maxPrice);
            $sql .= " AND h.hot_price BETWEEN $minPrice AND $maxPrice";
        }

        // Rating
        if (!empty($filter['star'])) {
            $star = intval($filter['star']);
            $sql .= " AND h.hot_star = $star";
        }

        // Kiểu KS
        if (!empty($filter['type'])) {
            $type = $filter['type'];
            $sql .= " AND h.hot_type = '$type'";
        }

        if (!empty($filter['district'])) {
            $district = $filter['district'];
            $sql .= " AND d.dis_name LIKE '%$district%'";
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
        if (!empty($filter['star'])) {
            $star = intval($filter['star']);
            $sql .= " AND h.hot_star = $star";
        }

        // Kiểu KS
        if (!empty($filter['type'])) {
            $type = $filter['type'];
            $sql .= " AND h.hot_type = '$type'";
        }

        if (!empty($filter['district'])) {
            $district = $filter['district'];
            $sql .= " AND d.dis_name LIKE '%$district%'";
        }

        // Thực hiện câu lệnh truy vấn
        $this->DB->query($sql);

        // Lấy kết quả dưới dạng mảng
        $total = $this->DB->toArray()[0]['total'];

        return $total;
    }

    // Update status
    public function updateStatus($hotId, $status, $column)
    {
        $sql = "UPDATE $this->table_name SET $column = $status WHERE hot_id = $hotId";
        return $this->DB->query($sql);
    }

    // Thêm mới khách sạn
    public function addHotel($data)
    {
        $sql = "INSERT INTO $this->table_name 
        (hot_name, hot_name_other, hot_slug, hot_active, hot_hot, hot_promotion, hot_lat, hot_lng, hot_content, hot_district_id, hot_city_id, hot_price, hot_star, hot_type, hot_address_map) 
        VALUES (
        '{$data['hot_name']}',
        '{$data['hot_name_other']}',
        '{$data['hot_slug']}',
        '{$data['hot_active']}',
        '{$data['hot_hot']}',
        '{$data['hot_promotion']}',
        '{$data['hot_lat']}',
        '{$data['hot_lng']}',
        '{$data['hot_content']}',
        '{$data['hot_district_id']}',
        '{$data['hot_city_id']}',
        '{$data['hot_price']}',
        '{$data['hot_star']}',
        '{$data['hot_type']}',
        '{$data['hot_address_map']}'
        )";
        $hotel_id =  $this->DB->executeReturn($sql);

        $selectedAmenities = $data['amenities'];

        // Thêm tiện ochs đã chọn theo khách sạn
        foreach ($selectedAmenities as $amenityId) {
            $this->DB->execute("INSERT INTO hotel_amenities (hta_hotel_id, hta_amenity_id) VALUES ($hotel_id, '{$amenityId}')");
        }
        if ($hotel_id) {
            return true;
        } else {
            return false;
        }
    }

    // Cập nhật khách sạn
    public function updateHotel($hotel_id, $data)
    {
        $sql = "UPDATE $this->table_name SET
        hot_name = '{$data['hot_name']}',
        hot_name_other = '{$data['hot_name_other']}',
        hot_slug = '{$data['hot_slug']}',
        hot_active = '{$data['hot_active']}',
        hot_hot = '{$data['hot_hot']}',
        hot_promotion = '{$data['hot_promotion']}',
        hot_lat = '{$data['hot_lat']}',
        hot_lng = '{$data['hot_lng']}',
        hot_content = '{$data['hot_content']}',
        hot_district_id = '{$data['hot_district_id']}',
        hot_city_id = '{$data['hot_city_id']}',
        hot_price = '{$data['hot_price']}',
        hot_star = '{$data['hot_star']}',
        hot_type = '{$data['hot_type']}' WHERE hot_id = $hotel_id";
        $result = $this->DB->query($sql);
        if ($result) {
            // Xóa các tiện ích đã chọn cũ đi và thêm lại các tiện ích đã chọn mới
            $this->DB->query("DELETE FROM hotel_amenities WHERE hta_hotel_id = $hotel_id");
            $selectedAmenities = $data['amenities'];
            foreach ($selectedAmenities as $amenityId) {
                $this->DB->execute("INSERT INTO hotel_amenities (hta_hotel_id, hta_amenity_id) VALUES ($hotel_id, '{$amenityId}')");
            }
        }
        return $result;
    }

    // Lấy ra danh sách tiện nghi
    public function getAmenities()
    {
        $sql = "SELECT * FROM amenity";
        $this->DB->query($sql);
        return $this->DB->toArray();
    }

    // Lấy danh sách tiện ích của khách sạn
    public function getAmenitiesByHotel($hotel_id)
    {
        $sql = "SELECT a.ame_id, a.ame_name
        FROM amenity a
        JOIN hotel_amenities ha ON a.ame_id = ha.hta_amenity_id
        WHERE ha.hta_hotel_id = $hotel_id";
        $result =  $this->DB->query($sql)->toArray();
        return $result;
    }

    // Lấy thông tin chi tiết khách sạn
    // Lấy ra thông tin chi tiết khách sạn
    public function getDetail($id)
    {
        //  $sql = "SELECT h.*,
        //  c.cit_name,
        //  d.dis_name,d.dis_address_map,
        //  JSON_ARRAYAGG(img.hti_name) AS images
        //  FROM hotel h
        //  LEFT JOIN city c ON h.hot_city_id = c.cit_id
        //  LEFT JOIN district d ON h.hot_district_id = d.dis_id
        //  LEFT JOIN hotel_image img ON h.hot_id = img.hti_hotel_id
        //  WHERE h.hot_slug = '$slug'
        //  GROUP BY h.hot_id, c.cit_name, d.dis_name";

        $sql = "SELECT h.*,
        c.cit_name,
        d.dis_name,d.dis_address_map,
        JSON_ARRAYAGG(img.hti_name) AS images
        FROM hotel h
        LEFT JOIN city c ON h.hot_city_id = c.cit_id
        LEFT JOIN district d ON h.hot_district_id = d.dis_id
        LEFT JOIN hotel_image img ON h.hot_id = img.hti_hotel_id
        WHERE h.hot_id = '$id'
        GROUP BY h.hot_id, c.cit_name, d.dis_name";

        $this->DB->query($sql);

        $result =  $this->DB->getOne();
        if (!$result) {
            return false;
        }

        // Lấy ra danh sách tiện ích của khách sạn
        $result['amenities'] = $this->getAmenitiesByHotel($result['hot_id']);

        // $review_result = $this->getAverageReviewByHotel($result['hot_id']);
        // $result['reviews'] = $review_result['reviews'];
        // $result['average_rating'] = $review_result['average_rating'];
        // $result['total_reviews'] = $review_result['total_reviews'];
        // $result['averages'] = $review_result['averages'];
        return $result;
    }

    // Xóa khách sạn
    public function deleteHotel($id)
    {
        $sql = "DELETE FROM hotel WHERE hot_id = $id";
        return $this->DB->query($sql);
    }

    /** END OF KHÁCH SẠN */

    /** PHÒNG */
    // Lấy ra danh sách phòng 
    public function getRooms($filter = [],  $page = 1, $perPage = 15)
    {
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT r.*, h.hot_name, h.hot_id FROM room r  LEFT JOIN hotel h ON r.roo_hotel_id = h.hot_id WHERE 1=1";

        // Áp dụng các bộ lọc
        // Lọc tên phòng
        if (!empty($filter['name'])) {
            $name = $filter['name'];
            $sql .= " AND r.roo_name LIKE '%$name%' ";
        }

        // Lọc theo tên khách sạn
        if (!empty($filter['hotel_name'])) {
            $hotel_name = $filter['hotel_name'];
            $sql .= " AND h.hot_name LIKE '%$hotel_name%' ";
        }

        $sql .= " GROUP BY r.roo_id";

        // Thêm phân trang
        $sql .= " LIMIT " . intval($perPage) . " OFFSET " . intval($offset);

        // Lấy kết quả dưới dạng mảng
        $rooms = $this->DB->query($sql)->toArray();

        return [
            'rooms' => $rooms,
            'totals' => $this->getTotalRooms($filter)
        ];
    }

    // Lấy tổng số phòng theo filter
    public function getTotalRooms($filter = [])
    {

        $sql = "SELECT COUNT(DISTINCT r.roo_id) AS total FROM room r WHERE 1=1";

        // Áp dụng các bộ lọc
        // Lọc tên phòng
        if (!empty($filter['name'])) {
            $name = $filter['name'];
            $sql .= " AND r.roo_name LIKE '%$name%' ";
        }

        // Lọc theo tên khách sạn
        if (!empty($filter['hotel_name'])) {
            $hotel_name = $filter['hotel_name'];
            $sql .= " AND h.hot_name LIKE '%$hotel_name%' ";
        }

        // Thực hiện câu lệnh truy vấn
        $this->DB->query($sql);

        // Lấy kết quả dưới dạng mảng
        $total = $this->DB->toArray()[0]['total'];

        return $total;
    }

    // Xóa phòng
    public function deleteRoom($id)
    {
        $sql = "DELETE FROM room WHERE roo_id = $id";
        return $this->DB->query($sql);
    }

    // Thêm phòng
    public function addRoom($data)
    {
        $sql = "INSERT INTO room (roo_name, roo_hotel_id, roo_price, roo_active, roo_bed, roo_size_person, roo_description, roo_view, roo_size, roo_breakfast, roo_promotion) VALUES ('{$data['roo_name']}', '{$data['roo_hotel_id']}', '{$data['roo_price']}', '{$data['roo_active']}', '{$data['roo_bed']}', '{$data['roo_size_person']}', '{$data['roo_description']}', '{$data['roo_view']}', '{$data['roo_size']}', '{$data['roo_breakfast']}', '{$data['roo_promotion']}')";

        $room_id =  $this->DB->executeReturn($sql);

        $selectedAmenities = $data['amenities'];

        // Thêm tiện ích đã chọn theo phòng
        foreach ($selectedAmenities as $amenityId) {
            $this->DB->execute("INSERT INTO room_amenities (ram_room_id, ram_amenity_id) VALUES ($room_id, '{$amenityId}')");
        }
        if ($room_id) {
            return true;
        } else {
            return false;
        }
    }

    // get room by id
    public function getRoomById($id)
    {
        $sql = "SELECT * FROM room WHERE roo_id = '{$id}'";

        $this->DB->query($sql);

        $result =  $this->DB->getOne();
        if (!$result) {
            return false;
        }

        // Lấy ra danh sách tiện ích của khách sạn
        $result['amenities'] = $this->getRoomAmenities($result['roo_id']);

        return $result;
    }

    // Lấy tiện ích của phòng
    public function getRoomAmenities($room_id)
    {
        $sql = "SELECT a.ame_id, a.ame_name
        FROM amenity a
        JOIN room_amenities ra ON a.ame_id = ra.ram_amenity_id
        WHERE ra.ram_room_id = $room_id";
        $result =  $this->DB->query($sql)->toArray();
        return $result;
    }

    // Cập nhật phòng
    public function updateRoom($room_id, $data)
    {
        $sql = "UPDATE room SET
        roo_name = '{$data['roo_name']}',
        roo_price = '{$data['roo_price']}',
        roo_active = '{$data['roo_active']}',
        roo_bed = '{$data['roo_bed']}',
        roo_size_person = '{$data['roo_size_person']}',
        roo_description = '{$data['roo_description']}',
        roo_view = '{$data['roo_view']}',
        roo_size = '{$data['roo_size']}',
        roo_breakfast = '{$data['roo_breakfast']}',
        roo_promotion = '{$data['roo_promotion']}',
        roo_hotel_id = '{$data['roo_hotel_id']}'
        WHERE roo_id = $room_id";
        $result = $this->DB->query($sql);
        if ($result) {
            // Xóa các tiện ích đã chọn cũ đi và thêm lại các tiện ích đã chọn mới
            $this->DB->query("DELETE FROM room_amenities WHERE ram_room_id = $room_id");
            $selectedAmenities = $data['amenities'];
            foreach ($selectedAmenities as $amenityId) {
                $this->DB->execute("INSERT INTO room_amenities (ram_room_id, ram_amenity_id) VALUES ($room_id, '{$amenityId}')");
            }
        }
        return $result;
    }

    /** END OF PHÒNG */

    /** TIỆN ÍCH */
    // Lấy ra danh sách tiện ích
    public function getAmenitiesWithMeta($filter = [],  $page = 1, $perPage = 15)
    {
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT a.*, gr.amg_name FROM amenity a LEFT JOIN amenity_group gr ON a.ame_group_id = gr.amg_id WHERE 1=1";

        // Áp dụng các bộ lọc
        // Lọc tên tiện ích
        if (!empty($filter['name'])) {
            $name = $filter['name'];
            $sql .= " AND a.ame_name LIKE '%$name%' ";
        }

        $sql .= " GROUP BY a.ame_id";

        // Thêm phân trang
        $sql .= " LIMIT " . intval($perPage) . " OFFSET " . intval($offset);

        // Lấy kết quả dưới dạng mảng
        $amenities = $this->DB->query($sql)->toArray();

        return [
            'amenities' => $amenities,
            'totals' => $this->getTotalAmenity($filter)
        ];
    }

    // Lấy tổng số khách sạn theo filter
    public function getTotalAmenity($filter = [])
    {

        $sql = "SELECT COUNT(DISTINCT a.ame_id) AS total FROM amenity a WHERE 1=1";

        // Áp dụng các bộ lọc
        // Lọc tên tiện ích
        if (!empty($filter['name'])) {
            $name = $filter['name'];
            $sql .= " AND a.ame_name LIKE '%$name%' ";
        }

        // Thực hiện câu lệnh truy vấn
        $this->DB->query($sql);

        // Lấy kết quả dưới dạng mảng
        $total = $this->DB->toArray()[0]['total'];

        return $total;
    }

    // Xóa phòng
    public function deleteAmenity($id)
    {
        $sql = "DELETE FROM amenity WHERE ame_id = $id";
        return $this->DB->query($sql);
    }

    // Thêm phòng
    public function addAmenity($data)
    {
        $sql = "INSERT INTO amenity (ame_name, ame_group_id, ame_icon) VALUES ('{$data['ame_name']}', '{$data['ame_group_id']}', '{$data['ame_icon']}')";

        $amenity_id =  $this->DB->executeReturn($sql);


        if ($amenity_id) {
            return true;
        } else {
            return false;
        }
    }

    // get tiện ích by id
    public function getAmenityById($id)
    {
        $sql = "SELECT a.*, gr.amg_name, gr.amg_id FROM amenity a LEFT JOIN amenity_group gr ON a.ame_group_id = gr.amg_id WHERE a.ame_id = '{$id}'";

        $this->DB->query($sql);

        $result =  $this->DB->getOne();
        if (!$result) {
            return false;
        }
        return $result;
    }


    // Cập nhật tiện ích
    public function updateAmenity($ame_id, $data)
    {
        $sql = "UPDATE amenity SET
        ame_name = '{$data['ame_name']}',
        ame_icon = '{$data['ame_icon']}',
        ame_group_id = '{$data['ame_group_id']}'
        WHERE ame_id = $ame_id";
        $result = $this->DB->query($sql);
        return $result;
    }


    /** NHÓM */

    // Lấy tất cả nhóm
    public function getAllGroups()
    {
        $sql = "SELECT * FROM amenity_group";
        $result =  $this->DB->query($sql)->toArray();
        return $result;
    }

    /**END OF NHÓM */
}
