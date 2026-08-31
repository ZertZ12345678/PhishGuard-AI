<?php

include "db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];


    // Check password match

    if ($password !== $confirm_password) {

        die("Passwords do not match.");
    }


    // Check existing username/email

    $check_sql = "SELECT * FROM users WHERE Username = ? OR Email = ?";

    $check_stmt = $conn->prepare($check_sql);

    $check_stmt->bind_param(
        "ss",
        $username,
        $email
    );

    $check_stmt->execute();

    $result = $check_stmt->get_result();


    if ($result->num_rows > 0) {

        die("Username or email already exists.");
    }


    // Hash password

    $hashed_password = password_hash(
        $password,
        PASSWORD_DEFAULT
    );


    // Default role

    $role = "User";


    // Insert user

    $sql = "INSERT INTO users
            (Username, Email, Password, Role)
            VALUES (?, ?, ?, ?)";


    $stmt = $conn->prepare($sql);


    $stmt->bind_param(
        "ssss",
        $username,
        $email,
        $hashed_password,
        $role
    );


    if ($stmt->execute()) {

        header("Location: ../../frontend/user_home.php");
        exit();
    } else {

        echo "Registration failed: " . $conn->error;
    }


    $stmt->close();
    $check_stmt->close();
}


$conn->close();
