<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['user_type'] != 'Recipe Owner') {
    header("Location: loginform.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once "datab.php";

    $recipe_name = $_POST['recipe_name'];
    $ingredients = $_POST['ingredients'];
    $instructions = $_POST['instructions'];
    $category = $_POST['category'];

    if (!isset($_FILES["recipe_photo"]) || $_FILES["recipe_photo"]["error"] != 0) {
        die("Error uploading file.");
    }

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["recipe_photo"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["recipe_photo"]["tmp_name"]);
    if ($check === false) {
        die("File is not an image.");
    }

    if ($_FILES["recipe_photo"]["size"] > 500000) {
        die("Your file is too large.");
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        die("Sorry, only JPG, JPEG, PNG files are allowed.");
    }

    if (!move_uploaded_file($_FILES["recipe_photo"]["tmp_name"], $target_file)) {
        die("Sorry, there was an error uploading your file.");
    }

    // Insert into database
    $stmt = $pdo->prepare("INSERT INTO recipes (username, recipe_name, ingredients, instructions, recipe_photo, category) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['username'], $recipe_name, $ingredients, $instructions, $target_file, $category]);

    if ($stmt->rowCount() > 0) {
        header("Location: owner_dashboard.php");
        exit();
    } else {
        echo "Error adding recipe.";
    }
}
?>
