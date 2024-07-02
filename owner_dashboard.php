<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['user_type'] != 'Recipe Owner') {
    header("Location: loginform.html");
    exit();
}

// Include the database connection file
include_once "datab.php";

// Fetch user details
$sql = "SELECT * FROM registration WHERE username = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['username']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $recipe_name = $_POST['recipe_name'];
    $ingredients = $_POST['ingredients'];
    $instructions = $_POST['instructions'];
    $category = $_POST['category'];

    // Validate and handle file upload
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
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Owner Homepage</title>
  <link rel="stylesheet" type="text/css" href="styless.css">
</head>
<body>
  <div class="dashboard-container">
    <div class="header">
      <h2>Owner Homepage</h2>
      <div class="user-info">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</div>
    </div>
    <div class="content">
      <h3>Profile Details</h3>
      <p><strong>Full Name:</strong> <?php echo htmlspecialchars($user['fullname']); ?></p>
      <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
      <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
      <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
      <p><strong>Bio:</strong> <?php echo htmlspecialchars($user['bio']); ?></p>
      <h3>Add a Recipe</h3>
      <form action="owner_dashboard.php" method="post" enctype="multipart/form-data">
        <div class="input-group">
          <label for="category">Recipe Category:</label>
          <select id="category" name="category" required>
            <option value="">Select Category</option>
            <option value="Dessert">Dessert</option>
            <option value="Course Meal">Course Meal</option>
            <option value="Appetizer">Appetizer</option>
            <option value="Drink">Drink</option>
          </select>
        </div>
        <div class="input-group">
          <label for="recipe_name">Recipe Name:</label>
          <input type="text" id="recipe_name" name="recipe_name" required>
        </div>
        <div class="input-group">
          <label for="ingredients">Ingredients:</label>
          <textarea id="ingredients" name="ingredients" rows="4" required></textarea>
        </div>
        <div class="input-group">
          <label for="instructions">Instructions:</label>
          <textarea id="instructions" name="instructions" rows="4" required></textarea>
        </div>
        <div class="input-group">
          <label for="recipe_photo">Upload Photo:</label>
          <input type="file" id="recipe_photo" name="recipe_photo" accept="image/*" required>
        </div>
        <button type="submit">Add Recipe</button>
      </form>
    </div>
    <div class="footer">
      <a href="logout.php">Logout</a>
    </div>
  </div>
</body>
</html>
