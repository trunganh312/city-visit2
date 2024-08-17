<?php
include('../../core/config/require_cms.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy đường dẫn tệp từ payload
    $name = $_POST['name'];

    if ($name) {
        // Đảm bảo rằng fileUrl chỉ chứa tên tệp, không chứa bất kỳ đường dẫn nào khác
        $filePath = basename($name); // Lấy tên tệp từ đường dẫn
        $uploadDir = '../../../uploads/room_images/'; // Đảm bảo đường dẫn đúng đến thư mục chứa tệp
        $fileToDelete = $uploadDir . $filePath;

        if (file_exists($fileToDelete)) {
            if (unlink($fileToDelete)) {
                $DB->execute("DELETE FROM room_image WHERE rimg_image = '$name'");
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Không thể xóa tệp.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Tệp không tồn tại.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'URL không hợp lệ.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ.']);
}
