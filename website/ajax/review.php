<?
include '../config/require_web.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $comment = $_POST['comment'];
    $cleanliness = $_POST['cleanliness'];
    $amenities = $_POST['amenities'];
    $money = $_POST['money'];
    $service = $_POST['service'];
    $support = $_POST['support'];
    $location = $_POST['location'];
    $hotel_id = $_POST['hotel_id'];

    $sql = "INSERT INTO reviews (rev_hotel_id, rev_cleanliness, rev_amenities, rev_money, rev_service, rev_customer_support, rev_location, rev_comment, rev_name, rev_email) VALUES ('$hotel_id', '$cleanliness', '$amenities', '$money', '$service', '$support', '$location', '$comment', '$name', '$email')";

    $result = $DB->execute($sql);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Gửi đánh giá thành công!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gửi đánh giá thất bại']);
    }
}
