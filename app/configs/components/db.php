<?php

// DataBase
return [
    'db' => [
        'class' => '\Micro\db\DbConnection',
        'connectionString' => 'mysql:host=localhost;dbname=micro',
        'username' => 'micro',
        'password' => 'micro',
        'options' => [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'']
    ]
];