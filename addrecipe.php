<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Recipe</title>
    <link rel="stylesheet" href="form.css">
</head>
<body>
    <div class="form-container">
        <h2>Add Recipe</h2>
        <form action="save_recipe.php" method="POST" enctype="multipart/form-data">
            <div class="input-group">
                <label for="category">Category:</label>
                <select id="category" name="category" required>
                    <?php
                    // Fetch categories from the database
                    include_once "datab.php";
                    $sql = "SELECT * FROM categories";
                    $result = $pdo->query($sql);
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='" . $row['id'] . "'>" . $row['category_name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="input-group">
                <label for="recipe_name">Recipe Name:</label>
                <input type="text" id="recipe_name" name="recipe_name" placeholder="Enter recipe name" required>
            </div>
            <div class="input-group">
                <label for="recipe_owner">Recipe Owner:</label>
                <input type="text" id="recipe_owner" name="recipe_owner" placeholder="Enter your name" required>
            </div>
            <div class="input-group">
                <label for="ingredients">Ingredients:</label>
                <textarea id="ingredients" name="ingredients" placeholder="List ingredients" rows="4" required></textarea>
            </div>
            <div class="input-group">
                <label for="recipe_procedure">Procedure:</label>
                <textarea id="recipe_procedure" name="recipe_procedure" placeholder="Describe the procedure" rows="4" required></textarea>
            </div>
            <div class="input-group">
                <label for="recipe_image">Recipe Image:</label>
                <input type="file" id="recipe_image" name="recipe_image" accept="image/*" required>
            </div>
            <button type="submit">Add Recipe</button>
        </form>
    </div>
</body>
</html>
