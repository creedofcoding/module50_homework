<?php
define('DATABASE', 'sqlite:' . realpath(__DIR__ . '/database/database.db')); //путь к БД
define('UPLOAD_DIR', 'uploads/'); //путь к загруженным файлам

//define('URL', '/');
//define('UPLOAD_MAX_SIZE', 5000000); // 5 MB
define('ALLOWED_TYPES', ['image/jpg', 'image/jpeg', 'image/png', 'image/gif']);
