<?php

namespace App\Database;

use PDO;
use PDOException;

class Connection
{
    private $MYSQL_HOST;
    private $MYSQL_USER;
    private $MYSQL_PASSWD;
    private $MYSQL_DATABASE;

    public function __construct(
        string $MYSQL_HOST,
        string $MYSQL_USER,
        string $MYSQL_PASSWD,
        string $MYSQL_DATABASE
    ) {
        $this->MYSQL_HOST = $MYSQL_HOST;
        $this->MYSQL_USER = $MYSQL_USER;
        $this->MYSQL_PASSWD = $MYSQL_PASSWD;
        $this->MYSQL_DATABASE = $MYSQL_DATABASE;
    }

    /**
     * Estabelece conexão com o banco de dados
     * 
     * @return PDO|string Retorna objeto PDO ou mensagem de erro
     */
    public function connect()
    {
        try {
            $pdo = new PDO("mysql:host=" . $this->MYSQL_HOST . ";dbname=" . $this->MYSQL_DATABASE, $this->MYSQL_USER, $this->MYSQL_PASSWD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $pdo;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Executado uma query
     * 
     * @param string $query Query que sera executada
     * @return array Retorna dados obtidos ou mensagem de erro
     */
    public function executeQuery(string $query)
    {
        try {
            $connection = $this->connect();

            $respone = $connection->query($query);
            return $respone->fetchAll();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Obtém dados de uma tabela com mapeamento de campos
     * 
     * @param string $table Nome da tabela
     * @param array $fields Array associativo com mapeamento de campos [campo_original => alias]
     * @return array Retorna dados obtidos ou mensagem de erro
     */
    public function getFields(string $table, array $fields)
    {
        $sqlString = '';
        foreach ($fields as $chave => $valor) {
            $sqlString .= "$chave as $valor, ";
        }

        $sqlString = rtrim($sqlString, ', ');

        try {
            $connection = $this->connect();

            // dd("SELECT {$sqlString} FROM {$table}");
            // exit;

            $respone = $connection->query("SELECT {$sqlString} FROM {$table}");
            return $respone->fetchAll();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Migra dados de produtos entre bancos de dados
     * 
     * @param string $table Tabela de destino da migração
     * @param PDO $newDatabase instância do PDO contendo a conexão com o banco destino
     * @param array $fields Mapeamento de campos [campo_origem => campo_destino] (todos os campos da consulta serão criados apartir desse array)
     * @param array $data Dados que serão inseridos, sendo um array associativo retornado do método getFields  
     * @return string Mensagem de sucesso ou mensagem de erro vindo de uma PDOException
     */
    public function migrateData(string $table, PDO $newDatabase, array $fields, array $data)
    {
        $fieldsString = implode(', ', $fields);
        $fieldsString = rtrim($fieldsString, ', ');

        $placeholders = '';
        foreach ($fields as $value) {
            $placeholders .= ":$value, ";
        }
        $placeholders = rtrim($placeholders, ', ');

        try {
            foreach ($data as $value) {
                $stmt = $newDatabase->prepare("INSERT INTO {$table} ({$fieldsString}) VALUES ($placeholders) ");
                $stmt->execute($value);
            }
            echo "Migração feita com sucesso";
        } catch (PDOException $e) {
            dd($e);
        }
    }
}
