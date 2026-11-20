<?php
return [
 'dsn'=>getenv('DB_DSN') ?: 'mysql:host=127.0.0.1;port=3306;dbname=academia_meridiano;charset=utf8mb4',
 'user'=>getenv('DB_USER') ?: 'root',
 'password'=>getenv('DB_PASSWORD') ?: '',
 'admin_hash'=>getenv('ADMIN_HASH') ?: ''
];
