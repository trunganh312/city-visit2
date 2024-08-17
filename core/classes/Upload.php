<?

/**
 * Class Upload Image
 * Created by SenEnter
 */

class Upload
{

    public  $path_upload;   //Đường dẫn sẽ upload ảnh vào
    public  $max_width      =   1920;
    public  $max_height     =   1280;
    public  $max_size       =   3072;   //Maxsize cho phép upload (KB)
    public  $error          =   '';
    public  $limit_width_height =   false;  //Nếu file có kích thước quá lớn thì tự động resize xuống mức max_width, max_height ở trên, giữ file gốc và thêm original vào tên file gốc

    private $type_allow     =   ['jpg', 'jpe', 'jpeg', 'png', 'gif', 'webp'];    //Array chứa các định dạng file (đuôi) cho phép upload
    private $check_security =   true;
    private $file_size;
    private $extension;
    public  $new_name       =   ''; //Tên mới của file sau khi upload thành công


    /**
     * Upload:: __construct()
     * @param $name_control: Ten cua input file control
     * @param $path_upload: Duong dan de upload anh
     * 
     */
    function __construct($name_control, $path_upload, $min_width = 1200, $min_height = 800, $ratio = '')
    {

        //Kiểm tra xem có tồn tại control file ko
        if (!isset($_FILES[$name_control])) {
            $this->error    =   "Không tồn tại control file <b>" . $name_control . "</b>";

            return;
        }

        //Nếu ko up file
        if ($_FILES[$name_control]['size'] <= 0)  return;

        //Check lỗi upload
        if (@!is_uploaded_file($_FILES[$name_control]['tmp_name'])) {
            $this->error    =   "Không tìm thấy file tmp_name, file upload tạm (Có thể do file upload quá lớn hoặc sai tên control upload file khi submit)";
            return;
        }

        //Check file size
        $this->file_size  =  @filesize($_FILES[$name_control]['tmp_name']);
        if ($this->file_size > $this->max_size * 1024) {
            $this->error    =   "File <b>" . $_FILES[$name_control]['name'] . "</b> có dung lượng file upload <b>(" . round($this->file_size / 1024, 2) . "KB)</b> vượt quá giới hạn cho phép (" . $this->max_size . "KB).<br>";
            return;
        }

        //Lấy kích thước ảnh để check min width min hgieht
        list($img_width, $img_height)   =   @getimagesize($_FILES[$name_control]['tmp_name']);
        if (!$img_width || !$img_height) {
            $this->error    =   'Không lấy được kích thước file ' . $_FILES[$name_control]['name'];
            return;
        }
        //Nếu đồng thời cả chiều rộng/dài đều nhỏ hơn kích thước tối thiểu thì mới coi là ko hợp lệ
        if (($img_width < $min_width && $img_height < $min_height) || ($img_width < $min_height && $img_height < $min_width)) {
            $this->error    =   'File <b>' . $_FILES[$name_control]['name'] . '</b> (' . $img_width . 'x' . $img_height . ') nhỏ hơn kích thước tối thiểu';
            return;
        }

        //Nếu có check chính xác tỷ lệ bao nhiêu đấy
        if (!check_ratio_image($img_width, $img_height, $ratio)) {
            $this->error    =   'File <b>' . $_FILES[$name_control]['name'] . '</b> (' . $img_width . 'x' . $img_height . ') không đúng tỷ lệ yêu cầu (' . $ratio . ')';
            return;
        }

        //Check extension của file
        $extension   =   get_extension($_FILES[$name_control]['name']);

        if (!in_array($extension, $this->type_allow)) {
            $this->error    =   "Định dạng file upload không đúng. Bạn chỉ có thể upload các file có định dạng " . implode(", ", $this->type_allow) . '<br>';
            return;
        }

        //Tạo thư mục upload nếu chưa có
        if (!is_dir($path_upload)) {
            mkdir($path_upload, 0777, true);
        }

        //Tên của file sau khi được upload (Ko bao gồm extension)
        $new_name   =   $this->generateFileName();

        if ($extension == 'webp') {
            //Đổi tên file upload
            $this->new_name  =    $new_name . '.' . $extension;

            //Đường dẫn đầy đủ của file mới
            $file_uploaded  =   $path_upload . $this->new_name;

            //Upload file lên folder
            move_uploaded_file($_FILES[$name_control]['tmp_name'], $file_uploaded);
        } else {
            //Đổi tên file upload
            $this->new_name  =  $new_name . '.webp';

            //Đường dẫn đầy đủ của file mới
            $file_uploaded  =   $path_upload . $this->new_name;

            (new Image)->convertToWebP($_FILES[$name_control]['tmp_name'], $file_uploaded, $extension, 85);
        }


        //Kiểm tra xem đã upload được file vào đúng folder upload chưa
        if (!is_file($file_uploaded)) {
            $this->error    =   "Đường dẫn để upload file không đúng";
            $this->new_name =   ''; //Reset tên ảnh
            return;
        }

        //Kiểm tra trường hợp hack bằng cách cố tình upload 1 file ko phải dạng ảnh nhưng để extension là ảnh
        if ($this->check_security) {
            $check  =   $this->checkImage($path_upload, $this->new_name);
            if (!$check) {
                $this->error    =   "Phần mở rộng của file không tương ứng với định dạng file";
                return;
            }
        }

        $this->path_upload  =   $path_upload;
        $this->extension    =    $extension;

        if ($this->limit_width_height && ($img_width > $this->max_width || $img_height > $this->max_height)) {

            $this->resizeImage($this->new_name, $path_upload, ['rename' => ["maxwidth" => $this->max_width, "maxheight" => $this->max_height]]);

            //Nếu resize thành công thì phải đổi lại tên file
            if (is_file($path_upload . $new_name . '_rename.' . $this->extension)) {
                //Đổi tên file vừa upload và thêm chữ 'original'
                rename($path_upload . $this->new_name, $path_upload . $new_name . '_original.' . $this->extension);
                //Đổi tên file vừa resize thành tên file bình thường
                $rename  =  rename($path_upload . $new_name . '_rename.' . $this->extension, $path_upload . $this->new_name);
            }
        }
    }


    /**
     * Upload::generateFileName()
     * 
     * @return string Ten cua file anh, ko bao gom extension
     */
    function generateFileName()
    {
        $name   =   'image_';
        for ($i = 0; $i < 3; $i++) {
            $name .= chr(rand(97, 122));
        }
        $name   .=  date('ymd') . rand(1000, 9999);

        return $name;
    }

    /**
     * Upload::checkImage()
     * Kiem tra phan mo rong cua file xem co chuan ko
     * @param mixed $path
     * @param mixed $filename
     * @return Boollean
     */
    function checkImage($path, $filename)
    {

        $Image    =   new Image;

        //Lấy extension
        $ext    =   $Image->getExtension($filename);

        //Check file
        $checkImg   =   true;

        switch ($ext) {
            case "gif":
                $checkImg = @imagecreatefromgif($path . $filename);
                break;

            case "jpg":
            case "jpe":
            case "jpeg":
                $checkImg = @imagecreatefromjpeg($path . $filename);
                break;

            case "png":
                $checkImg = @imagecreatefrompng($path . $filename);
                break;
        }

        //Nếu file có phần mở rộng ko chính xác thì xóa file đi
        if (!$checkImg) {
            $Image->deleteFile($path, $filename);
            return false;
        }

        return true;
    }

    /**
     * Upload::resizeImage()
     * 
     * @param mixed $array_resize
     * @return void
     */
    function resizeImage($array_resize = [SIZE_MEDIUM => ['maxwidth' => 180, 'maxheight' => 180]])
    {

        $Image    =   new Image;

        $Image->resizeImage($this->path_upload, $this->new_name, $array_resize);
    }

    /**
     * Upload:: uploadImage() 
     */

    // Upload single image
    public function uploadSingleImage($file, $folder)
    {
        // Đường dẫn thư mục tải lên
        $upload_dir = '../../../uploads/' . $folder . '/';

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

    // Resize image
}
