<?php

session_start();

include "db_connection.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];


    // Find user by email

    $sql = "SELECT * FROM users WHERE Email = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "s",
        $email
    );


    $stmt->execute();


    $result = $stmt->get_result();



    if ($result->num_rows == 1) {


        $user = $result->fetch_assoc();



        // Check password

        if (password_verify($password, $user["Password"])) {


            $_SESSION["UserID"] = $user["UserID"];
            $_SESSION["Username"] = $user["Username"];
            $_SESSION["Role"] = $user["Role"];



            // Role-based access

            if ($user["Role"] == "Admin") {

                header("Location: ../../frontend/admin_dashboard.php");
                exit();
            } else {

                header("Location: ../../frontend/user_home.php");
                exit();
            }
        } else {

            echo "Incorrect password.";
        }
    } else {

        echo "Account not found.";
    }


    $stmt->close();
}


$conn->close();
