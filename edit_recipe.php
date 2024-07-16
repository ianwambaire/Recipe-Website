<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['user_type'] != 'Recipe Owner') {
    header("Location: loginform.html");
    exit();
}

include_once "datab.php";

// Retrieve recipe details
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$recipe || $recipe['username'] != $_SESSION['username']) {
        die("Recipe not found or you do not have permission to edit.");
    }
} else {
    die("Recipe ID not specified.");
}

// Handle form submission for updating recipe
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $recipe_name = $_POST['recipe_name'];
    $ingredients = $_POST['ingredients'];
    $instructions = $_POST['instructions'];
    $category = $_POST['category'];

    // Validate and handle file upload if a new photo is provided
    if (!empty($_FILES["recipe_photo"]["name"])) {
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

        // Update recipe with new photo
        $recipe_photo = $target_file;
        $stmt = $pdo->prepare("UPDATE recipes SET recipe_name = ?, ingredients = ?, instructions = ?, recipe_photo = ?, category = ? WHERE id = ?");
        $stmt->execute([$recipe_name, $ingredients, $instructions, $recipe_photo, $category, $_GET['id']]);
    } else {
        // Update recipe without changing the photo
        $stmt = $pdo->prepare("UPDATE recipes SET recipe_name = ?, ingredients = ?, instructions = ?, category = ? WHERE id = ?");
        $stmt->execute([$recipe_name, $ingredients, $instructions, $category, $_GET['id']]);
    }

    if ($stmt->rowCount() > 0) {
        // Redirect to view recipes page after successful update
        header("Location: view_recipe.php");
        exit();
    } else {
        echo "Error updating recipe.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Recipe</title>
  <link rel="stylesheet" type="text/css" href="styless.css">
</head>
<body>
  <div class="dashboard-container">
    <div class="header">
      <h2>Edit Recipe</h2>
      <div class="user-info">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</div>
    </div>
    <div class="content">
      <h3>Edit Recipe Details</h3>
      <form action="edit_recipe.php?id=<?php echo $_GET['id']; ?>" method="post" enctype="multipart/form-data">
        <div class="input-group">
          <label for="category">Recipe Category:</label>
          <select id="category" name="category" required>
            <option value="">Select Category</option>
            <option value="Dessert" <?php if ($recipe['category'] == 'Dessert') echo 'selected'; ?>>Dessert</option>
            <option value="Course Meal" <?php if ($recipe['category'] == 'Course Meal') echo 'selected'; ?>>Course Meal</option>
            <option value="Appetizer" <?php if ($recipe['category'] == 'Appetizer') echo 'selected'; ?>>Appetizer</option>
            <option value="Drink" <?php if ($recipe['category'] == 'Drink') echo 'selected'; ?>>Drink</option>
          </select>
        </div>
        <div class="input-group">
          <label for="recipe_name">Recipe Name:</label>
          <input type="text" id="recipe_name" name="recipe_name" value="<?php echo htmlspecialchars($recipe['recipe_name']); ?>" required>
        </div>
        <div class="input-group">
          <label for="ingredients">Ingredients:</label>
          <textarea id="ingredients" name="ingredients" rows="4" required><?php echo htmlspecialchars($recipe['ingredients']); ?></textarea>
        </div>
        <div class="input-group">
          <label for="instructions">Instructions:</label>
          <textarea id="instructions" name="instructions" rows="4" required><?php echo htmlspecialchars($recipe['instructions']); ?></textarea>
        </div>
        <div class="input-group">
          <label for="recipe_photo">Change Photo:</label>
          <input type="file" id="recipe_photo" name="recipe_photo" accept="image/*">
        </div>
        <button type="submit">Update Recipe</button>
      </form>
    </div>
    <div class="footer">
      <a href="view_recipe.php">Back to Recipes</a>
      <a href="owner_dashboard.php">Back to Dashboard</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>
</body>
</html>
