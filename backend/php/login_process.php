<?php

session_start();

include "db_connection.php";



if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $email = trim($_POST["email"]);

    $password = $_POST["password"];



    // Find user by email

    $sql =
        "SELECT * FROM users WHERE Email = ?";


    $stmt =
        $conn->prepare($sql);


    $stmt->bind_param(
        "s",
        $email
    );


    $stmt->execute();


    $result =
        $stmt->get_result();



    if ($result->num_rows == 1) {


        $user =
            $result->fetch_assoc();



        /*
        ==========================================
        PASSWORD CHECK
        ==========================================

        Admin:
        - Simple password checking
        - For project/demo use

        User:
        - Hashed password verification
        ==========================================
        */


        $isPasswordCorrect = false;



        // Admin login

        if (
            $user["Role"] == "Admin"
            &&
            $password == $user["Password"]
        ) {

            $isPasswordCorrect = true;
        }



        // Normal user login

        elseif (

            password_verify(
                $password,
                $user["Password"]
            )

        ) {

            $isPasswordCorrect = true;
        }




        if ($isPasswordCorrect) {



            // Create session

            $_SESSION["UserID"] =
                $user["UserID"];


            $_SESSION["Username"] =
                $user["Username"];


            $_SESSION["Email"] =
                $user["Email"];


            $_SESSION["Role"] =
                $user["Role"];





            /*
            ======================================
            ROLE BASED REDIRECT
            ======================================
            */


            if (
                $user["Role"] == "Admin"
            ) {


                header(
                    "Location: ../../frontend/admin_dashboard.php"
                );


                exit();
            } else {


                header(
                    "Location: ../../frontend/user_home.php"
                );


                exit();
            }
        } else {


            echo "
            <script>
            alert('Incorrect password.');
            window.location.href='../../frontend/login.php';
            </script>
            ";
        }
    } else {


        echo "
        <script>
        alert('Account not found.');
        window.location.href='../../frontend/login.php';
        </script>
        ";
    }




    $stmt->close();
}


$conn->close();
