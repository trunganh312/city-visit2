<?
require_once __DIR__ . '/../models/Common.php';
require_once __DIR__ . '/../helpers/response.php';

class CommonController
{
    private $common;
    public function __construct($DB)
    {
        $this->common = new Common($DB);
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
            $table = $data['table'];
            $feild = $data['feild'];
            if ($this->common->updateStatus($hotelId, $value, $column, $table, $feild)) {
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
