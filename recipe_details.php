<?php
session_start();
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: loginform.html");
    exit();
}

// Include database connection
include_once "datab.php";

// Retrieve recipe details based on ID
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT recipes.*, registration.fullname AS owner_name FROM recipes INNER JOIN registration ON recipes.username = registration.username WHERE recipes.id = ?");
    $stmt->execute([$_GET['id']]);
    $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$recipe) {
        die("Recipe not found.");
    }
} else {
    die("Recipe ID not specified.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recipe Details</title>
  <link rel="stylesheet" type="text/css" href="details.css">
</head>
<body>
  <div class="dashboard-container">
    <div class="header">
      <h2>Recipe Details</h2>
      <div class="user-info">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</div>
    </div>

    <div class="recipe-details">
      <div class="recipe-card">
        <img src="<?php echo htmlspecialchars($recipe['recipe_photo']); ?>" alt="Recipe Photo">
        <div class="card-content">
          <h3 class="card-title"><?php echo htmlspecialchars($recipe['recipe_name']); ?></h3>
          <p class="card-description"><strong>Ingredients:</strong><br><?php echo nl2br(htmlspecialchars($recipe['ingredients'])); ?></p>
          <p class="card-description"><strong>Instructions:</strong><br><?php echo nl2br(htmlspecialchars($recipe['instructions'])); ?></p>
          <div class="card-owner">
            Recipe by <?php echo htmlspecialchars($recipe['owner_name']); ?> &#169;
          </div>
        </div>
      </div>
    </div>

    <div class="footer">
      <a href="user_dashboard.php">Back to Dashboard</a>
      <a href="logout.php">Logout</a>
      <p>&copy; <?php echo date('Y'); ?> All rights reserved.</p>
    </div>
  </div>
</body>
</html>
