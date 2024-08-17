<?php
// models/Hotel.php

class Common
{

    private $DB;
    public function __construct($DB)
    {
        $this->DB = $DB;
    }

    // Update status
    public function updateStatus($id, $status, $column, $table, $feild)
    {
        $sql = "UPDATE $table SET $column = $status WHERE $feild = $id";
        return $this->DB->query($sql);
    }
}
