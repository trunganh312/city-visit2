<?php
include('../../Core/Config/require_cms.php');
// Đường dẫn tới thư mục uploads
$uploadDir = '../../../uploads/room_images/';
$response = array();

// Kiểm tra nếu có file được upload
if (!empty($_FILES['file'])) {
    $file = $_FILES['file'];

    // Lấy roo_id từ form data
    $roo_id = isset($_POST['roo_id']) ? intval($_POST['roo_id']) : null; // Chuyển roo_id thành số nguyên để tránh SQL Injection
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    if ($roo_id && $id) {
        // Tạo tên file duy nhất bằng cách sử dụng uniqid() và giữ nguyên phần mở rộng
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $uniqueFileName = $id . '.' . $fileExtension;
        $uploadFilePath = $uploadDir . $uniqueFileName;

        // Kiểm tra và tạo thư mục nếu chưa tồn tại
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Di chuyển file tới thư mục uploads
        if (move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
            // Thêm ảnh vào db sau khi di chuyển file thành công
            // Giả sử $DB là đối tượng kết nối cơ sở dữ liệu
            $DB->execute("INSERT INTO room_image (rimg_room_id, rimg_image) VALUES ($roo_id, '{$uniqueFileName}')");

            $response['status'] = 'success';
            $response['url'] = $uniqueFileName;
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Có lỗi xảy ra khi tải lên file.';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Hotel ID không hợp lệ.';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Không có file được upload.';
}

// Trả về kết quả dưới dạng JSON
echo json_encode($response);
