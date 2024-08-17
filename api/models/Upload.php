<?

class Upload
{

    public function __construct() {}


    /**
     * Upload:: uploadImage() 
     */

    // Upload single image
    public function uploadSingleImage($file, $folder)
    {
        if (!$folder) return;
        // Đường dẫn thư mục tải lên
        $upload_dir = '../uploads/' . $folder . '/';

        // Tạo thư mục nếu chưa tồn tại
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Kiểm tra nếu có lỗi khi tải lên
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('File upload error: ' . $file['error']);
        }

        // Đặt tên tệp duy nhất
        $current_timestamp = time();
        $file_name = basename($file['name']);
        $file_path = $current_timestamp . '_' . $file_name;
        $root_path = $upload_dir . $file_path;


        // Di chuyển tệp từ tạm thời đến thư mục tải lên
        if (move_uploaded_file($file['tmp_name'], $root_path)) {
            return $file_path; // Trả về tên tệp 
        } else {
            throw new Exception('Failed to move uploaded file.');
        }
    }

    // Upload multiple images
    public function uploadMultipleImages($files, $folder)
    {
        $upload_dir = '../../../uploads/' . $folder . '/';
        $images = [];

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        foreach ($files as $file) {
            if ($file['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('File upload error: ' . $file['error']);
            }
            $current_timestamp = time();
            $file_name = basename($file['name']);
            $file_path = $current_timestamp . '_' . $file_name;
            $root_path = $upload_dir . $file_path;
            if (move_uploaded_file($file['tmp_name'], $root_path)) {
                $images[] = $file_path; // Trả về danh sách tên tệp
            } else {
                throw new Exception('Failed to move uploaded file.');
            }
        }

        return $images;
    }

    // DELETE IMAGE
    // Ví dụ endpoint DELETE trên server (PHP)
    public function deleteImage($id, $folder)
    {
        $path = '../uploads/' . $folder . '/' . $id;
        if (file_exists($path)) {
            unlink($path);
            http_response_code(200);
            echo json_encode(['message' => 'Image deleted successfully!']);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Image not found!']);
        }
    }
}
