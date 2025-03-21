<?php

require __DIR__ . '/vendor/autoload.php';

use \App\Database\Connection;

$oldDatabase = new Connection(
    MYSQL_HOST_OLD,
    MYSQL_USER_OLD,
    MYSQL_PASSWD_OLD,
    MYSQL_DATABASE_OLD
);
$newDatabase = new Connection(
    MYSQL_HOST_NEW,
    MYSQL_USER_NEW,
    MYSQL_PASSWD_NEW,
    MYSQL_DATABASE_NEW
);
$newConnection = $newDatabase->connect();

/**
 * @var array $data O indice se trata do nome no banco de dados antigo
 * e o value é o alias que sera passado para o sql, ou seja
 * o nome atual do campo no banco de dados da Red
 */
$data = [
    'name' => 'fullName'
];

$dataOldDatabase = $oldDatabase->getFields('produto_categoria', $data);

$query = $oldDatabase->migrateData('categoria', $newConnection, $data, $dataOldDatabase);
