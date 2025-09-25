<?php
namespace App\Repositories;

abstract class BaseRepository {
    protected $db;
    protected $table;

    public function __construct($database) {
        $this->db = $database;
    }

    public function findAll($limit = null, $offset = null) {
        $sql = "SELECT * FROM {$this->table}";

        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
            if ($offset) {
                $sql .= " OFFSET " . intval($offset);
            }
        }

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->getPrimaryKey()} = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findBy($column, $value) {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$value]);
        return $stmt->fetchAll();
    }

    public function findOneBy($column, $value) {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$value]);
        return $stmt->fetch();
    }

    public function create($data) {
        $columns = array_keys($data);
        $placeholders = array_fill(0, count($columns), '?');

        $sql = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ")
                VALUES (" . implode(', ', $placeholders) . ")";

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(array_values($data));

        if ($result) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    public function update($id, $data) {
        $columns = array_keys($data);
        $setClause = implode(' = ?, ', $columns) . ' = ?';

        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->getPrimaryKey()} = ?";

        $values = array_values($data);
        $values[] = $id;

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE {$this->getPrimaryKey()} = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function count($conditions = []) {
        $sql = "SELECT COUNT(*) FROM {$this->table}";

        if (!empty($conditions)) {
            $whereClause = [];
            $values = [];

            foreach ($conditions as $column => $value) {
                $whereClause[] = "{$column} = ?";
                $values[] = $value;
            }

            $sql .= " WHERE " . implode(' AND ', $whereClause);
            $stmt = $this->db->prepare($sql);
            $stmt->execute($values);
        } else {
            $stmt = $this->db->query($sql);
        }

        return $stmt->fetchColumn();
    }

    protected function getPrimaryKey() {
        return $this->primaryKey ?? 'id';
    }

    protected function buildWhereClause($conditions) {
        if (empty($conditions)) {
            return ['', []];
        }

        $whereClause = [];
        $values = [];

        foreach ($conditions as $column => $value) {
            if (is_array($value)) {
                $placeholders = array_fill(0, count($value), '?');
                $whereClause[] = "{$column} IN (" . implode(', ', $placeholders) . ")";
                $values = array_merge($values, $value);
            } else {
                $whereClause[] = "{$column} = ?";
                $values[] = $value;
            }
        }

        return [' WHERE ' . implode(' AND ', $whereClause), $values];
    }
}
?>