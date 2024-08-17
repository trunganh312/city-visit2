<?php
require_once(__DIR__ . '/../config/require_api.php');

header('Access-Control-Allow-Origin: *'); // Thay * bằng domain cụ thể của bạn để bảo mật hơn
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
header('Content-Type: application/json');


// Lấy URL từ yêu cầu
$request = explode('?', $_SERVER['REQUEST_URI'])[0];
$method = $_SERVER['REQUEST_METHOD']; // Lấy phương thức HTTP (GET, POST, PUT, DELETE)

// Phân tích URL để xử lý các tham số động
$parts = explode('/', trim($request, '/'));
$endpoint = $parts[0];
$id = isset($parts[2]) ? intval($parts[2]) : null; // Lấy ID nếu có

// Điều hướng yêu cầu đến controller và phương thức tương ứng
switch ($endpoint) {
    case 'api':
        $action = $parts[1];
        switch ($action) {
            case 'uploads':
                if ($method === 'GET') {
                    if ($id) {
                        $HotelController->getAmenityById($id);
                    } else {
                        $HotelController->getAmenitiesWithMeta();
                    }
                } elseif ($method === 'POST') {
                    $HotelController->uploadImage();
                } elseif ($method === 'PUT') {
                    if ($id) {
                        $HotelController->updateAmenity($id);
                    } else {
                        http_response_code(400);
                        echo json_encode(array("message" => "ID is required for update."));
                    }
                } elseif ($method === 'DELETE') {
                    if ($id) {
                        $HotelController->deleteImage($id);
                    } else {
                        http_response_code(400);
                        echo json_encode(array("message" => "ID is required for delete."));
                    }
                }
                break;
            case 'groups':
                if ($method === 'GET') {
                    if ($id) {
                        $HotelController->getHotelDetails($id);
                    } else {
                        $HotelController->getAllGroups();
                    }
                } elseif ($method === 'POST') {
                    $HotelController->addAmenity();
                } elseif ($method === 'PUT') {
                    if ($id) {
                        $HotelController->updateHotel($id);
                    } else {
                        http_response_code(400);
                        echo json_encode(array("message" => "ID is required for update."));
                    }
                } elseif ($method === 'DELETE') {
                    if ($id) {
                        $HotelController->deleteHotel($id);
                    } else {
                        http_response_code(400);
                        echo json_encode(array("message" => "ID is required for delete."));
                    }
                }
                break;


            case 'amenities':
                if ($method === 'GET') {
                    if ($id) {
                        $HotelController->getAmenityById($id);
                    } else {
                        $HotelController->getAmenitiesWithMeta();
                    }
                } elseif ($method === 'POST') {
                    $HotelController->addAmenity();
                } elseif ($method === 'PUT') {
                    if ($id) {
                        $HotelController->updateAmenity($id);
                    } else {
                        http_response_code(400);
                        echo json_encode(array("message" => "ID is required for update."));
                    }
                } elseif ($method === 'DELETE') {
                    if ($id) {
                        $HotelController->deleteAmenity($id);
                    } else {
                        http_response_code(400);
                        echo json_encode(array("message" => "ID is required for delete."));
                    }
                }
                break;

            case 'hotels':
                if ($method === 'GET') {
                    if ($id) {
                        $HotelController->getHotelDetails($id);
                    } else {
                        $HotelController->getHotels();
                    }
                } elseif ($method === 'POST') {
                    $HotelController->addHotel();
                } elseif ($method === 'PUT') {
                    if ($id) {
                        $HotelController->updateHotel($id);
                    } else {
                        http_response_code(400);
                        echo json_encode(array("message" => "ID is required for update."));
                    }
                } elseif ($method === 'DELETE') {
                    if ($id) {
                        $HotelController->deleteHotel($id);
                    } else {
                        http_response_code(400);
                        echo json_encode(array("message" => "ID is required for delete."));
                    }
                }
                break;
            case 'amenity':
                if ($method === 'GET') {
                    $HotelController->getAmenities();
                }
                break;
            case 'districts':
                if ($method === 'GET') {
                    if ($id) {
                        $DistrictController->getDistrictById($id);
                    } else {
                        $DistrictController->getAllDistricts();
                    }
                } elseif ($method === 'POST') {
                    $DistrictController->addDistrict();
                } elseif ($method === 'PUT') {
                    if ($id) {
                        $DistrictController->updateDistrict($id);
                    } else {
                        http_response_code(400);
                        echo json_encode(array("message" => "ID is required for update."));
                    }
                } elseif ($method === 'DELETE') {
                    if ($id) {
                        $DistrictController->deleteDistrict($id);
                    } else {
                        http_response_code(400);
                        echo json_encode(array("message" => "ID is required for delete."));
                    }
                }
                break;
            case 'districtByMeta':
                $DistrictController->getDistrictsWithMeta();
                break;
            case 'districtByCity':
                $DistrictController->getDistrictsByCityId();
                break;
            case 'city':
                $CityController->getAllCitys();
                break;
            case 'active':
                $CommonController->updateStatus();
                break;
            case 'rooms':
                if ($method === 'GET') {
                    if ($id) {
                        $HotelController->getRoomById($id);
                    } else {
                        $HotelController->getRooms();
                    }
                } elseif ($method === 'POST') {
                    $HotelController->addRoom();
                } elseif ($method === 'PUT') {
                    if ($id) {
                        $HotelController->updateRoom($id);
                    } else {
                        http_response_code(400);
                        echo json_encode(array("message" => "ID is required for update."));
                    }
                } elseif ($method === 'DELETE') {
                    if ($id) {
                        $HotelController->deleteRoom($id);
                    } else {
                        http_response_code(400);
                        echo json_encode(array("message" => "ID is required for delete."));
                    }
                }
                break;

            default:
                http_response_code(404);
                echo json_encode(array("message" => "Endpoint not found."));
                break;
        }
        break;
    default:
        http_response_code(404);
        echo json_encode(array("message" => "Endpoint not found."));
        break;
}
