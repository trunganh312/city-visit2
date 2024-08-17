<?
class District
{
    private $table_name = 'district';
    private $DB;


    public function  __construct($DB)
    {
        $this->DB = $DB;
    }

    // Lấy ra tất cả huyện theo tỉnh
    public function getAllDistrictsByCity($city_id)
    {

        $sql = "SELECT * FROM " . $this->table_name . " WHERE dis_city_id= " . $city_id;
        return  $this->DB->query($sql)->toArray();
    }

    // Lấy ra tất cả huyện không có phân trang 
    public function getAll()
    {
        $sql = "SELECT d.*, c.cit_id, c.cit_name FROM " . $this->table_name . " d LEFT JOIN city c ON d.dis_city_id = c.cit_id";
        return  $this->DB->query($sql)->toArray();
    }

    // Lấy dan sách quận huyện có phân trang, query
    public function getAllDistrictsByMeta($filter = [], $page = 1, $perPage = 15)
    {
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT d.*,
        c.cit_name
        FROM district d
        LEFT JOIN city c ON d.dis_city_id = c.cit_id
        WHERE 1=1 ";

        // Áp dụng các bộ lọc
        if (!empty($filter['name'])) {
            $name = $filter['name'];
            $sql .= " AND d.dis_name LIKE '%$name%' ";
        }

        $sql .= " GROUP BY d.dis_id, c.cit_name";

        // Thêm phân trang
        $sql .= " LIMIT " . intval($perPage) . " OFFSET " . intval($offset);

        // Thực hiện câu lệnh truy vấn
        $this->DB->query($sql);

        // Lấy kết quả dưới dạng mảng
        $districts = $this->DB->toArray();

        return [
            'districts' => $districts,
            'totals' => $this->getTotalDistrict($filter)
        ];
    }

    // Lấy tổng số quận huyện theo filter
    public function getTotalDistrict($filter = [])
    {
        $sql = "SELECT COUNT(DISTINCT d.dis_id) AS total
        FROM district d
        LEFT JOIN city c ON d.dis_city_id = c.cit_id
        WHERE 1=1 ";

        // Áp dụng các bộ lọc
        if (!empty($filter['name'])) {
            $name = $filter['name'];
            $sql .= " AND d.dis_name LIKE '%$name%' ";
        }

        // Thực hiện câu lệnh truy vấn
        $this->DB->query($sql);

        // Lấy kết quả dưới dạng mảng
        $total = $this->DB->toArray()[0]['total'];

        return $total;
    }

    // Thêm mới quận huyện
    public function addDistrict($data)
    {
        $sql = "INSERT INTO $this->table_name 
        (dis_name, dis_name_other, dis_content, dis_active, dis_hot, dis_address_map, dis_lat_center, dis_lng_center, dis_city_id) 
        VALUES (
        '{$data['dis_name']}',
        '{$data['dis_name_other']}',
        '{$data['dis_content']}',
        '{$data['dis_active']}',
        '{$data['dis_hot']}',
        '{$data['dis_address_map']}',
        '{$data['dis_lat_center']}',
        '{$data['dis_lng_center']}',
        '{$data['dis_city_id']}'
        )";
        $result =  $this->DB->query($sql);
        if ($result) {
            return true;
        }
        return false;
    }

    // Xóa
    public function deleteDistrict($id)
    {
        $sql = "DELETE FROM $this->table_name WHERE dis_id = " . intval($id);
        return  $this->DB->query($sql);
    }

    // Lấy chi tiết 1 huyện
    public function getDistrictById($id)
    {
        $sql = "SELECT d.*, c.cit_id, c.cit_name FROM $this->table_name d LEFT JOIN city c ON c.cit_id = d.dis_city_id WHERE dis_id = " . intval($id);
        return  $this->DB->query($sql)->getOne();
    }

    // Update
    public function updateDistrict($district_id, $data)
    {
        $sql = "UPDATE $this->table_name SET
         dis_name = '{$data['dis_name']}',
         dis_name_other = '{$data['dis_name_other']}',
         dis_active = '{$data['dis_active']}',
         dis_hot = '{$data['dis_hot']}',
         dis_address_map = '{$data['dis_address_map']}',
         dis_content = '{$data['dis_content']}',
         dis_city_id = '{$data['dis_city_id']}',
         dis_lat_center = '{$data['dis_lat_center']}',
         dis_lng_center = '{$data['dis_lng_center']}' WHERE dis_id = $district_id";
        $result = $this->DB->query($sql);

        return $result;
    }
}
