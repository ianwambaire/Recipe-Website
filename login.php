<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include the database connection file
    include_once "datab.php";

    // Retrieve form data
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Prepare and execute SQL query to check login credentials
    $sql = "SELECT * FROM registration WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists and password is correct
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_type'] = $user['user_type'];

        // Redirect based on user type
        if ($user['user_type'] == 'Administrator') {
            header("Location: admin_dashboard.php");
        } elseif ($user['user_type'] == 'Recipe Owner') {
            header("Location: owner_dashboard.php");
        } else {
            header("Location: user_dashboard.php");
        }
        exit();
    } else {
        echo "Invalid details";
    }
} else {
    echo "Invalid request method.";
    exit();
}
?>
