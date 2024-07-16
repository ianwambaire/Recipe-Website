<?php
session_start();
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: loginform.html");
    exit();
}

// Include database connection
include_once "datab.php";

// Fetch all recipes and their owners
$stmt = $pdo->query("SELECT recipes.*, registration.fullname AS owner_name FROM recipes INNER JOIN registration ON recipes.username = registration.username");
$recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Dashboard</title>
  <link rel="stylesheet" type="text/css" href="userdash.css">
</head>
<body>
  <div class="dashboard-container">
    <div class="header">
      <h2>User Home Page</h2>
      <div class="user-info">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</div>
    </div>

    <div class="cards-container">
      <?php foreach ($recipes as $recipe): ?>
      <div class="card">
        <a href="recipe_details.php?id=<?php echo $recipe['id']; ?>" class="recipe-link">
          <img src="<?php echo htmlspecialchars($recipe['recipe_photo']); ?>" alt="Recipe Photo">
          <div class="card-content">
            <h3 class="card-title"><?php echo htmlspecialchars($recipe['recipe_name']); ?></h3>
          </div>
        </a>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="footer">
      <p>&copy; <?php echo date('Y'); ?> All rights reserved.</p>
    </div>
  </div>
</body>
</html>
