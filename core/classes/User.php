<?
/**
 * Class User
 * Created by SenEnter
 */

class User extends Model {
    
	private $security;
    private $security_char = 'SiLW$91Dasl';
    private $private_char = 'S7';
    
    public $id;
    public $email;
    public $info = [];
	public $logged =   false;
	public $loggedtime = 0;
	public $errMsg = '';
    
    
    /**
     * User::__construct()
     * Dang nhap
     * @param string $email
     * @param string $password
     * @return void
     */
    function __construct($email = '', $password = '') {
        
        parent::__construct();
        
        //Check xem lay thong tin tu cookie hay dang nhap
        $check_cookie   =  false;
        if ($email == '' && $password == '') {
            $email          =   getValue('logged_name', GET_STRING, GET_COOKIE, '', 1);
            $password       =   getValue('PHPSESSlD', GET_STRING, GET_COOKIE, '', 1);
            $check_cookie   =   true;
        }
        $email  =   replaceMQ($email);
        
        //Check trong DB
        $row    =   $this->DB->query("SELECT *
                                        FROM user
                                        WHERE use_active = 1 AND use_disable = 0 AND use_email = '" . $email . "'
                                        LIMIT 1")
                                        ->getOne();
        if (!empty($row)) {
        
            //Chuoi check xac nhan
            $security   =  $this->generateSecurityCookie($row['use_security'], $row['use_random']);

            //Nếu là đăng nhập từ form thì generate ra chuỗi security để check xem có trùng với security được generate ko
            if (!$check_cookie) $password = $this->generateSecurityCookie($this->generateSecurity($row['use_email'], $password), $row['use_random']);

            //Nếu trùng nhau thì là đăng nhập thành công
            if($password == $security){
            
                $this->logged       =   true;
               	$this->id           =	(int)$row['use_id'];
                $this->email        =   $row['use_email'];
                $this->security     =   $security;
                $this->info         =   $row;
                
                //Cập nhật lần đăng nhập cuối và tổng số lần đăng nhập
                if (!$check_cookie) {
                   $this->DB->execute("UPDATE user
                                      SET use_total_logged = use_total_logged + 1, use_last_login = " . CURRENT_TIME . "
                                      WHERE use_id = " . $this->id . "
                                      LIMIT 1");
                }
            } else {
                if (!$check_cookie) $this->errMsg  =  'Mật khẩu không đúng!';
            }
        } else {
            if (!$check_cookie) $this->errMsg  =  'Tài khoản không tồn tại hoặc chưa được kích hoạt sử dụng';
        }
        
    }
    
    
	/**
	 * User::logout()
	 * Logout
     * @param string $redirect: Nếu truyền vào thì redirect theo $redirect, ko truyền thì return true
	 * @return void
	 */
	function logout($redirect = ''){
		setcookie("logged_name", " ", null, "/", null);
		setcookie("PHPSESSlD", " ", null, "/", null);
        
        if ($redirect == '') {
            return true;
        }
        
        redirect_url($url);
	}


	/**
	 * User::saveCookie()
	 * Set cookie de tu dong dang nhap trinh duyet
	 * @param integer $time
	 * @return void
	 */
	function saveCookie($time = 0){
		//Set cookie để tự động đăng nhập
		setcookie("logged_name", $this->email, ($time > 0 ? CURRENT_TIME + $time : null), "/", null);
		setcookie("PHPSESSlD", $this->security, ($time > 0 ? CURRENT_TIME + $time : null), "/", null);
	}
    

    /**
     * Generate security code
     * Tao ra ma security de kiem tra viec dang nhap
     * @param $user_password chua md5
     * string md5
     */
    function generateSecurity($email, $user_password) {
        return md5($email . "|" . $this->security_char . "|" . $user_password);
    }

    /**
     * User::generatePassword()
     * Tao ra password da duoc ma hoa de insert vao truong use_password (Ko luu password truc tiep)
     * @param mixed $password chua ma hoa
     * @param mixed $use_random
     * @return string md5
     */
    function generatePassword($password, $use_random) {
        return md5($password . '|' . $this->private_char . $use_random);
    }
    
    
    /**
     * User::generateSecurityCookie()
     * Tao ra ma de luu Cookie
     * @param mixed $user_security
     * @param mixed $time join
     * @return string md5
     */
    function generateSecurityCookie($user_security, $user_order) {
        return md5($user_security . "|" . $this->security_char . "|" . $user_order);
    }
    
    
    /**
     * User::generateUserRandom()
     * Generate ra use_random cho User
     * @return Integer
     */
    function generateUserRandom() {
        return rand(0, 99999999);
    }
    
    
    /**
     * User::register()
     * Dang ky tai khoan user
     * @param mixed $email
     * @param string $password
     * @return boolean
     */
    function register($data) {
        
        $email  =   isset($data['email']) ? trim($data['email']) : '';
        $name   =   isset($data['name']) ? trim($data['name']) : '';
        $phone  =   isset($data['phone']) ? convert_phone_number($data['phone'], '84') : '';
        $city   =   isset($data['city']) ? (int)$data['city'] : 0;
        $district   =   isset($data['district']) ? (int)$data['district'] : 0;
        $ward   =   isset($data['ward']) ? (int)$data['ward'] : 0;
        $address    =   isset($data['address']) ? trim($data['address']) : '';
        
        $password   =   isset($data['password']) ? $data['password'] : '';
        $send_email =   isset($data['send_email']) ? $data['send_email'] : true;
        
        $use_email      =   strtolower(replaceMQ($email));
        
        /** --- Kiểm tra xem email này đã có tk chưa --- **/
        $row    =   $this->getUserInfoByEmail($use_email);
        
        if (empty($row)) {
            
            //Validate email
            if (!validate_email($use_email)) {
                $this->errMsg   =  'Email không hợp lệ';
                return false;
            }
            
            $use_source =   REGISTER_SELF;  //KH tự đăng ký hay tk được tạo tự động
            
            //Nếu là tự động tạo TK
            if ($password == '') {
                $password   =   $this->generatePasswordRandom();
                $use_source =   REGISTER_AUTO;  //Đánh dấu là tạo tự động
            }
            
            //Các biến để insert vào dữ liệu
            $use_random     =   $this->generateUserRandom();
            
            $use_security   =   $this->generateSecurity($use_email, $password);
            $use_password   =   $this->generatePassword($password, $use_random);
            //echo    $password . '<br>' . $use_random . '<br>' . $use_password;exit;
            
            $Query  =   new GenerateQuery('user', true);
            $Query->add('use_email', DATA_STRING, $use_email, 'Bạn vui lòng nhập Email')
                    ->add('use_name', DATA_STRING, $name)
                    ->add('use_phone', DATA_STRING, $phone)
                    ->add('use_city', DATA_INTEGER, $city)
                    ->add('use_district', DATA_INTEGER, $district)
                    ->add('use_ward', DATA_INTEGER, $ward)
                    ->add('use_address', DATA_STRING, $address)
                    ->add('use_password', DATA_STRING, $use_password)
                    ->add('use_security', DATA_STRING, $use_security)
                    ->add('use_time_create', DATA_INTEGER, CURRENT_TIME)
                    ->add('use_active', DATA_INTEGER, 1)
                    ->add('use_source', DATA_INTEGER, $use_source)
                    ->add('use_random', DATA_INTEGER, $use_random);
            
            //Validate
       	    if($Query->validate()){
       	        
                $use_id =   $this->DB->executeReturn($Query->generateQueryInsert());
                
                //Nếu tạo TK thành công
                if ($use_id > 0) {
                    
                    //Gán Email và security để dùng lưu cookie
                    $this->id       =   $use_id;
                    $this->email    =   $email;
                    $this->security =   $this->generateSecurityCookie($use_security, $use_random);
                    
                    if ($send_email) {
                    
                        $Mailer =   new Mailer();
                        //Gửi email thông báo cho KH
                        if ($use_source == REGISTER_AUTO) {
                            $Mailer->sendEmailRegisterAuto($use_id, $password);
                        } else {
                            $Mailer->sendEmailRegister($use_id);
                        }
                    }
                    
                    return true;
                    
                } else {
                    $this->errMsg   =  'Đã có lỗi xảy ra khi tạo mới tài khoản, vui lòng thử lại';
                    save_log('error_register.cfn', $this->errMsg . '. Data: ' . json_encode($data));
                }
            } else {
                foreach ($Query->getError() as $e) {
                    $this->errMsg   .=  $e . '. ';
                }
                save_log('error_register.cfn', $this->errMsg . '. Data: ' . json_encode($data));
            }
        } else {
            $this->errMsg   =  'Tài khoản Email này đã tồn tại, vui lòng đăng nhập';
        }
        
        return false;
    }
    
    
    /**
     * User::changePassword()
     * Doi mat khau
     * @param mixed $old_password
     * @param mixed $new_password
     * @param bool $savecookie
     * @return int Error code (0: Chua dang nhap, 1: Doi MK thanh cong, 2: MK hien tai ko dung, 3: MK moi qua ngan, )
     */
    function changePassword($old_password, $new_password, $savecookie = true) {
      
        //Neu chua dang nhap
        if(!$this->logged) return 0; //Chua dang nhap
        
        //Mã hóa password cũ để kiểm tra xem có đúng ko
        $use_password   =   $this->generatePassword($old_password, $this->info['use_random']);
        //echo    $old_password . '<br>' . $this->info['use_random'] . '<br>' . $use_password . '<br>' . $this->info['use_password'];exit;
        if ($use_password != $this->info['use_password']) return 2;  //Mat khau hien tai ko dung
        
        if (strlen($new_password) < 6) return 3;  //Mat khau moi qua ngan
        
        //Ma hoa password moi
        $real_password  =   $new_password;
        $use_security   =   $this->generateSecurity($this->email, $real_password);
        $new_password   =   $this->generatePassword($real_password, $this->info['use_random']);
        
        $update =   $this->DB->execute("UPDATE user
                                        SET use_password = '" . $new_password . "', use_security = '" . $use_security . "', 
                                            use_last_time_change_pass = " . CURRENT_TIME . " 
                                        WHERE use_id = " . $this->id . "
                                        LIMIT 1");
        if ($update > 0) {
            //Nếu có lưu cookie trình duyệt
            if ($savecookie) {
                $this->security   =  $this->generateSecurityCookie($use_security,  $this->info['use_random']);
                $this->saveCookie(TIME_REMEMBER);
            }
            
            return 1;
        }
        
        //Mac dinh return 0
        return 0;
    }
    
    /**
     * User::genManual()
     * Generate ra giá trị của trường use_password và use_security để update vào DB thủ công
     * @param mixed $user_id
     * @param mixed $password
     * @return void
     */
    function genManual($user_id, $password) {
        $row    =   $this->DB->query("SELECT use_random, use_email FROM user WHERE use_id = " . (int)$user_id)->getOne();
        if (!empty($row)) {
            echo    'Security: ' . $this->generateSecurity($row['use_email'], $password) . '<br>';
            echo    'Password: ' . $this->generatePassword($password, $row['use_random']);
        } else {
            echo    'Empty!';
        }
        
    }
   
    /**
     * User::confirmResetPassword()
     * Gui email xac nhan viec reset mat khau
     * @param mixed $email
     * @return
     */
    function confirmResetPassword($email) {
        $email  =   removeInjection($email);
        //Check account
        $row    =   $this->DB->query("SELECT use_id, use_name, use_security, use_random, use_active
                                        FROM user
                                        WHERE use_email = '" . $email . "'")
                                        ->getOne();
        if (!empty($row)) {
            if ($row['use_active'] != 1) {
                $this->errMsg   =   'Tài khoản đang không được kích hoạt sử dụng, vui lòng liên hệ Hotline</p>';
                return false;
            }
         
            //Gửi email xác nhận việc reset mật khẩu
            $link_reset =  $this->genLinkResetPassword($row);
            $Mailer =   new Mailer();
            
            return $Mailer->sendMailConfirmResetPassword($email, $row['use_name'], $link_reset);
         
        } else {
            $this->errMsg  =    '<p>&Tài khoản Email không tồn tại</p>';
            return false;
        }
    }
    
    
    /**
     * User::genLinkResetPassword()
     * Link xác thực việc reset password
     * @param mixed $row_user
     * @return
     */
    function genLinkResetPassword($row_user) {
        $token   =  $this->genTokenReset($row_user);
              
        $link =  DOMAIN_WEB . '/resetpw.php?uid=' . $row_user['use_id'] . '&token=' . $token;
        
        return $link;
    }
    
    /**
     * User::genLinkVerifyAccount()
     * Verify khi đăng ký tài khoản
     * @param mixed $row_user
     * @return
     */
    function genLinkVerifyAccount($row_user) {
        $token   =  $this->genTokenReset($row_user);
              
        $link =  DOMAIN_WEB . '/verify.php?uid=' . $row_user['use_id'] . '&token=' . $token;
        
        return $link;
    }
    
    
    /**
     * User::genTokenReset()
     * Generate ra token de kem theo link reset password
     * @param mixed $row_user
     * @return
     */
    function genTokenReset($row_user) {
        $token   =  md5($row_user['use_random'] . $this->security_char . '|' . date('dmY') . '|' . $row_user['use_security']);
        
        return $token;
    }
    
    /**
     * User::verifyToken()
     * Xác thực nhưng link có kèm theo token
     * @param integer $user_id
     * @return
     */
    function verifyToken($user_id = 0) {
        
        if ($user_id == 0) $user_id  =  getValue('uid');
        
        //Lấy thông tin User
        $row    =   $this->getUserInfoByID($user_id);
        
        if (!empty($row)) {
            
            $token  =   getValue('token', GET_STRING, GET_GET, '');
            
            //Kiểm tra tính hợp lệ của token
            if ($token == $this->genTokenReset($row)) {
                                                
                //Return để ở bên ngoài giao diện cho tạo luôn password
                return true;
                
            } else {
                $this->addError('Liên kết không hợp lệ hoặc đã quá thời gian cho phép!');
                return false;
            }
        }
        
        $this->addError('Tài khoản không tồn tại!');
        return false;
    }
   
    /**
     * User::setVerified()
     * 
     * @param mixed $user_id
     * @return void
     */
    function setVerified($user_id) {
        $this->DB->execute("UPDATE user SET use_verify = 1 WHERE use_id = " . (int)$user_id . " LIMIT 1");
    }
   
   
   /**
     * User::createNewPassword()
     * Tạo mới password khi chưa đăng nhập (Khi reset password)
     * @param mixed $user_id
     * @param mixed $new_password
     * @param bool $savecookie
     * @return bool
     */
    function createNewPassword($user_id, $new_password, $savecookie = true) {
        
        if (strlen($new_password) < 6) {
            $this->addError('Mật khẩu mới phải có tối thiểu 6 ký tự');
            return false;
        }
        
        //Lấy thông tin user
        $info   =   $this->DB->query("SELECT use_id, use_email, use_random FROM user WHERE use_id = " . (int)$user_id)->getOne();
        if (!empty($info)) {
            
            //Mã hóa password mới
            $real_password  =   $new_password;
            $use_security   =   $this->generateSecurity($info['use_email'], $real_password);
            $new_password   =   $this->generatePassword($real_password, $info['use_random']);
            
            $update =   $this->DB->execute("UPDATE user
                                            SET use_password = '" . $new_password . "', use_security = '" . $use_security . "', 
                                                use_last_time_change_pass = " . CURRENT_TIME . " 
                                            WHERE use_id = " . $info['use_id'] . "
                                            LIMIT 1");
            if ($update > 0) {
                //Nếu có lưu cookie trình duyệt
                if ($savecookie) {
                    $this->email    =   $info['use_email'];
                    $this->security =   $this->generateSecurityCookie($use_security,  $info['use_random']);
                    $this->saveCookie(TIME_REMEMBER);
                }
                
                return true;
            }
        
        }
        
        return false;
    }
   
    /**
     * User::fakeLogin()
     * 
     * @param mixed $user_id
     * @return void
     */
    function fakeLogin($user_id) {
        //Check trong DB
        $row    =   $this->DB->query("SELECT *
                                        FROM user
                                        WHERE use_active = 1 AND use_id = " . (int)$user_id . "
                                        LIMIT 1")
                                        ->getOne();
        if (!empty($row)) {
            
            //Verify token
            $token  =   getValue('token', GET_STRING, GET_GET, '');
            if ($token == $this->genTokenReset($row)) {
                
                $this->logged       =   true;
               	$this->id           =	(int)$row['use_id'];
                $this->email        =   $row['use_email'];
                $this->security     =   $this->generateSecurityCookie($row['use_security'], $row['use_random']);;
                $this->info         =   $row;
                
                $this->saveCookie();
                
                redirect_url(DOMAIN_WEB);
            
            } else {
                exit('Verify Error!');
            }
            
        } else {
            exit('Not found!');
        }
    }
   
    /**
     * User::getUserInfoByID()
     * Lay thong tin cua User
     * @param integer $user_id
     * @return row
     */
    function getUserInfoByID($user_id, $field = '*') {
        if ($user_id == 0) $user_id   =  $this->id;
        
        $row    =   $this->DB->query("SELECT" . $field . "
                                        FROM user
                                        WHERE use_id = " . $user_id)
                                        ->getOne();
        
        return $row;
    }
    
    
    /**
     * User::getUserInfoByEmail()
     * Lay thong tin cua User
     * @param string $email
     * @return row
     */
    function getUserInfoByEmail($email, $field = '*') {
        $email  =   removeInjection($email);
        
        $row    =   $this->DB->query("SELECT " . $field . "
                                        FROM user
                                        WHERE use_email = '" . $email . "'")
                                        ->getOne();
        
        return $row;
    }
    
    /**
     * User::generatePasswordRandom()
     * Tao ra Password ngau nhien
     * @return string $password
     */
    function generatePasswordRandom() {
        $password   =   '';
        for ($i = 1; $i <= 6; $i++) {
            $password   .= chr(rand(65, 90));
        }
        
        return $password;
    }
    
}
?>