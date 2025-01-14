<?php

namespace Core\Database\Interfaces;

interface MigrationManagaer
{
    public function up();
    public function down();
}
