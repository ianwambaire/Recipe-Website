<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Recipe Category</title>
    <link rel="stylesheet" href="form.css">
</head>
<body>
    <div class="form-container">
        <h2>Add Recipe Category</h2>
        <form action="savecategory.php" method="POST">
            <div class="input-group">
                <label for="category_name">Category Name:</label>
                <input type="text" id="category_name" name="category_name" placeholder="Enter category name" required>
            </div>
            <button type="submit">Add Category</button>
        </form>
    </div>
</body>
</html>
