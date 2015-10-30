<?php

namespace Footstones\Framework\Dao;

use Footstones\Framework\Kernel;
use Footstones\Framework\Common\DynamicQueryBuilder;
use Footstones\Framework\Common\DaoException;
use Footstones\Framework\Common\FieldSerializer;

abstract class BaseDao
{
    private static $cachedSerializer = array();

    public function get($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ? LIMIT 1";

        return $this->db()->fetchAssoc($sql, array($id)) ?: null;
    }

    public function create($fields)
    {
        $affected = $this->db()->insert($this->table, $fields);
        if ($affected <= 0) {
            throw $this->createDaoException('Insert error.', 51001);
        }

        return $this->get($this->db()->lastInsertId());
    }

    public function update($id, $fields)
    {
        $this->db()->update($this->table, $fields, array('id' => $id));

        return $this->get($id);
    }

    public function delete($id)
    {
        return $this->db()->delete($this->table, array('id' => $id));
    }

    public function search($conditions = array(), $orderBy = array(), $start = 0, $limit = 10)
    {
        $this->filterStartLimit($start, $limit);
        $builder = $this->createQueryBuilder($conditions)
            ->select('*')
            ->setFirstResult($start)
            ->setMaxResults($limit);
        foreach ($orderBy as $field => $order) {
            $builder->addOrderBy($field, $order);
        }
        return $builder->execute()->fetchAll() ?: array();
    }

    public function searchCount($conditions)
    {
        $builder = $this->createQueryBuilder($conditions)
            ->select('COUNT(id)');

        return $builder->execute()->fetchColumn(0) ? : 0;
    }

    protected function getTableColumns()
    {
        $sql = "DESC {$this->table}";
        $columnDescs = $this->db()->fetchAll($sql, array());

        $columns = array();
        foreach ($columnDescs as $columnDesc) {
            $columns[] = $columnDesc['Field'];
        }

        return $columns;
    }

    protected function createQueryBuilder($conditions)
    {
        $conditions = array_filter($conditions, function ($value) {
            if ($value === '' || $value === null) {
                return false;
            }

            return true;
        });

        $columns = $this->getTableColumns();

        $builder = $this->createDynamicQueryBuilder($conditions)
            ->from($this->table, 't');

        foreach ($columns as $column) {
            $builder->andWhere("{$column} = :{$column}");
        }

        foreach ($conditions as $key => $value) {
            if (stripos('like', $key) &&in_array(substr($value, 0, -4), $columns)) {
                $conditions[$key] = "%{$conditions[$key]}%";
                $column = substr($value, 0, -4);
                $builder->andWhere("{$column} = :{$key}");
            }

            if (stripos('lt', $key) && in_array(substr($value, 0, -2), $columns)) {
                $column = substr($value, 0, -2);
                $builder->andWhere("{$column} < :{$key}");
            }

            if (stripos('eqorlt', $key) && in_array(substr($value, 0, -6), $columns)) {
                $column = substr($value, 0, -6);
                $builder->andWhere("{$column} =< :{$key}");
            }

            if (stripos('gt', $key) &&in_array(substr($value, 0, -2), $columns)) {
                $column = substr($value, 0, -2);
                $builder->andWhere("{$column} > :{$key}");
            }

            if (stripos('eqorgt', $key) &&in_array(substr($value, 0, -6), $columns)) {
                $column = substr($value, 0, -6);
                $builder->andWhere("{$column} >= :{$key}");
            }

        }

        return $builder;
    }

    protected function getTable()
    {
        return $this->table;
    }

    protected function setTable($table)
    {
        $this->table = $table;
    }

    public function db()
    {
        return Kernel::instance()->database();
    }

    protected function kernel()
    {
        return Kernel::instance();
    }

    protected function createDynamicQueryBuilder($conditions)
    {
        return new DynamicQueryBuilder($this->db(), $conditions);
    }

    protected function filterStartLimit(&$start, &$limit)
    {
        $start = (int) $start;
        $limit = (int) $limit;
    }

    protected function createDaoException($message = null, $code = 0)
    {
        return new DaoException($message, $code);
    }

    protected function createSerializer()
    {
        if (!isset(self::$cachedSerializer['field_serializer'])) {
            self::$cachedSerializer['field_serializer'] = new FieldSerializer();
        }
        return self::$cachedSerializer['field_serializer'];
    }
}
