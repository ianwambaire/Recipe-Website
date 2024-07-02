<?php
 session_start();
 if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    header("Location: loginform.html");
    exit();
 }
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
      
      <div class="card">
        <img src="desserts.jpg" alt="Desserts">
        <div class="card-content">
          <h3 class="card-title">Desserts</h3>
          <p class="card-description">Explore delicious desserts.</p>
        </div>
      </div>

      
      <div class="card">
        <img src="course_meals.jpg" alt="Course Meals">
        <div class="card-content">
          <h3 class="card-title">Course Meals</h3>
          <p class="card-description">Discover hearty course meals.</p>
        </div>
      </div>

      
      <div class="card">
        <img src="appetizers.jpg" alt="Appetizers">
        <div class="card-content">
          <h3 class="card-title">Appetizers</h3>
          <p class="card-description">Find tasty appetizers.</p>
        </div>
      </div>

      
      <div class="card">
        <img src="drinks.jpg" alt="Drinks">
        <div class="card-content">
          <h3 class="card-title">Drinks</h3>
          <p class="card-description">Enjoy refreshing drinks.</p>
        </div>
      </div>

      

    </div>

    <div class="footer">
      <a href="logout.php">Logout</a>
    </div>
  </div>
</body>
</html>
