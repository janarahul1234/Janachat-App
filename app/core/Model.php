<?php

namespace App\core;

use App\core\Database;

class Model extends Database
{
    private array $query = ['all' => 'SELECT * FROM'];

    public function where(array $where = [])
    {
        if ($where) {
            $holder = '';

            foreach (array_keys($where) as $key) {
                $holder .= "{$key} = :{$key} AND ";
            }

            $holder = trim($holder, ' AND ');

            $this->query['where'] = " WHERE {$holder}";
            $this->query['param'] = $where;
        }

        return $this;
    }

    public function all()
    {
        return $this->query("{$this->query['all']} {$this->table}") ?? false;
    }

    public function get()
    {
        return $this->query(
            "{$this->query['all']} {$this->table}{$this->query['where']}",
            $this->query['param']
        ) ?? false;
    }

    public function first()
    {
        return $this->query(
            "{$this->query['all']} {$this->table}{$this->query['where']}",
            $this->query['param']
        )[0] ?? false;
    }

    public function firstWhere(array $where = [])
    {
        $this->where($where);

        return $this->query(
            "{$this->query['all']} {$this->table}{$this->query['where']}",
            $this->query['param']
        )[0] ?? false;
    }

    public function create(array $data = [])
    {
        $columns = implode(', ', array_keys($data));

        $holder = '';

        foreach (array_keys($data) as $key) {
            $holder .= ":{$key}, ";
        }

        $holder = trim($holder, ', ');

        return $this->query("INSERT INTO {$this->table} ($columns) VALUES ($holder)", $data);
    }

    public function update(array $data = [])
    {
        $holder = '';

        foreach (array_keys($data) as $key) {
            $holder .= "{$key} = :{$key}, ";
        }

        $holder = trim($holder, ', ');

        return $this->query(
            "UPDATE {$this->table} SET {$holder}{$this->query['where']}",
            array_merge($data, $this->query['param'])
        );
    }

    public function delete()
    {
        return $this->query(
            "DELETE FROM {$this->table}{$this->query['where']}",
            $this->query['param']
        );
    }

    public function parseArray(string $value): array
    {
        $pattern = '/[\,\s]/';
        return preg_split($pattern, $value, -1, PREG_SPLIT_NO_EMPTY);
    }

    public function parseString(array $array): string
    {
        return implode(',', $array);
    }
}
