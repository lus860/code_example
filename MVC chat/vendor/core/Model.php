<?php

namespace vendor\core;

use vendor\lib\Db;
use PDO;

abstract class Model {

	public $db;
    protected $table = '';
    protected $sql = '';
    protected $stmt = '';
    protected $selectFlag = false;

	public function __construct()
    {
        $config = CONFIG_DB;
        $this->db = new PDO('mysql:host='.$config['host'].';dbname='.$config['name'].'', $config['user'], $config['password']);
        $this->table = $this->getTable();
	}

    public function setTable($table)
    {
        $this->table = $table;
    }

    public function getTable()
    {
        $class = get_class($this);
        $classArr = explode('\\', $class);
        return lcfirst(end($classArr))."s";
    }

    public function insert($data)
    {
        $fieldsArray = [];
        $valuesArray = [];
        if (!empty($data)) {
            foreach ($data as $field => $value) {
                $fieldsArray[] = "`$field`";
                $valuesArray[] = ":$field";
            }
            $fieldsStr = implode(',', $fieldsArray);
            $valuesStr = implode(',', $valuesArray);
            $this->sql = "INSERT INTO  $this->table ($fieldsStr) VALUES ($valuesStr)";
            $this->stmt = $this->db->prepare($this->sql);
            return $this->stmt->execute($data);
        }


    }

    public function select($what = "*")
    {   $whatStr = '*';
        if (is_array($what)) {
            $whatArr = [];
            foreach ($what as $value) {
                $whatArr[] = "'$value'";
            }
            $whatStr = implode(',',$whatArr);
        } else {
            if ($what != "*") {
                $whatStr = "'$what'";
            }
        }

        $this->selectFlag = true;
        $this->sql = "SELECT $whatStr FROM $this->table";
        return $this;
    }

    public function find($id)
    {
        $this->stmt = $this->db->prepare("SELECT * FROM $this->table WHERE id = '$id' ");
        $this->stmt->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);

    }

    public function where($conditions = 1, $or = null)
    {
        $condStr = 1;
        if (is_array($conditions)) {
            $condArr = [];
            foreach ($conditions as $field => $value) {
                $condArr[] = "`$field` = \"$value\"";
            }

            $condStr = $or ? implode(' OR ',$condArr):implode(  " AND "   ,$condArr);
        }

        if ($this->selectFlag) {

            $this->sql .= " WHERE $condStr";

        } else {

            $this->sql = " WHERE $condStr";

        }

        return $this;
    }

    public function limit ($a,$b = null)
    {
        if (!$b) { $this->sql .= " LIMIT $a";
        }
        else { $this->sql .= " LIMIT $a, $b"; }
        return $this;
    }

    public function group_by ($name)
    {
        $this->sql .= " GROUP BY `$name`";
        return $this;
    }

    public function join($connection, $join = 'INNER', $table)
    {
        $this->sql = ' '.$this->sql.' '. $join.' JOIN '. $table. ' ON '. $connection;
        return $this;
    }

    public function order_by ($name, $type="ASC")
    {
        if($type == "DESC"){
            $this->sql .= " ORDER BY $name DESC";
        } else {
            $this->sql .= " ORDER BY $name";
        }
        return $this;
    }

    public function delete ($data = [])
    {
        $this->selectFlag = false;

        $this->sql = "DELETE FROM $this->table". "$this->sql";
       if(!empty($data)){
           $this->where($data);
       }
        $this->db->exec($this->sql);
    }

    public function update($data1)
    {
        $this->selectFlag = false;
        $condArr1 = [];
        foreach ($data1 as $field => $value) {
            $condArr1[] = "`$field` = \"$value\"";
        }
        $condStr1 = implode(' , ',$condArr1);

        $this->sql = "UPDATE $this->table SET $condStr1". $this->sql;
        $this->get();

    }


    public function get()
    {
        $this->stmt = $this->db->prepare($this->sql);
        $this->stmt->execute();
    }

    public function rowCount()
    {
        $this->get();
        return $this->stmt->rowCount();
    }

    public function column()
    {
        $this->get();
        return $this->stmt->fetchColumn();
    }

    public function fetch_obj() {
        $this->get();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    //Return a specific row as an object
    public function fetch_accoc() {
        $this->get();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }


}