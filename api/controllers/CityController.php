<?
require_once __DIR__ . '/../models/City.php';
require_once __DIR__ . '/../helpers/response.php';

class CityController
{
    private $city;

    public function __construct($DB)
    {
        $this->city = new city($DB);
    }

    public function getAllCitys()
    {

        $citys = $this->city->getAll();
        if (!empty($citys)) {
            $response = new Response(200, 'Call api thành công!!', $citys, null);
            return $response->sendResponse();
        } else {
            $response = new Response(404, 'Không tìm thấy huyện nào!!', null, null);
            return $response->sendResponse();
        }
    }
}
