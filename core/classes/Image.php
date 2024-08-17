<?
/**
 * Class Image
 * Created by SenEnter
 */

class Image {
    
    function __construct() {
        
    }
    
    /**
     * Image::moveImage()
     * Copy hoac move 1 file anh de mot thu muc moi
     * @param mixed $image_name
     * @param mixed $current_path
     * @param mixed $new_path
     * @param mixed $array_resize
     * @param bool $keep_backup
     * @return Boolean
     */
    function moveImage($image_name, $current_path, $new_path, $array_resize = [], $keep_backup = false, $quality = 85) {
        
        //Nếu chưa có thư mục thì tạo mới
        if (!is_dir($new_path)){
            mkdir($new_path, 0777, true);
        }
        
        //Đường dẫn đầy đủ của file
        $old_file   =   $current_path . $image_name;
        $new_file   =   $new_path . $image_name;
        
        //Nếu ko tồn tại file thì return false luôn
        if (!file_exists($old_file)) return false;
        
        //Lấy kích thước ảnh
        list($img_width, $img_height)   =   getimagesize($old_file);
        if (!$img_width || !$img_height) return false;
        
        //Di chuyển hoặc copy ảnh
        $result =   false;
        if ($keep_backup) {
            $result =   copy($old_file, $new_file);
        } else {
            $result =   rename($old_file, $new_file);
        }
        
        //Nếu có resize ảnh
        if ($result) {
            $this->resizeImage($new_path, $image_name, $array_resize, $quality);
        }
        
        //Return
        return $result;
    }
    
    
    /**
     * Image::resizeImage()
     * Resize anh theo array
     * @param mixed $path
     * @param mixed $filename
     * @param mixed $array_resize = ["medium" => ["maxwidth" => 180, "maxheight" => 180]]
     * @param integer $quality
     * @return Boolean
     */
    function resizeImage($path, $filename, $array_resize = [SIZE_LARGE => ["maxwidth" => 180, "maxheight" => 180]], $quality = 85) {
        //Đường dẫn đầy đủ của file
        $file   =   $path . $filename;
        $ext    =   $this->getExtension($filename);
 
        //Resize theo mảng các size
        foreach($array_resize as $prefix => $value){
            $maxwidth   =   intval($value['maxwidth']);
            $maxheight  =   intval($value['maxheight']);
            
     		//Lấy giá trị width và height của file để tính tỉ lệ
     		list($img_width, $img_height)    =   @getimagesize($file);
     		if (!$img_width || !$img_height) return false;
    
         	//Tính tỉ lệ
    		$scale    =   min($maxwidth / $img_width, $maxheight / $img_height);
    		if($scale > 1)    $scale  =   1;
    
    		//Kích thước của file mới
            $new_width  =   $img_width * $scale;
            $new_height =   $img_height * $scale;
    
      		//Tạo ảnh
            $new_img    =   @imagecreatetruecolor($new_width, $new_height);
    
      		//Kiểm tra định dạng của ảnh xem có chính xác ko (Tránh trường hợp up file exe hoặc định dạng khác)
      		switch($ext){
    			case "gif":
    				$image = imagecreatefromgif($file);
    				break;

    			case "jpg":
    			case "jpe":
    			case "jpeg":
    				$image = imagecreatefromjpeg($file);
    				break;
                    
    			case "png":
    				$image = imagecreatefrompng($file);
                    imagealphablending($new_img, false);
                    imagesavealpha($new_img,true);
                    $transparent = imagecolorallocatealpha($new_img, 255, 255, 255, 127);
                    imagefilledrectangle($new_img, 0, 0, $new_width, $new_height, $transparent);
    				break;
                case "webp":
                    $image = imagecreatefromwebp($file);
                    break;
    		}
    
    		//Copy ảnh
    		imagecopyresampled($new_img, $image, 0, 0, 0, 0, $new_width, $new_height, $img_width, $img_height);
            
            //Các file được resize được lưu vào các thư mục có tên là tiền tố của file (large, medium, small, tiny...)
            $path_save  =   $path . $prefix . '/';
            if (!is_dir($path_save)){
        		mkdir($path_save, 0777, true);
        	}
            
            //Đường dẫn file ảnh mới
            $new_file   =   $path_save . $filename;
    		//Tạo ảnh
    		switch($ext){
    		  
    			case "gif":
    				imagegif($new_img, $new_file);
    				break;
                    
    			case "jpg":
    			case "jpe":
    			case "jpeg":
    				imagejpeg($new_img, $new_file, $quality);
    				break;
                    
    			case "png":
    				imagepng($new_img, $new_file);
    				break;

                case "webp":
                    imagewebp($new_img, $new_file, $quality);
                    break;
    		}

    		//Giải phóng bộ nhớ
    		@imagedestroy($new_img);
    		@imagedestroy($image);
        }
        
        return true;
    }
    
    /**
     * Image::convertToWebP()
     * 
     * @param mixed $old_file
     * @param mixed $new_file
     * @param mixed $ext
     * @param integer $quality
     * @return
     */
    function convertToWebP($old_file, $new_file, $ext, $quality = 85) {
        // Kiểm tra nếu hàm imagewebp có sẵn trong PHP
        if(function_exists('imagewebp')) {
            
            //Nếu ko tồn tại file thì lưu log
            if (!file_exists($old_file)) {
                save_log('error_image.cfn', 'File 404: ' . $old_file);
                return false;
            }
            
            //Kiểm tra định dạng của ảnh xem có chính xác ko (Tránh trường hợp up file exe hoặc định dạng khác)
            switch($ext){
                case "gif":
                    $image = imagecreatefromgif($old_file);
                    imagepalettetotruecolor($image);
                    break;

                case "jpg":
                case "jpe":
                case "jpeg":
                    $image = imagecreatefromjpeg($old_file);
                    break;
                    
                case "png":
                    $image = imagecreatefrompng($old_file);
                    imagepalettetotruecolor($image);
                    break;
            }

            // Chuyển đổi định dạng ảnh sang WebP
            return  imagewebp($image, $new_file, $quality);
        }

        return false;
    }
    
    /**
     * Image::getExtension()
     * Lay extension cua file
     * @param mixed $filename
     * @return string extension (jpg, png...)
     */
    function getExtension($filename){
        $ext    =   substr($filename, strrpos($filename, ".") + 1);
        return	strtolower($ext);
    }
    
    /**
     * deleteFile()
     * Xoa file anh cua KS
     * @param mixed $path_file: Duong dan cua file
     * @param mixed $file_name: Ten anh
     * @return void
     */
    function deleteFile($path_file, $file_name) {
        
        //1 file ảnh có thể có 2 tên: Tên gốc và tên có thêm 'o' (trường hợp file khi up quá lớn)
        $arr_prefix =   ['', 'o'];
        
        //Các thư mục của file
        $arr_folder =   ['', SIZE_BIG, SIZE_LARGE, SIZE_MEDIUM, SIZE_SMALL];
        
        //Xóa hết các size của file ở thư mục gốc
        foreach ($arr_prefix as $value) {            
            $file   =   $path_file . $value . $file_name;
            
    		if(file_exists($file)) @unlink($file);   
    	}
        
        //Xóa hết file ở các thư mục con
        foreach ($arr_folder as $folder) {
            $file   =   $path_file . $folder . '/' . $file_name;
            
    		if(file_exists($file)) @unlink($file);   
        }
    }
    
}
?>