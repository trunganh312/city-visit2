<?
include '../config/require_web.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $message = $_POST['message'];
    $hotel_id = $_POST['hotel_id'];
    $room_id = $_POST['room_id'];
    $sql = "INSERT INTO booking (boo_full_name, boo_email, boo_phone, boo_address, boo_request, boo_hotel_id, boo_room_id) VALUES ('$fullname', '$email', '$phone', '$address', '$message', '$hotel_id', '$room_id')";

    $result = $DB->execute($sql);
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Đặt phòng thành công!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Đặt phòng thất bại']);
    }
}
