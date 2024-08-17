<?

/**
 * Class Model
 * Created by SenEnter
 **/

class Model
{

    public  $DB;
    public  $error  =   [];

    function __construct()
    {
        $this->DB   =   new Database;
    }

    /**
     * Model::addError()
     * 
     * @param mixed $error
     * @return void
     */
    function addError($error)
    {

        if (empty($error))  return;

        if (gettype($error) == 'array') {
            $this->error    =   array_merge($this->error, $error);
        } else {
            $this->error[]  =   $error;
        }
    }

    /**
     * Model::getError()
     * 
     * @return
     */
    function getError()
    {
        return $this->error;
    }

    /**
     * Model::getRecordInfo()
     * 
     * @param mixed $table
     * @param mixed $field_id
     * @param mixed $id
     * @param string $field
     * @return
     */
    function getRecordInfo($table, $field_id, $id, $field = '*')
    {
        $row    =   $this->DB->query("SELECT " . $field . "
                                        FROM " . $table . "
                                        WHERE " . $field_id . " = " . $id)
            ->getOne();
        return $row;
    }

    /**
     * GeneralModel::getListData()
     * L?y ra list c?a table theo di?u ki?n WHERE
     * @param mixed $table
     * @param mixed $field
     * @param string $where: Ko bao g?m AND ho?c OR ? d?ng tru?c
     * @param string $order_by
     * @param string $type_return: key OR row
     * @return [$row] or [key => value]
     */
    function getListData($table, $field, $where = '', $order_by = '', $type_return = 'key', $limit = 0)
    {

        $sql_where  =   "1";

        if ($where != '') $sql_where    .=  " AND " . $where;
        if ($order_by != '')    $order_by   =   " ORDER BY " . $order_by;

        //N?u type_return = key thì c?n gán 2 tru?ng c?n l?y thành id và name
        if ($type_return == 'key') {
            $exp    =   explode(',', $field);
            if (count($exp) == 2) {
                $field  =   trim($exp[0]) . " AS id, " . trim($exp[1]) . " AS name";
            } else {
                return [];
            }
        }

        $data   =   $this->DB->query("SELECT " . $field . "
                                        FROM " . $table . "
                                        WHERE " . $sql_where
            . $order_by . ($limit > 0 ? " LIMIT " . (int)$limit : ""))
            ->toArray();
        //N?u mu?n tr? v? là m?ng ko có key thì return luôn
        if ($type_return == 'row')   return $data;

        //N?u mu?n tr? v? là m?ng có key => value
        $array_return   =   [];
        foreach ($data as $row) {
            $array_return[$row['id']]   =   $row['name'];
        }

        return $array_return;
    }
}
