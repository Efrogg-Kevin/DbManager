<?php
namespace Efrogg\Db\Adapters\Mysql;


use Efrogg\Db\Adapters\DbResultAdapter;

class MysqlDbResult implements DbResultAdapter {

    /**
     * @var resource
     */
    private $resource = false;

    public function __construct($resource) {
        $this->resource = $resource;
    }

    public function fetch($type=self::FETCH_TYPE_ASSOC)
    {
        if($type == self::FETCH_TYPE_ASSOC) {
            return $this -> resource ? mysql_fetch_assoc($this -> resource) : false;
        } elseif($type == self::FETCH_TYPE_ARRAY) {
            return $this -> resource ? mysql_fetch_array($this -> resource,MYSQL_NUM) : false;
        } else {
            return $this -> resource ? mysql_fetch_array($this -> resource) : false;
        }
    }

    public function fetchAll($type=self::FETCH_TYPE_ASSOC)
    {
        $data = array();
        while($line = $this -> fetch($type)) {
            $data[]=$line;
        }
        return $data;
    }

    public function fetchColumn($column = 0)
    {
        $result=array();
        if($this -> resource) {
            for($i=0;$i<mysql_num_rows($this -> resource);$i++) {
                $result[]=mysql_result($this -> resource,$i,$column);
            }
        }
        return $result;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this -> resource !== false;
    }

    /**
     * @param $class_name
     * @param array $params
     * @return array
     */
    public function fetchObject($class_name = null, array $params = null)
    {
        if(is_null($params)) {
            return mysql_fetch_object($this->resource, $class_name);
        } else {
            return mysql_fetch_object($this->resource, $class_name, $params);
        }
    }

    public function fetchAllObject($class_name = "stdClass", array $params = null)
    {
        $result=array();
        if($this -> resource) {
            if(mysql_num_rows($this -> resource)>0) mysql_data_seek($this -> resource,0); // repart du debut

            if(is_null($params)) {
                while($res=mysql_fetch_object($this -> resource,$class_name  ))
                    $result[]=$res;
            } else {
                while($res=mysql_fetch_object($this -> resource,$class_name , $params ))
                    $result[]=$res;
            }

            if(mysql_num_rows($this -> resource)>0) mysql_data_seek($this -> resource,0);
        }
        return $result;
    }

    public function getErrorCode()
    {
        return mysql_errno($this->resource);    //todo : ne doit pas marcher....
    }

    public function getErrorMessage()
    {
        return mysql_error($this->resource);   //todo : ne doit pas marcher.... non plus ....
    }
}