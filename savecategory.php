<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include the database connection file
    include_once "datab.php";

    // Retrieve form data
    $category_name = $_POST["category_name"];

    // Prepare and execute SQL query to save the category
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
