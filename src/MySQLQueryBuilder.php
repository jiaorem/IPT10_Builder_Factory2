<?php

define('QUERY_TYPE_SELECT', 'select');
// define('QUERY_TYPE_INSERT', 'insert');
// define('QUERY_TYPE_UPDATE', 'update');
// define('QUERY_TYPE_DELETE', 'delete');

class MySQLQueryBuilder implements SQLQueryBuilder 
{
    protected $query;

    protected function reset()
    {
        $this->query = new \stdClass();
    }

    public function select($table, $fields)
    {
        $this->reset();
        $this->query->base = 'SELECT ' . implode(',', $fields) . ' From ' . $table;
        $this->query->type = QUERY_TYPE_SELECT;
        return $this;
    }
    // public function insert($table, $data)
    // {

    // }
    public function where($field, $operator, $value)
	{
		$filter = "$field {$operator} '{$value}'";
		array_push($this->query->where, $filter);

		return $this;
	}

	public function limit($start = null, $offset = null)
	{
		if (!in_array($this->query->type, [QUERY_TYPE_SELECT])) {
            throw new \Exception("LIMIT can only be added to SELECT");
        }
        $this->query->limit = " LIMIT " . $start . ", " . $offset;

        return $this;
	}

	public function join($table, $field1, $field2)
	{
		$join_expression = " JOIN {$table} ON ({$field1}={$field2})";
		array_push($this->query->joins, $join_expression);

		return $this;
	}

    public function orderBy($field, $order = 'ASC')
    {
        if (empty($field)) return $this;
        $this->query->base .= 'ORDER BY ' . $field . ' ' . $order;

        return $this;
    }

	public function getSQL()
	{
		$query = $this->query;
		$sql = $query->base;

		if (!empty($query->joins)) {
			$sql .= implode('', $query->joins);
		}

		if (!empty($query->where)) {
			$sql .= " WHERE ";
			$sql .= implode(' AND ', $query->where);
		}

		if (!is_null($query->limit)) {
			$sql .= " LIMIT " . $query->limit;
		}

		if (!is_null($query->offset)) {
			$sql .= " , " . $query->offset;
		}

		return $sql;
	}
}