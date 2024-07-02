<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    include_once "datab.php";

    $category_name = $_POST["category_name"];

    $sql = "INSERT INTO categories (category_name) VALUES (?)";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$category_name])) {
        header("Location: addrecipe.php");
        exit();
    } else {
        echo "Error adding category.";
    }
}
?>
