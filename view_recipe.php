<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['user_type'] != 'Recipe Owner') {
    header("Location: loginform.html");
    exit();
}

include_once "datab.php";

// Fetch recipes
$stmt = $pdo->prepare("SELECT * FROM recipes WHERE username = ?");
$stmt->execute([$_SESSION['username']]);
$recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Recipes</title>
  <link rel="stylesheet" type="text/css" href="view.css">
</head>
<body>
  <div class="dashboard-container">
    <div class="header">
      <h2>View Recipes</h2>
      <div class="user-info">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</div>
    </div>
    <div class="content">
      <?php foreach ($recipes as $recipe): ?>
      <div class="recipe-card">
        <img src="<?php echo htmlspecialchars($recipe['recipe_photo']); ?>" alt="Recipe Photo">
        <div class="recipe-details">
          <h3><?php echo htmlspecialchars($recipe['recipe_name']); ?></h3>
          <p><strong>Category:</strong> <?php echo htmlspecialchars($recipe['category']); ?></p>
          <p><strong>Ingredients:</strong><br><?php echo nl2br(htmlspecialchars($recipe['ingredients'])); ?></p>
          <p><strong>Instructions:</strong><br><?php echo nl2br(htmlspecialchars($recipe['instructions'])); ?></p>
          <a href="edit_recipe.php?id=<?php echo $recipe['id']; ?>">Edit</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="footer">
      <a href="owner_dashboard.php">Back to Dashboard</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>
</body>
</html>
