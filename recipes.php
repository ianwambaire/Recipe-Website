<?php
session_start(); // Start session if not already started

// Include database connection
include_once "datab.php";

// Fetch recipes from database
$stmt = $pdo->query("SELECT * FROM recipes");
$recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipes</title>
    <link rel="stylesheet" type="text/css" href="main.css">
</head>
<body>
    <div id="home">
        <header>
            <div id="logo">
                <img src="landingsite/chef-removebg-preview.png" height="160px" width="160px" alt="iangotyou">
            </div>
            <div id="navigation">
                <ul>
                    <li><a href="register.html">REGISTER</a></li>
                    <li><a href="loginform.html">LOGIN</a></li>
                    <li><a href="index.html">HOME</a></li>
                </ul>
            </div>
        </header>
        <main>
            <div class="recipe-section">
                <h1>Our Recipes</h1>
                <div class="recipe-container">
                    <?php foreach ($recipes as $recipe): ?>
                    <div class="recipe-card">
                        <a href="<?php echo isset($_SESSION['username']) ? 'recipe-details.php?id='.$recipe['id'] : 'loginform.html'; ?>">
                            <img src="<?php echo htmlspecialchars($recipe['recipe_photo']); ?>" alt="<?php echo htmlspecialchars($recipe['recipe_name']); ?>">
                            <h4><?php echo htmlspecialchars($recipe['recipe_name']); ?></h4>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
        <footer>
            <div id="contact" class="contact-section">
                <div class="row">
                    <div class="column">
                        <h2>Contact Us</h2>
                        <p>If you have any questions or inquiries, feel free to reach out to us using the contact information below:</p>
                        <p>Email: ian.wambaire@strathmore.edu</p>
                        <p>Phone: +254 728 970-567</p>
                        <p>Address: 454 Ole Sangare Road, Nairobi, Kenya</p>
                    </div>
                    <div class="column">
                        <img src="landingsite/homebackgroundlayout.jpg" alt="Contact Image" width="400" height="300">
                    </div>
                </div>
            </div>
        </footer> 
    </div>    
</body>
</html>
