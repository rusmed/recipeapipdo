<?php

namespace App\Models\Db;

use Illuminate\Support\Facades\DB;

abstract class Base
{
    /**
     * Db Adapter
     *
     * @var object
     */
    protected $_db;

    public function __construct()
    {
        $this->_db = DB::connection()->getPdo();
    }

    protected function fetchAll($query, array $params = [])
    {
        if (empty($params)) {
            $stmt = $this->_db->query($query);
        } else {
            $stmt = $this->_db->prepare($query);
            $stmt->execute($params);
        }

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    protected function fetchRow($query, array $params = [])
    {
        if (empty($params)) {
            $stmt = $this->_db->query($query);
        } else {
            $stmt = $this->_db->prepare($query);
            $stmt->execute($params);
        }

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    protected function execute($query, array $params = [])
    {
        $stmt = $this->_db->prepare($query);
        $stmt->execute($params);
    }

    protected function getLastInsertId()
    {
        return $this->_db->lastInsertId();
    }

    protected function rowToModel($row = null)
    {
        if (!$row) return null;

        $data = [];
        foreach ($row as $columnName => $value) {
            if (array_key_exists($columnName, $this->_map))
                $data[$this->_map[$columnName]] = $value;
        }

        return new $this->_modelType($data);
    }

    public function modelToArray($model)
    {
        $arrDiff = array_diff(array_flip($this->_map), $this->_hidden);

        $row = [];
        foreach ($arrDiff as $columnName => $propertyName) {
            $method = 'get' . ucfirst($propertyName);
            if (method_exists($model, $method))
                $row[$columnName] = $model->$method();
        }

        return $row;
    }
}
