<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once "datab.php";

    // Assign POST variables and check if they are set
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $bio = $_POST['bio'];
    $user_type = $_POST['user_type'];

    // Check if all required fields are set
    if (!$fullname || !$username || !$email || !$phone || !$password || !$bio || !$user_type) {
        die("All fields are required.");
    }

    // Check if file is set and not empty
    if (!isset($_FILES["photo"]) || $_FILES["photo"]["error"] != 0) {
        die("Error uploading file.");
    }

    // Handles file upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is an actual image
    $check = getimagesize($_FILES["photo"]["tmp_name"]);
    if ($check === false) {
        die("File is not an image.");
    }

    // Check file size
    if ($_FILES["photo"]["size"] > 500000) {
        die("Your file is too large.");
    }

    // Allow only specific file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        die("Sorry, only JPG, JPEG, PNG files are allowed.");
    }

    // Try to move the uploaded file
    if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
        die("Sorry, there was an error uploading your file.");
    }

    // Database connection
    $conn = new mysqli('localhost:3307', 'root', 'oliviamumbi2010', 'recipedatabase');
    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO registration (fullname, username, email, phone, password, photo, bio, user_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $fullname, $username, $email, $phone, $hashed_password, $target_file, $bio, $user_type);
        
        // Execute the statement
        if ($stmt->execute()) {
            header("Location: loginform.html");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>
