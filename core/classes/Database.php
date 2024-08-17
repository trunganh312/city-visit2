<?

/**
 * Class Database
 * Created by SenEnter
 */

class Database
{

    /** Path save log **/
    private $path_log;
    /** Connector **/
    private $con;

    private $connected      =   false;
    /** Show debug query **/
    private $debug_query    =   false;

    private $db_log         =   null;

    /** instance **/
    public static $instance;

    private $max_connection =   100;

    private $count_con  =   1;

    private $result;
    public  $sql    =   '';
    private $slow_query;
    private $host;
    private $username;
    private $password;
    private $database;

    /**
     * Init
     */
    function __construct()
    {

        $this->path_log     =   get_root_path() . 'log/';
        $this->slow_query   =   ENV_DB_SLOW_QUERY;
        $this->host         =   ENV_DB_HOST;
        $this->username     =   ENV_DB_USERNAME;
        $this->password     =   ENV_DB_PASSWORD;
        $this->database     =   ENV_DB_DBNAME;
    }

    /**
     * DBConnect::connectDB()
     * 
     * @return boolean
     */
    function connectDB()
    {
        //Nếu chưa vượt quá max_connection thì ko cần kết nối lại
        if ($this->connected && $this->count_con <= $this->max_connection) {
            //echo    $this->count_con . '--<br>';
            $this->count_con++;

            return true;
        }

        $this->closeConnect();

        //Ket noi den DB
        $this->con = mysqli_connect($this->host, $this->username, $this->password);
        if (!$this->con) {
            $error = "Khong the ket noi den DB: Host: $this->host, User: $this->username" . chr(13);
            //Save log for check again
            $this->saveLog('error_connect', $error);

            return false;
        }
        if (mysqli_select_db($this->con, $this->database)) {
            @mysqli_query($this->con, "SET NAMES 'utf8'");
            $this->connected    =   true;
            return true;
        }

        //return
        return false;
    }

    /**
     * DBConnect::closeConnect()
     * 
     * @return void
     */
    public function closeConnect()
    {
        if ($this->connected && $this->count_con > $this->max_connection) {
            //mysql_free_result($this->result);
            mysqli_close($this->con);
            $this->connected    =   false;
            $this->count_con    =   1;
        }
    }


    /**
     * DBConnect::saveLog()
     * 
     * @param mixed $file
     * @param mixed $content
     * @return void
     */
    private function saveLog($file, $content, $file_line = '')
    {

        $endline = "\n";
        //if($_SERVER['SERVER_NAME'] == "localhost")   $endline =  PHP_EOL;
        $break_line = "---------------------------------------------------------------------------";
        //Ten file
        $filename   =   $this->path_log . $file . ".cfn";

        //Mở file để ghi
        $handle =   fopen($filename, "a");

        //Neu ko mo duoc file thi exit
        if (!$handle) exit('Error create log!');

        if ($file_line == '') {
            $file_line  =   'File: ' . $this->GetIncludedFile();
        }

        //Noi dung luu log
        $string =   date("d/m/Y H:i:s") . ' ' . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"] . $endline;
        $string .=  "IP:" . @$_SERVER['REMOTE_ADDR'] . $endline;
        $string .=  $file_line . $endline;
        $string .=  $endline . $content . $break_line . $endline;

        fwrite($handle, $string);
        fclose($handle);
    }


    /**
     * DBConnect::query()
     * Run query select
     * @param mixed $query
     * @return record
     */
    function query($query)
    {
        $this->sql  =   $query;

        //Check slow
        $start = $this->getTime();

        /** Connect DB **/
        $this->connectDB();

        //Query
        $result = @mysqli_query($this->con, $query);

        //If fail
        if (!$result) {
            /** ======= Goi file & line thuc thi ====== **/
            $file_line  =   'File: ' . $this->GetIncludedFile();

            $error   = @mysqli_error($this->con) . "\n\n" . $query . "\n\n";

            //Save log error
            $this->saveLog('query_error', $error, $file_line);

            //Dump loi neu o local hoac test
            if (is_dev()) {
                exit($file_line . ":\n" . $error);
            }
        }
        //Check slow query
        $finish     =   $this->getTime();
        $time_query =   $finish - $start;

        /** Nếu query chậm thì lưu log lại để xử lý giảm tải **/
        if ($time_query >= $this->slow_query) {
            /** ======= Goi file & line thuc thi ====== **/
            $file_line  =   'File: ' . $this->GetIncludedFile();

            $slow   =   $query . "\n\n";
            $slow   .=  "Query time : " . number_format($time_query, 10, ".", ",") . "\n";
            $this->saveLog('query_slow', $slow, $file_line);
        }

        $this->result   =   $result;

        //Return
        return $this;
    }

    /**
     * DBConnect::execute()
     * Run query execute
     * @param mixed $query
     * @return
     */
    function execute($query, $return_err = false)
    {
        $this->sql  =   $query;

        $total_affect = 0;

        /** Connect DB **/
        $this->connectDB();

        @mysqli_query($this->con, $query);

        //kiem tra thanh cong hay chua
        $total_affect = mysqli_affected_rows($this->con);

        //neu ket qua query thuc thi khong thanh cong tru truong hop insert ignore
        if ($total_affect < 0 && strpos($query, "IGNORE") === false) {

            /** ======= Goi file & line thuc thi ====== **/
            $file_line  =   'File: ' . $this->GetIncludedFile();

            $error   = @mysqli_error($this->con) . "\n" . $query;
            $this->saveLog('query_error', $error, $file_line);
            if ($return_err) {
                return $file_line . ":\n" . $error;
            }
            if (is_dev()) {
                exit($file_line . ":\n" . $error);
            }
        }

        return $total_affect;
    }

    /**
     * DBConnect::executeReturn()
     * Run query insert and return last ID inserted
     * @param mixed $query
     * @return
     */
    function executeReturn($query)
    {
        //Return last id
        $last_id = 0;

        /** Connect DB **/
        $this->connectDB();

        @mysqli_query($this->con, $query);

        $total = @mysqli_affected_rows($this->con);

        //neu ket qua khong thanh cong và khong phai là insert ignore
        if ($total < 0 && strpos($query, "IGNORE") === false) {
            /** ======= Goi file & line thuc thi ====== **/
            $file_line  =   'File: ' . $this->GetIncludedFile();

            $error   = @mysqli_error($this->con) . "\n" . $query;
            $this->saveLog('query_error', $error, $file_line);

            if (is_dev()) {
                exit($file_line . ":\n" . $error);
            }
        }

        $result = @mysqli_query($this->con, "SELECT LAST_INSERT_ID() AS last_id");

        if ($row = @mysqli_fetch_array($result)) {
            $last_id = $row["last_id"];
        }

        return (int)$last_id;
    }

    /**
     * Database::toArray()
     * Convert result to array from self::query()
     * @return
     */
    public function toArray()
    {
        $data = [];
        while ($row = mysqli_fetch_assoc($this->result)) {
            $data[] = $row;
        }

        return $data;
    }

    /**
     * Database::getOne()
     * 
     * @return
     */
    function getOne()
    {
        if ($row = mysqli_fetch_assoc($this->result)) {
            return $row;
        }

        return [];
    }


    /**
     * DBConnect::count()
     * Count record: SELECT COUNT(id) AS total...
     * @param mixed $query
     * @return number
     */
    function count($query)
    {
        $this->sql  =   $query;

        $result = $this->query($query);

        if ($row = mysqli_fetch_assoc($this->result)) {
            return (int)$row['total'];
        }

        return 0;
    }

    /**
     * DBConnect::getTime()
     * Get micro time
     * @return
     */
    function getTime()
    {

        list($usec, $sec) = explode(" ", microtime());

        return ((float)$usec + (float)$sec);
    }


    /**
     * DBConnect::GetIncludedFile()
     * Lay thong tin file thuc thi de luu log
     * @return file_name
     */
    function GetIncludedFile()
    {
        $file       =   '';
        $backtrace  =   debug_backtrace();

        $include_functions  =   ['include', 'include_once', 'require', 'require_once'];
        $total_back         =   count($backtrace);

        for ($index = 0; $index < $total_back; $index++) {
            $function   =   $backtrace[$index]['function'];
            if (in_array($function, $include_functions)) {
                $file   =   $backtrace[$index - 1]['file'];
                break;
            }
        }

        return $file;
    }


    public function escapeString($string)
    {
        return @mysqli_real_escape_string($this->con, $string);
    }
}
