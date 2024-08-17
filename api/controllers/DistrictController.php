<?
require_once __DIR__ . '/../models/District.php';
require_once __DIR__ . '/../helpers/response.php';

class DistrictController
{
    private $district;

    public function __construct($DB)
    {
        $this->district = new District($DB);
    }

    public function getAllDistricts()
    {
        $districts = $this->district->getAll();
        if (!empty($districts)) {
            $response = new Response(200, 'Call api thành công!!', $districts, null);
            return $response->sendResponse();
        } else {
            $response = new Response(404, 'Không tìm thấy huyện nào!!', null, null);
            return $response->sendResponse();
        }
    }

    // lấy huyện theo city_id
    public function getDistrictsByCityId()
    {
        $city_id = $_GET['id'];
        if (empty($city_id)) {
            $response = new Response(400, 'city_id không được để trống!!', null, null);
            return $response->sendResponse();
        }
        $districts = $this->district->getAllDistrictsByCity($city_id);
        if (!empty($districts)) {
            $response = new Response(200, 'Call api thành công!!', $districts, null);
            return $response->sendResponse();
        } else {
            $response = new Response(404, 'Không tìm thấy huyện nào!!', null, null);
            return $response->sendResponse();
        }
    }


    // Lấy danh sách quận huyện có phân trang
    public function getDistrictsWithMeta()
    {
        $filter = [];

        // Search theo tên tên huyện
        if (!empty($_GET['name'])) {
            $filter['name'] = $_GET['name'];
        }
        // Xử lý các tham số phân trang
        $page       = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        // Số lượng item trên 1 trang
        $perPage    = 10;

        // Data hotel 
        $result       =  $this->district->getAllDistrictsByMeta($filter, $page, $perPage);

        // Tổng số KS được tìm thấy
        $totals = $result['totals'];
        $districts = $result['districts'];

        // Tổng số trang
        $totalPages = ceil($totals / $perPage);
        $meta = new Meta($page, $totalPages, $totals);
        if ($result) {
            $response = new Response(200, 'Call api thành công', $districts, $meta);
            return $response->sendResponse();
        } else {
            $response = new Response(400, 'Call api thất bại', null, null);
            return $response->sendResponse();
        }
    }

    // Thêm mới quận huyện
    public function addDistrict()
    {
        // Thêm các header CORS
        header('Access-Control-Allow-Origin: *'); // Hoặc thay * b��ng domain cụ thể của bạn để bảo mật hơn
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Content-Type: application/json');

        // Lấy dữ liệu JSON từ yêu cầu
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data)) {
            if ($this->district->addDistrict($data)) {
                $response = new Response(200, 'Call api thành công', null, null);
                return $response->sendResponse();
            } else {
                $response = new Response(400, 'Call api thất bại');
                return $response->sendResponse();
            }
        }
    }

    // Xóa 
    public function deleteDistrict($id)
    {
        header('Access-Control-Allow-Origin: *'); // Hoặc thay * b��ng domain cụ thể của bạn để bảo mật hơn
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Content-Type: application/json');
        if ($this->district->deleteDistrict($id)) {
            $response = new Response(200, 'Call api thành công', null, null);
            return $response->sendResponse();
        } else {
            $response = new Response(400, 'Call api thất bại');
            return $response->sendResponse();
        }
    }

    // Lấy thông tin chi tiết
    public function getDistrictById($id)
    {
        header('Access-Control-Allow-Origin: *'); // Hoặc thay * b��ng domain cụ thể của bạn để bảo mật hơn
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Content-Type: application/json');
        $district = $this->district->getDistrictById($id);
        if ($district) {
            $response = new Response(200, 'Call api thành công', $district, null);
            return $response->sendResponse();
        } else {
            $response = new Response(404, 'Không tìm thấy huyện nào!!', null, null);
            return $response->sendResponse();
        }
    }

    // Update 
    // Update hotel
    public function updateDistrict($id)
    {
        // Thêm các header CORS
        header('Access-Control-Allow-Origin: *'); // Hoặc thay * b��ng domain cụ thể của bạn để bảo mật hơn
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Content-Type: application/json');

        // Lấy dữ liệu JSON từ yêu cầu
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data)) {
            $districtId = $id;
            if ($this->district->updateDistrict($districtId, $data)) {
                $response = new Response(200, 'Call api thành công', null, null);
                return $response->sendResponse();
            } else {
                $response = new Response(400, 'Call api thất bại');
                return $response->sendResponse();
            }
        } else {
            echo json_encode(['error' => 'Missing required parameters']);
            return;
        }
    }
}
