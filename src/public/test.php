<?php

$pdo = new PDO('mysql:dbname=kanas;host=mysql', 'root', 'password', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$query = $pdo->query('SHOW VARIABLES like "version"');

$result = $query->fetch();

echo "Database version: " . $result['Value'];
