<?php

namespace Efrogg\Db\Adapters\Pdo;

use Efrogg\Db\Adapters\DbAdapter;
use Efrogg\Db\Adapters\DbResultAdapter;
use Efrogg\Db\Adapters\AbstractDbAdapter;
use Efrogg\Db\Exception\DbException;
use Efrogg\Db\Adapters\Mysql\MysqlDbResult;
use Efrogg\Db\Adapters\Pdo\PdoDbResult;
use Efrogg\Db\Query\DbQueryBuilder;

class PdoDbAdapter extends AbstractDbAdapter{
    /** @var  \PDO */
    protected $db;
    /** @var  PdoDbResult */
    protected $lastResult;
    /** @var  \PDOStatement */
    protected $lastStmt;


    /**
     * PrestashopDbAdapter constructor.
     * @param \PDO $db
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }


    /**
     * @param $query
     * @param array $params
     * @param bool $forceMaster
     * @return DbResultAdapter
     * @throws DbException
     */
    public function execute($query, $params = array(), $forceMaster = false)
    {
        if($query instanceof DbQueryBuilder) $query = $query->buildQuery();

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        $result = new PdoDbResult($stmt);

        if($this->throws_exceptions && !$result->isValid()) {
//            var_dump($result->getErrorMessage(),$result->getErrorCode());
            throw new DbException($result->getErrorMessage(),$result->getErrorCode());
        }
        $this->lastResult = $result;
        $this->lastStmt = $stmt;
        return $result;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->db->errorInfo();
    }

    /**
     * @return int
     */
    public function getInsertId()
    {
        return $this->db->lastInsertId();
    }

    /**
     * @return int
     */
    public function getAffectedRows()
    {
        return $this->lastStmt->rowCount();
    }

}