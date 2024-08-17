<?

/**
 * Class Admin
 * Created by SenEnter
 */

class Admin extends Model
{

    public      $info; //Chứa tất cả thông tin của admin đăng nhập
    public      $id = 0;
    public      $name;
    public      $boss = false;
    public      $cto    =   false;
    public      $logged = false;
    private     $stt_email  =   0;  //Check xem email da ton tai hay chua, neu ton tai roi thi them 01, 02, 03
    private     $admin_permission   =   []; //List tất cả các quyền của Admin đăng nhập
    private     $list_staff =   []; //List các nhân viên của Admin đăng nhập để ko bị query nhiều lần
    public      $msg    =   '';

    /**
     * Admin::Admin()
     *
     * @param string $email
     * @param string $password
     * @param string $path_error
     * @return
     */
    function __construct($email = "", $password = "", $path_error =  '/login.php')
    {
        parent::__construct();

        if (isset($_SESSION["login_error"])) unset($_SESSION["login_error"]);

        //Truong hop da dang nhap roi thì lấy thông tin từ session 
        if ($email == '' && $password == '') {

            $admin_id   =  getValue("cms_logged_id", GET_INT, GET_SESSION, 0);

            $row    =   $this->DB->query("SELECT admin.*
                                         FROM admin
                                         WHERE adm_active = 1 AND adm_id = " . $admin_id)
                ->getOne();
            if (!empty($row)) {

                if ($this->checkPasswordSession($row['adm_password'])) {

                    /** --- Ko cho để mật khẩu mặc định --- **/
                    if ($this->generatePassword(PWD_DEFAULT, $row['adm_random']) == $row['adm_password']) {

                        $exp    =   explode('?', $_SERVER['REQUEST_URI']);
                        $exp    =   explode('/', $exp[0]);
                        $file   =   end($exp);

                        if ($file != 'profile.php' && $file != 'logout.php') {
                            $this->redirectError('/module/admin/profile.php');
                        }
                    }

                    /** --- Nếu thông tin đăng nhập chính xác thì generate ra thông tin của Admin để sử dụng --- **/
                    $this->generateInfoLogged($row);

                    //Lưu thời gian last_online
                    $this->DB->execute("UPDATE admin SET adm_last_online = " . CURRENT_TIME . " WHERE adm_id = " . $row['adm_id'] . " LIMIT 1");
                } else {

                    $this->logged   =   false;

                    if (isset($_SESSION["cms_logged_id"])) unset($_SESSION["cms_logged_id"]);

                    $this->redirectError($path_error);
                }
            } else {

                $this->redirectError($path_error);
            }
        } else {

            //Trường hợp đăng nhập từ form
            $email  =    removeInjection($email);

            //Kiểm tra đăng nhập
            $row    =   $this->DB->query("SELECT *
                                            FROM admin
                                            WHERE adm_active = 1 AND adm_email = '" . $email . "'")
                ->getOne();

            if (!empty($row)) {

                $pwd_encoded    =   $this->generatePassword($password, $row['adm_random']);

                if ($pwd_encoded == $row['adm_password']) {

                    //Nếu chính xác thì generate ra thông tin của Admin để sử dụng
                    $this->generateInfoLogged($row);
                    $_SESSION['cms_logged_id']  =  intval($row['adm_id']);
                    $_SESSION['keymk']          =  $this->encodePassword($row['adm_password']);

                    $update =   $this->DB->execute("UPDATE admin
                                                    SET adm_last_login = " . CURRENT_TIME . ", adm_ip_login = '" . removeInjection($_SERVER['REMOTE_ADDR']) . "'
                                                    WHERE adm_id = " . $row['adm_id'] . "
                                                    LIMIT 1");
                } else {
                    $_SESSION["login_error"] = "Email hoặc mật khẩu không hợp lệ. Vui lòng kiểm tra lại!";
                }
            } else {
                $_SESSION["login_error"] = "Tài khoản không tồn tại!";
            }
        }

        //Return thông tin của Admin
        return $this->info;
    }


    /**
     * Admin::redirectError()
     * Redirect den trang error
     * @param mixed $path_error
     * @return void
     */
    private function redirectError($path_error)
    {
        if ($path_error != '') {
            redirect_url($path_error);
        }
    }


    /**
     * Admin::generateInfoLogged()
     * Sau khi login xong thi gan cac thong tin dang nhap vao Class de su dung
     * @param mixed $data
     * @return void
     */
    function generateInfoLogged($data)
    {
        $this->info     =  $data;
        $this->name     =  $data['adm_name'];
        $this->boss     = ($data['adm_boss'] == 1 ? true : false);
        $this->cto      = ($data['adm_cto'] == 1 ? true : false);
        $this->id       =  (int)$data['adm_id'];
        $this->logged   =  true;
    }


    /**
     * Admin::generateAdminRandom()
     * Generate ra use_random cho Admin account
     * @return Integer[0, 99999999]
     */
    function generateAdminRandom()
    {
        do {
            $value = rand(0, 99999999);
            $result = $this->DB->query("SELECT adm_id FROM admin WHERE adm_random = {$value}")->getOne();
        } while (!empty($result));

        return $value;
    }

    /**
     * Admin::generatePassword()
     * Generate ra password cua Admin
     * @param mixed $password: Real password
     * @param mixed $adm_random: Random number of admin
     * @return string md5
     */
    function generatePassword($password, $adm_random)
    {
        return md5($password . '|' . SECRET_TOKEN . $adm_random);
    }


    /**
     * Admin::encodePassword()
     * Encode password de luu vao session dang nhap
     * @param mixed $adm_password
     * @return string md5
     */
    function encodePassword($adm_password)
    {
        return md5(SECRET_TOKEN . "|" . $adm_password);
    }


    /**
     * Admin::checkPasswordSession()
     * Kiem tra chuoi session cua ID dang login co chinh xac ko
     * @param mixed $adm_password
     * @return boolean
     */
    function checkPasswordSession($adm_password)
    {
        $keymk  =   getValue('keymk', 'str', 'SESSION', '', 1);
        if ($keymk === $this->encodePassword($adm_password)) {
            return true;
        }

        return false;
    }


    /**
     * Admin::logout()
     *
     * @param string $redirect
     * @return void
     */
    function logout($redirect = "/")
    {
        if (isset($_SESSION["logged"]))   unset($_SESSION["logged"]);
        if (isset($_SESSION["cms_logged_id"]))   unset($_SESSION["cms_logged_id"]);
        session_destroy();
        session_unset();
        redirect_url($redirect);
    }

    /**
     * Admin::isSuperAdmin()
     * 
     * @return bool
     */
    function isSuperAdmin()
    {

        if ($this->boss || $this->cto) {
            return true;
        }

        return false;
    }

    /**
     * Admin::fakeLogin()
     *
     * @param mixed $admin_id
     * @return void
     */
    function fakeLogin($admin_id = 0)
    {


        //Chi co super admin moi co quyen fake login
        if ($this->cto) {

            if ($admin_id == 0) $admin_id  =  getValue('id');

            if ($this->verifyToken($admin_id)) {

                $row    =   $this->DB->query("SELECT * FROM admin WHERE adm_id = $admin_id")->getOne();


                $this->generateInfoLogged($row);

                $_SESSION['cms_logged_id']  =  intval($row['adm_id']);
                $_SESSION['keymk']          =  $this->encodePassword($row['adm_password']);
                redirect_url('/index.php');
            }
        }

        redirect_url('/');
    }


    /**
     * Admin::genTokenReset()
     * Generate ra token de verify khi gửi link
     * @param mixed $row
     * @return
     */
    function genToken($row)
    {
        $token   =  md5($row['adm_random'] . SECRET_TOKEN . '|' . date('dmY'));

        return $token;
    }

    /**
     * Admin::verifyToken()
     * Xác thực token cho những link có kèm theo token
     * @param integer $admin_id
     * @return
     */
    function verifyToken($admin_id)
    {
        //Lấy thông tin Admin
        $row    =   $this->DB->query("SELECT * FROM admin WHERE adm_id = $admin_id")->getOne();

        if (!empty($row)) {

            $token  =   getValue('token', 'str', 'GET', '');

            //Kiểm tra tính hợp lệ của token
            if ($token == $this->genToken($row)) {

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
     * Admin::isMe()
     * Check xem 1 ID co phai la ID cua tai khoan dang dang nhap hay ko
     * @param integer $admin_id
     * @return boolean
     */
    function isMe($admin_id)
    {

        if ($this->id == intval($admin_id)) {
            return true;
        }

        return false;
    }


    /**
     * Admin::generatePassManual()
     * 
     * @param mixed $random
     * @param string $password
     * @return void
     */
    function generatePassManual($random, $password = 'Cms1122')
    {
        echo    'adm_password: ' . $this->generatePassword($password, $random);
    }

    /**
     * Admin::getInfoById()
     * Lấy thông tin Admin theo ID
     * @param mixed $id
     * @param string $field
     * @return
     */
    function getInfoById($id, $field = '*')
    {
        return $this->DB->query("SELECT $field FROM admin WHERE adm_id = " . (int)$id)->getOne();
    }


    /**
     * Admin::getListIDGroupOfAdmin()
     * Lấy ra list ID các group của Admin 
     * @param mixed $admin_id
     * @param bool $return_array
     * @return string OR array
     */
    function getListIDGroupOfAdmin($admin_id, $return_array = false)
    {
        $array_id   =   [];

        $data   =   $this->DB->query("SELECT aga_group_id FROM admin_group_admin WHERE aga_admin_id = " . $admin_id)->toArray();
        foreach ($data as $row) {
            $array_id[] =   $row['aga_group_id'];
        }

        //Nếu return dạng chuỗi: 1,2,3,4
        if (!$return_array) {
            $list_id    =   !empty($array_id) ? implode(',', $array_id) : '0';
            return $list_id;
        }

        //Nếu return theo Array;
        return $array_id;
    }

    /**
     * Admin::getAllChildGroupAdmin()
     * Lấy ra tất cả các group cấp cha => con của 1 admin
     * @param mixed $admin_id
     * @param bool $return_array
     * @return
     */
    function getAllChildGroupAdmin($admin_id, $return_array = false)
    {

        //Lấy ra các group trực tiếp của Admin
        $group      =   $this->getListIDGroupOfAdmin($admin_id, true);
        $array_id   =   [];

        $finish =   false;
        while (!$finish) {

            //Lấy hết tất cả các group con của từng group
            $child  =   [];

            foreach ($group as $g_id) {

                $data   =   $this->DB->query("SELECT adgr_id FROM admin_group WHERE adgr_parent = " . $g_id)->toArray();

                foreach ($data as $r) {
                    $id =   $r['adgr_id'];
                    //Nếu chưa có trong mảng array_id thì mới gán vào
                    if (!in_array($id, $array_id)) {
                        $array_id[]  =   $id;
                        //Nếu chưa có trong mảng $child thì gán vào để foreach tiếp
                        if (!in_array($id, $child)) $child[]   =   $id;
                    }
                }
            }

            //Nếu ko còn group con nào thì cho finish để dừng ko query nữa
            if (empty($child)) {
                $finish =   true;
            } else {
                $group  =   $child; //Nếu vẫn còn thì gán lại $group thành các group con để foreach từ đầu
            }
        }

        //Nếu return dạng chuỗi: 1,2,3,4
        if (!$return_array) {
            $list_id    =   !empty($array_id) ? implode(',', $array_id) : '0';
            return $list_id;
        }

        //Nếu return theo Array;
        return $array_id;
    }

    /**
     * Admin::getAllStaffAdmin()
     * Lấy ra tất cả các nhân viên của 1 admin
     * @param mixed $admin_id: Nếu = 0 thì lấy theo ID của Admin đăng nhập
     * @param bool $return_array
     * @return [] OR String
     */
    function getAllStaffAdmin($admin_id = 0, $return_array = true)
    {

        //Nếu là lần đầu gọi đến hàm này thì mới phải query để lấy ra
        if (empty($this->list_staff)) {

            if ($admin_id == 0) $admin_id   =   $this->id;

            $array_id   =   [$admin_id];

            //Lấy ra tất cả các group cấp từ cha => con của Admin
            $group  =   $this->getAllChildGroupAdmin($admin_id, true);

            if (!empty($group)) {
                $data   =   $this->DB->query("SELECT DISTINCT(aga_admin_id) FROM admin_group_admin WHERE aga_group_id IN(" . implode(',', $group) . ")")->toArray();

                foreach ($data as $row) {
                    $array_id[] =   $row['aga_admin_id'];
                }
            }

            //Gán vào mảng chứa list_staff của class để sử dụng lại, tránh query nhiều lần
            $this->list_staff   =   $array_id;
        } else {

            //Nếu đã từng gọi đến hàm này rồi thì sử dụng luôn list_staff
            $array_id   =   $this->list_staff;
        }

        //Nếu return dạng chuỗi: 1,2,3,4
        if (!$return_array) {
            $list_id    =   !empty($array_id) ? implode(',', $array_id) : '0';
            return $list_id;
        }

        //Nếu return theo Array;
        return $array_id;
    }

    /**
     * Admin::getStaffOfGroup()
     * Lấy list staff của 1 team
     * @param mixed $group
     * @return
     */
    function getStaffOfGroup($group, $active = true)
    {

        $staffs =   [];

        $data   =   $this->DB->query("SELECT adm_id, adm_name
                                        FROM admin
                                        INNER JOIN admin_group_admin ON adm_id = aga_admin_id
                                        WHERE aga_group_id = " . (int)$group . ($active ? " AND adm_active = 1" : "") . "
                                        ORDER BY adm_name")
            ->toArray();
        foreach ($data as $row) {
            $staffs[$row['adm_id']] =   $row['adm_name'];
        }

        return $staffs;
    }


    /**
     * Admin::checkPermission()
     * Check quyền của Admin
     * @param mixed $permission: Alias của quyền
     * @param bool $exit: Show ra câu thông báo và Exit hoặc trả về Boolean True/False
     * @return
     */
    function checkPermission($permission, $owner_id = 0, $exit = true)
    {

        /** Mặc định sẽ ko có tính năng phân quyền nên return true luôn, nếu làm thêm thì comment lại **/
        return true;

        //Super Admin
        if ($this->isSuperAdmin()) return true;

        $permission =   removeInjection($permission);

        //Đầu tiên là check xem có quyền theo Alias hay ko
        $has_permission =   $this->hasPermission($permission);

        //Nếu ko có quyền thì return hoặc exit luôn
        if (!$has_permission) {
            if ($exit) {
                $this->showPermissionDeclined('Bạn không có quyền sử dụng tính năng này');
            } else {
                $this->msg  =   'Bạn không có quyền sử dụng tính năng này';
                return false;
            }
        }

        //Nếu có quyền thì check tiếp xem quyền này có cho phép chỉ chính Admin sở hữu bản ghi mới được quyền sửa hay ko
        $per_info   =   $this->DB->query("SELECT per_id, per_owner, per_allow_leader
                                            FROM permission
                                            WHERE per_alias = '$permission'")
            ->getOne();

        if (isset($per_info['per_owner']) && $per_info['per_owner'] == 1) {

            //Nếu ko phải là owner của dữ liệu thì check tiếp các logic quyền theo Level
            if (!$this->isMe($owner_id)) {

                //Check tiếp xem Admin đang đăng nhập có thuộc nhóm nào mà Nhóm đó ko bị check quyền theo level hay ko, chỉ cần thuộc 1 trong các nhóm đó thì là có quyền luôn
                $check_level    =   $this->DB->query("SELECT adgr_check_level
                                                        FROM admin_group
                                                        INNER JOIN admin_group_admin ON (adgr_id = aga_group_id)
                                                        WHERE adgr_check_level = 0 AND aga_admin_id = " . $this->id . "
                                                        LIMIT 1")
                    ->getOne();

                //Nếu ko thuộc nhóm nào mà Ko bị check quyền theo level thì lại check tiếp xem Admin đang đăng nhập có phải là leader của Owner hay ko
                if (empty($check_level)) {

                    //Lại check tiếp, nếu quyền này mà ko cho phép leader xử lý thì ko có quyền
                    if ($per_info['per_allow_leader'] != 1) {
                        if ($exit) {
                            $this->showPermissionDeclined('Bạn không có quyền xử lý dữ liệu này - Mã: ' . __LINE__);
                        } else {
                            $this->msg  =   'Bạn không có quyền xử lý dữ liệu này - Mã: ' . __LINE__;
                            return false;
                        }
                    }

                    //Nếu cho phép leader thì lấy ra DS các staff của Admin để check xem có phải là leader hay ko
                    $list_staff =   $this->getAllStaffAdmin($this->id);

                    //Nếu ko phải là leader của owner thì ko có quyền
                    if (!in_array($owner_id, $list_staff)) {
                        if ($exit) {
                            $this->showPermissionDeclined('Bạn không có quyền xử lý dữ liệu này - Mã: ' . __LINE__);
                        } else {
                            $this->msg  =   'Bạn không có quyền xử lý dữ liệu này - Mã: ' . __LINE__;
                            return false;
                        }
                    }
                }
            }
        }

        return true;
    }

    /**
     * Admin::hasPermission()
     * 
     * @param mixed $permission
     * @return
     */
    function hasPermission($permission)
    {

        /** Mặc định sẽ ko có tính năng phân quyền nên return true luôn, nếu làm thêm thì comment lại **/
        return true;

        //Super Admin
        if ($this->isSuperAdmin()) return true;

        //Check xem quyền cần check có nằm trong array chứa tất cả các quyền của Admin đăng nhập hay ko
        $admin_permission   =   $this->getAllPermissionAdmin();
        if (in_array($permission, $admin_permission)) {
            return true;
        }

        return false;
    }

    /**
     * Admin::genSQLPermission()
     * Generate ra câu SQL check theo quyền xử lý với những dữ liệu chỉ cho phép owner xử lý
     * @param mixed $field
     * @param bool $check_level: Co check quyen theo level hay ko
     * @return string
     */
    function genSQLPermission($field, $check_level = false)
    {

        if ($this->isSuperAdmin()) {
            return "";
        }

        //Nếu ko cần check quyền theo level, mà admin này thuộc 1 nhóm nào đó mà nhóm đó ko bị check quyền theo level thì sẽ có quyền full
        if (!$check_level) {
            $check_level    =   $this->DB->query("SELECT adgr_check_level
                                                    FROM admin_group
                                                    INNER JOIN admin_group_admin ON (adgr_id = aga_group_id)
                                                    WHERE adgr_check_level = 0 AND aga_admin_id = " . $this->id . "
                                                    LIMIT 1")
                ->getOne();

            if (!empty($check_level)) return "";
        }

        //Lấy hết các staff của admin đăng nhập
        $list_staff =   $this->getAllStaffAdmin($this->id, false);

        $sql    =   " AND $field IN($list_staff)";

        return $sql;
    }


    /**
     * Admin::getAllPermissionAdmin()
     * Lấy ra tất cả các quyền của Admin
     * @return []
     */
    function getAllPermissionAdmin()
    {

        /** Nếu đã có list quyền rồi thì return luôn để tránh phải gọi lại query **/
        if (!empty($this->admin_permission)) {
            return $this->admin_permission;
        }

        //Lấy ra tất cả các quyền của Admin đăng nhập
        $data   =   $this->DB->query("SELECT per_alias
                                        FROM permission_group_admin
                                        INNER JOIN permission ON (pega_permission_id = per_id)
                                        WHERE pega_group_id IN(SELECT aga_group_id
                                                                FROM admin_group_admin
                                                                WHERE aga_admin_id = " . $this->id . ")")
            ->toArray();

        foreach ($data as $row) {
            $this->admin_permission[]   =   $row['per_alias'];
        }

        return $this->admin_permission;
    }

    /**
     * Admin::showPermissionDeclined()
     * 
     * @param mixed $msg
     * @return void
     */
    function showPermissionDeclined($msg)
    {
        echo    '<p style="text-align:center;margin-top:30px;">' . $msg . '!</p>';
        exit();
    }
}
