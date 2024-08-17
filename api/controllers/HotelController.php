<?
require_once __DIR__ . '/../models/Hotel.php';
require_once __DIR__ . '/../models/Upload.php';
require_once __DIR__ . '/../helpers/response.php';

class HotelController
{
    private $hotel;

    public function __construct($DB)
    {
        $this->hotel = new Hotel($DB);
    }

    public function getHotels()
    {
        $filter = [];
        $sort = '';

        // Search theo tên ks hoặc tên huyện
        if (!empty($_GET['s'])) {
            $filter['s'] = $_GET['s'];
        }

        // Filter theo hạng sao
        if (!empty($_GET['star'])) {
            $filter['star'] = $_GET['star'];
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

        // Lấy sort từ query string
        if (!empty($_GET['sort'])) {
            $sort = $_GET['sort'];
        }

        // Xử lý các tham số phân trang
        $page       = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        // Số lượng item trên 1 trang
        $perPage    = 10;

        // Data hotel 
        $result       =  $this->hotel->getHotels($filter,  $sort, $page, $perPage);

        // Tổng số KS được tìm thấy
        $totals = $result['totals'];
        $hotels = $result['hotels'];

        // Tổng số trang
        $totalPages = ceil($totals / $perPage);
        $meta = new Meta($page, $totalPages, $totals);
        if ($result) {
            $response = new Response(200, 'Call api thành công', $hotels, $meta);
            return $response->sendResponse();
        } else {
            $response = new Response(400, 'Call api thất bại', null, null);
            return $response->sendResponse();
        }
    }

    public function updateStatus()
    { // Thêm các header CORS
        header('Access-Control-Allow-Origin: *'); // Hoặc thay * bằng domain cụ thể của bạn để bảo mật hơn
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Content-Type: application/json');

        // Lấy dữ liệu JSON từ yêu cầu
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['id']) && isset($data['column']) && isset($data['status'])) {
            $hotelId = $data['id'];
            $column = $data['column'];
            $value = $data['status'];
            if ($this->hotel->updateStatus($hotelId, $value, $column)) {
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

    // Thêm mới khách sạn
    public function addHotel()
    {
        // Thêm các header CORS
        header('Access-Control-Allow-Origin: *'); // Hoặc thay * b��ng domain cụ thể của bạn để bảo mật hơn
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Content-Type: application/json');

        // Lấy dữ liệu JSON từ yêu cầu
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data)) {
            if ($this->hotel->addHotel($data)) {
                $response = new Response(200, 'Call api thành công', null, null);
                return $response->sendResponse();
            } else {
                $response = new Response(400, 'Call api thất bại');
                return $response->sendResponse();
            }
        }
    }

    // Lấy danh sách tiện nghi
    public function getAmenities()
    {
        $amenities = $this->hotel->getAmenities();
        if ($amenities) {
            $response = new Response(200, 'Call api thành công', $amenities, null);
            return $response->sendResponse();
        } else {
            $response = new Response(400, 'Call api thất bại');
            return $response->sendResponse();
        }
    }

    // Update hotel
    public function updateHotel($id)
    {
        // Thêm các header CORS
        header('Access-Control-Allow-Origin: *'); // Hoặc thay * b��ng domain cụ thể của bạn để bảo mật hơn
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Content-Type: application/json');

        // Lấy dữ liệu JSON từ yêu cầu
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data)) {
            $hotelId = $id;
            if ($this->hotel->updateHotel($hotelId, $data)) {
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

    // Lấy chi tiết khách sạn
    public function getHotelDetails($id)
    {
        $hotel = $this->hotel->getDetail($id);
        if ($hotel) {
            $response = new Response(200, 'Call api thành công', $hotel, null);
            return $response->sendResponse();
        } else {
            $response = new Response(400, 'Không tìm thấy khách sạn với ID: ' . $id);
            return $response->sendResponse();
        }
    }

    // Delete hotel
    public function deleteHotel($id)
    {
        // Thêm các header CORS
        header('Access-Control-Allow-Origin: *'); // Hoặc thay * b��ng domain cụ thể của bạn để bảo mật hơn
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Content-Type: application/json');

        if ($this->hotel->deleteHotel($id)) {
            $response = new Response(200, 'Call api thành công', null, null);
            return $response->sendResponse();
        } else {
            $response = new Response(400, 'Không tìm thấy khách sạn với ID: ' . $id);
            return $response->sendResponse();
        }
    }

    // Get rooms
    public function getRooms()
    {
        $filter = [];

        // Search theo tên phòng
        if (!empty($_GET['name'])) {
            $filter['name'] = $_GET['name'];
        }

        // Tìm theo tên KS
        if (!empty($_GET['hotel_name'])) {
            $filter['hotel_name'] = $_GET['hotel_name'];
        }

        // Xử lý các tham số phân trang
        $page       = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Số lượng item trên 1 trang
        $perPage    = 10;

        // Data hotel 
        $result       =  $this->hotel->getRooms($filter, $page, $perPage);

        // Tổng số KS được tìm thấy
        $totals = $result['totals'];
        $rooms = $result['rooms'];

        // Tổng số trang
        $totalPages = ceil($totals / $perPage);
        $meta = new Meta($page, $totalPages, $totals);
        if ($result) {
            $response = new Response(200, 'Call api thành công', $rooms, $meta);
            return $response->sendResponse();
        } else {
            $response = new Response(400, 'Call api thất bại', null, null);
            return $response->sendResponse();
        }
    }

    // Xóa rooms
    public function deleteRoom($id)
    {
        // Thêm các header CORS
        header('Access-Control-Allow-Origin: *'); // Hoặc thay * b��ng domain cụ thể của bạn để bảo mật hơn
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Content-Type: application/json');

        if ($this->hotel->deleteRoom($id)) {
            $response = new Response(200, 'Call api thành công', null, null);
            return $response->sendResponse();
        } else {
            $response = new Response(400, 'Không tìm thấy phòng với ID: ' . $id);
            return $response->sendResponse();
        }
    }

    // Thêm phòng
    public function addRoom()
    {
        // Thêm các header CORS
        header('Access-Control-Allow-Origin: *'); // Hoặc thay * b��ng domain cụ thể của bạn để bảo mật hơn
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Content-Type: application/json');

        // Lấy dữ liệu JSON từ yêu cầu
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data)) {
            if ($this->hotel->addRoom($data)) {
                $response = new Response(200, 'Call api thành công', null, null);
                return $response->sendResponse();
            } else {
                $response = new Response(400, 'Call api thất bại');
                return $response->sendResponse();
            }
        }
    }

    // Get room by id
    public function getRoomById($id)
    {
        $room = $this->hotel->getRoomById($id);
        if ($room) {
            $response = new Response(200, 'Call api thành công', $room, null);
            return $response->sendResponse();
        } else {
            $response = new Response(400, 'Không tìm thấy khách sạn với ID: ' . $id);
            return $response->sendResponse();
        }
    }

    // Update room
    public function updateRoom($id)
    {
        // Thêm các header CORS
        header('Access-Control-Allow-Origin: *'); // Hoặc thay * b��ng domain cụ thể của bạn để bảo mật hơn
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Content-Type: application/json');

        // Lấy dữ liệu JSON từ yêu cầu
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data)) {
            $roomId = $id;
            if ($this->hotel->updateRoom($roomId, $data)) {
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

    /** TIỆN ÍCH */

    // Get rooms
    public function getAmenitiesWithMeta()
    {
        $filter = [];

        // Search theo tên phòng
        if (!empty($_GET['name'])) {
            $filter['name'] = $_GET['name'];
        }

        // Xử lý các tham số phân trang
        $page       = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Số lượng item trên 1 trang
        $perPage    = 10;

        // Data hotel 
        $result       =  $this->hotel->getAmenitiesWithMeta($filter, $page, $perPage);

        // Tổng số KS được tìm thấy
        $totals = $result['totals'];
        $amenities = $result['amenities'];

        // Tổng số trang
        $totalPages = ceil($totals / $perPage);
        $meta = new Meta($page, $totalPages, $totals);
        if ($result) {
            $response = new Response(200, 'Call api thành công', $amenities, $meta);
            return $response->sendResponse();
        } else {
            $response = new Response(400, 'Call api thất bại', null, null);
            return $response->sendResponse();
        }
    }

    // Xóa rooms
    public function deleteAmenity($id)
    {
        // Thêm các header CORS
        header('Access-Control-Allow-Origin: *'); // Hoặc thay * b��ng domain cụ thể của bạn để bảo mật hơn
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Content-Type: application/json');

        if ($this->hotel->deleteAmenity($id)) {
            $response = new Response(200, 'Call api thành công', null, null);
            return $response->sendResponse();
        } else {
            $response = new Response(400, 'Không tìm thấy phòng với ID: ' . $id);
            return $response->sendResponse();
        }
    }

    // Thêm phòng
    public function addAmenity()
    {
        // Thêm các header CORS
        header('Access-Control-Allow-Origin: *'); // Hoặc thay * b��ng domain cụ thể của bạn để bảo mật hơn
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Content-Type: application/json');

        // Lấy dữ liệu JSON từ yêu cầu
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data)) {
            if ($this->hotel->addAmenity($data)) {
                $response = new Response(200, 'Call api thành công', null, null);
                return $response->sendResponse();
            } else {
                $response = new Response(400, 'Call api thất bại');
                return $response->sendResponse();
            }
        }
    }

    // Get room by id
    public function getAmenityById($id)
    {
        $room = $this->hotel->getAmenityById($id);
        if ($room) {
            $response = new Response(200, 'Call api thành công', $room, null);
            return $response->sendResponse();
        } else {
            $response = new Response(400, 'Không tìm thấy khách sạn với ID: ' . $id);
            return $response->sendResponse();
        }
    }

    // Update room
    public function updateAmenity($id)
    {
        // Thêm các header CORS
        header('Access-Control-Allow-Origin: *'); // Hoặc thay * b��ng domain cụ thể của bạn để bảo mật hơn
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Content-Type: application/json');

        // Lấy dữ liệu JSON từ yêu cầu
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data)) {
            $ameId = $id;
            if ($this->hotel->updateAmenity($ameId, $data)) {
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


    /** NHÓM */
    // Lấy tất cả nhóm
    public function getAllGroups()
    {
        $groups = $this->hotel->getAllGroups();
        if ($groups) {
            $response = new Response(200, 'Call api thành công', $groups, null);
            return $response->sendResponse();
        } else {
            $response = new Response(400, 'Call api thất bại');
            return $response->sendResponse();
        }
    }

    /**END OF NHÓM */


    // UPLOAD IMAGE
    public function uploadImage()
    {
        $Upload = new Upload;
        // Thêm các header CORS
        header('Access-Control-Allow-Origin: *'); // Thay * bằng domain cụ thể của bạn để bảo mật hơn
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        header('Content-Type: application/json');

        // Handle preflight OPTIONS request
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            http_response_code(200);
            exit();
        }
        // Kiểm tra xem tệp hình ảnh đã được gửi lên chưa
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $uploadedFile = $_FILES['image'];
            $folder = $_POST['folder'];
            if ($folder) {
                $url =   $Upload->uploadSingleImage($uploadedFile, $folder);
                if ($url) {
                    echo json_encode(['message' => 'Upload thành công', 'url' => $url]);
                } else {
                    echo json_encode(['message' => 'Upload thất bại']);
                }
            } else {
                return;
            }
        } else {
            // Phản hồi lỗi khi không có tệp được tải lên hoặc có lỗi trong quá trình tải lên
            echo json_encode(['message' => 'No file uploaded or there was an upload error.']);
        }
    }

    // DELETE IMAGE
    public function deleteImage($imgPath)
    {
        $Upload = new Upload;

        // Thiết lập các header CORS
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        header('Content-Type: application/json');

        // Xử lý yêu cầu OPTIONS cho preflight
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }

        // Xử lý yêu cầu DELETE
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            // Lấy dữ liệu từ query string
            $folder = $_GET['folder'] ?? '';

            if (!$folder) {
                http_response_code(400);
                echo json_encode(['message' => 'Folder is required.']);
                exit();
            }

            if ($Upload->deleteImage($imgPath, $folder)) {
                $response = new Response(200, 'Image deleted successfully.', null, null);
                return $response->sendResponse();
            } else {
                $response = new Response(404, 'Image not found with path: ' . $imgPath);
                return $response->sendResponse();
            }
        } else {
            // Phản hồi lỗi cho các phương thức HTTP không được hỗ trợ
            http_response_code(405);
            echo json_encode(['message' => 'Method Not Allowed']);
        }
    }
}
