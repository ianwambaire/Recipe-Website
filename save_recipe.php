<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include the database connection file
    include_once "datab.php";

    // Retrieve form data
    $category = $_POST["category"];
    $recipe_name = $_POST["recipe_name"];
    $recipe_owner = $_POST["recipe_owner"];
    $ingredients = $_POST["ingredients"];
    $recipe_procedure = $_POST["recipe_procedure"];

    // Handle file upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["recipe_image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is an actual image
    $check = getimagesize($_FILES["recipe_image"]["tmp_name"]);
    if ($check === false) {
        die("File is not an image.");
    }

    // Check file size
    if ($_FILES["recipe_image"]["size"] > 500000) {
        die("Your file is too large.");
    }

    // Allow only specific file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        die("Sorry, only JPG, JPEG, PNG files are allowed.");
    }

    // Try to move the uploaded file
    if (!move_uploaded_file($_FILES["recipe_image"]["tmp_name"], $target_file)) {
        die("Sorry, there was an error uploading your file.");
    }

    // Prepare and execute SQL query to save the recipe
    $sql = "INSERT INTO recipes (category_id, recipe_name, recipe_owner, ingredients, recipe_procedure, recipe_image) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$category, $recipe_name, $recipe_owner, $ingredients, $recipe_procedure, $target_file])) {
        echo "Recipe has been added successfully!";
    } else {
        echo "Error adding recipe.";
    }
}
?>
