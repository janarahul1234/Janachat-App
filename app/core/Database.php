<?php

namespace App\core;

use PDO;

class Database
{
    private function get_connection() {
        $db_url = "mysql:host={$_ENV['DATABASE_HOST']};dbname={$_ENV['DATABASE_NAME']}";

        try {
            $pdo = new PDO($db_url, $_ENV['DATABASE_USER'], $_ENV['DATABASE_PASSWORD']);
            return $pdo;
        } catch (\PDOException $e) {
            exit("Error: {$e->getMessage()}<br/>");
        }
    }

    public function query(string $query, array $values = []) {
        $conn = $this->get_connection();
        $values = $this->convert($values);

        
        try {
            $stmt = $conn->prepare($query);
            $stmt->execute($values);
        } catch (\PDOException $e) {
            exit("Error: {$e->getMessage()}");
        }
        
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        return $result ? $result : null;
    }

    private function convert(array $values = []) {
        $temp = [];

        foreach ($values as $key => $value) {
            $temp[":{$key}"] = $value;
        }

        return $temp;
    }
}