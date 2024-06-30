<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Include the database connection file
    include_once "datab.php";

    //Retrieve form data
    $username= $_POST["username"];
    $password= $_POST["password"];

    //Prepare and execute SQL query to check login credentials
    $sql="SELECT * FROM registration WHERE username=?";
    $stmt=$pdo->prepare($sql);
    $stmt->execute([$username]);
    $user=$stmt->fetch(PDO::FETCH_ASSOC);

    //Check is user exists and password is correct
    if($user){
        echo "Welcome " . $user["username"] . "!";
        header("Location: afterlogin.php");
    }else{
        echo "Invalid details";
    }
}else{
    //header("Location: afterlogin.php");
    echo "didnt read";
    exit;
}
?>