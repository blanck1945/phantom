<?php

namespace Core\Model;

use Core\Database\Database;

class PhantomModel
{
    public string $table;
    private array $fillable = [];
    public function __construct(private Database $db) {}
    public function findAll()
    {
        return $this->db->findAll($this->table);
    }

    public function findOne(array $where)
    {
        return $this->db->findOne($this->table, $where, $this->fillable);
    }

    /**
     * HELPERS
     */
    public function set_props_to_query() {}
}
