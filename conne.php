<?php
echo "<table style='border: solid 1px black;'>";
echo "<tr><th>Id</th><th>fullname</th><th>username</th></tr>";

class TableRows extends RecursiveIteratorIterator {
   function __construct($it) {
       parent::__construct($it, self::LEAVES_ONLY);
   }

   function current() {
       return "<td style='width: 150px; border: 1px solid black;'>" . parent::current(). "</td>";
   }

   function beginChildren() {
       echo "<tr>";
   }

   function endChildren() {
       echo "</tr>" . "\n";
   }
} 
        $serverName = "localhost:3307";
        $dbUserName = "root";
        $dbPassword = "oliviamumbi2010";
        $dbName = "recipedatabase";
        try {
            $pdo = new PDO("mysql:host=$serverName;dbname=$dbName", $dbUserName, $dbPassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt=$pdo->prepare("SELECT id, username, fullname FROM registration");
            $stmt->execute();
            $pdo->exec("set names utf8");

            $result=$stmt->setFetchMode(PDO::FETCH_ASSOC);
            foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v){
                echo $v;
            }
        } catch (PDOException $e) {
            die("Error: Could not connect to the database. " . $e->getMessage());
        }
        $pdo=null;
        echo "</table>";
        
?>