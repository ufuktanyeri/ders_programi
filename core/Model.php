<?php

/**
 * Base Model Class
 * 
 * This is the base model class that all models should extend.
 * It provides common database operations and query building capabilities.
 */

abstract class Model
{
  /**
   * Database connection
   * @var PDO
   */
  protected $db;

  /**
   * Table name
   * @var string
   */
  protected $table;

  /**
   * Primary key column name
   * @var string
   */
  protected $primaryKey = 'id';

  /**
   * Fillable columns (mass assignment protection)
   * @var array
   */
  protected $fillable = [];

  /**
   * Hidden columns (won't be returned in toArray)
   * @var array
   */
  protected $hidden = [];

  /**
   * Timestamps enabled
   * @var bool
   */
  protected $timestamps = true;

  /**
   * Created at column name
   * @var string
   */
  protected $createdAt = 'created_at';

  /**
   * Updated at column name
   * @var string
   */
  protected $updatedAt = 'updated_at';

  /**
   * Constructor
   * 
   * @param PDO $db Database connection
   */
  public function __construct(PDO $db)
  {
    $this->db = $db;
  }

  /**
   * Find a record by ID
   * 
   * @param int $id The ID to search for
   * @return array|null The record or null if not found
   */
  public function find(int $id): ?array
  {
    $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['id' => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result ?: null;
  }

  /**
   * Find all records
   * 
   * @param array $conditions Optional WHERE conditions
   * @param string $orderBy Optional ORDER BY clause
   * @param int|null $limit Optional LIMIT
   * @return array Array of records
   */
  public function findAll(array $conditions = [], string $orderBy = '', ?int $limit = null): array
  {
    $sql = "SELECT * FROM {$this->table}";

    if (!empty($conditions)) {
      $whereClause = $this->buildWhereClause($conditions);
      $sql .= " WHERE {$whereClause}";
    }

    if ($orderBy) {
      $sql .= " ORDER BY {$orderBy}";
    }

    if ($limit !== null) {
      $sql .= " LIMIT {$limit}";
    }

    $stmt = $this->db->prepare($sql);
    $stmt->execute($conditions);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Find one record by conditions
   * 
   * @param array $conditions WHERE conditions
   * @return array|null The record or null if not found
   */
  public function findOne(array $conditions): ?array
  {
    $whereClause = $this->buildWhereClause($conditions);
    $sql = "SELECT * FROM {$this->table} WHERE {$whereClause} LIMIT 1";

    $stmt = $this->db->prepare($sql);
    $stmt->execute($conditions);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result ?: null;
  }

  /**
   * Create a new record
   * 
   * @param array $data Data to insert
   * @return int|bool The inserted ID or false on failure
   */
  public function create(array $data)
  {
    // Filter only fillable columns
    $data = $this->filterFillable($data);

    // Add timestamps if enabled
    if ($this->timestamps) {
      $data[$this->createdAt] = date('Y-m-d H:i:s');
      $data[$this->updatedAt] = date('Y-m-d H:i:s');
    }

    $columns = implode(', ', array_keys($data));
    $placeholders = ':' . implode(', :', array_keys($data));

    $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

    $stmt = $this->db->prepare($sql);

    if ($stmt->execute($data)) {
      return $this->db->lastInsertId();
    }

    return false;
  }

  /**
   * Update a record
   * 
   * @param int $id The ID of the record to update
   * @param array $data Data to update
   * @return bool True on success, false on failure
   */
  public function update(int $id, array $data): bool
  {
    // Filter only fillable columns
    $data = $this->filterFillable($data);

    // Add updated_at timestamp if enabled
    if ($this->timestamps) {
      $data[$this->updatedAt] = date('Y-m-d H:i:s');
    }

    $setClause = $this->buildSetClause($data);
    $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = :id";

    $data['id'] = $id;
    $stmt = $this->db->prepare($sql);

    return $stmt->execute($data);
  }

  /**
   * Delete a record
   * 
   * @param int $id The ID of the record to delete
   * @return bool True on success, false on failure
   */
  public function delete(int $id): bool
  {
    $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
    $stmt = $this->db->prepare($sql);

    return $stmt->execute(['id' => $id]);
  }

  /**
   * Count records
   * 
   * @param array $conditions Optional WHERE conditions
   * @return int The count
   */
  public function count(array $conditions = []): int
  {
    $sql = "SELECT COUNT(*) FROM {$this->table}";

    if (!empty($conditions)) {
      $whereClause = $this->buildWhereClause($conditions);
      $sql .= " WHERE {$whereClause}";
    }

    $stmt = $this->db->prepare($sql);
    $stmt->execute($conditions);

    return (int) $stmt->fetchColumn();
  }

  /**
   * Check if a record exists
   * 
   * @param array $conditions WHERE conditions
   * @return bool True if exists, false otherwise
   */
  public function exists(array $conditions): bool
  {
    return $this->count($conditions) > 0;
  }

  /**
   * Execute a raw SQL query
   * 
   * @param string $sql The SQL query
   * @param array $params Parameters to bind
   * @return array|bool Result array or boolean for non-SELECT queries
   */
  public function query(string $sql, array $params = [])
  {
    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);

    // If it's a SELECT query, return the results
    if (stripos(trim($sql), 'SELECT') === 0) {
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // For INSERT, UPDATE, DELETE, return boolean
    return $stmt->rowCount() > 0;
  }

  /**
   * Begin a transaction
   * 
   * @return bool
   */
  public function beginTransaction(): bool
  {
    return $this->db->beginTransaction();
  }

  /**
   * Commit a transaction
   * 
   * @return bool
   */
  public function commit(): bool
  {
    return $this->db->commit();
  }

  /**
   * Rollback a transaction
   * 
   * @return bool
   */
  public function rollback(): bool
  {
    return $this->db->rollBack();
  }

  /**
   * Build WHERE clause from conditions
   * 
   * @param array $conditions Conditions array
   * @return string The WHERE clause
   */
  protected function buildWhereClause(array $conditions): string
  {
    $clauses = [];

    foreach (array_keys($conditions) as $column) {
      $clauses[] = "{$column} = :{$column}";
    }

    return implode(' AND ', $clauses);
  }

  /**
   * Build SET clause for UPDATE
   * 
   * @param array $data Data array
   * @return string The SET clause
   */
  protected function buildSetClause(array $data): string
  {
    $clauses = [];

    foreach (array_keys($data) as $column) {
      $clauses[] = "{$column} = :{$column}";
    }

    return implode(', ', $clauses);
  }

  /**
   * Filter only fillable columns
   * 
   * @param array $data Input data
   * @return array Filtered data
   */
  protected function filterFillable(array $data): array
  {
    if (empty($this->fillable)) {
      return $data;
    }

    return array_intersect_key($data, array_flip($this->fillable));
  }

  /**
   * Get table name
   * 
   * @return string
   */
  public function getTable(): string
  {
    return $this->table;
  }

  /**
   * Get primary key column name
   * 
   * @return string
   */
  public function getPrimaryKey(): string
  {
    return $this->primaryKey;
  }
}
