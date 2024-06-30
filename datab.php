<?php
        $serverName = "localhost:3307";
        $dbUserName = "root";
        $dbPassword = "oliviamumbi2010";
        $dbName = "recipedatabase";
        try {
            $pdo = new PDO("mysql:host=$serverName;dbname=$dbName", $dbUserName, $dbPassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $pdo->exec("set names utf8");
        } catch (PDOException $e) {
            die("Error: Could not connect to the database. " . $e->getMessage());
        }
?>