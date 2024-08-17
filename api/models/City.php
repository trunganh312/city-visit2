<?
class City
{
    private $table_name = 'city';
    private $DB;

    public function  __construct($DB)
    {
        $this->DB = $DB;
    }

    // Lấy ra tất cả huyện
    public function getAll()
    {
        $sql = "SELECT * FROM " . $this->table_name;
        return  $this->DB->query($sql)->toArray();
    }
}
