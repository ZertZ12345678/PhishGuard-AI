<?php

session_start();


// =====================================================
// DATABASE CONNECTION
// =====================================================

include "db_connection.php";


// =====================================================
// ONLY ALLOW POST
// =====================================================

if (
    $_SERVER["REQUEST_METHOD"] !== "POST"
) {

    header(
        "Location: ../../frontend/login.php"
    );

    exit();
}


// =====================================================
// GET FORM DATA
// =====================================================

$token =
    trim($_POST["token"] ?? "");

$password =
    $_POST["password"] ?? "";

$confirm_password =
    $_POST["confirm_password"] ?? "";


// =====================================================
// CHECK RESET SESSION
// =====================================================

if (
    $token === ""
    ||
    !isset(
        $_SESSION["password_reset"]
    )
) {

    header(
        "Location: ../../frontend/forgot_password.php?error=" .
            urlencode(
                "Invalid or expired password reset link."
            )
    );

    exit();
}


// =====================================================
// GET RESET DATA
// =====================================================

$resetData =
    $_SESSION["password_reset"];


// =====================================================
// VERIFY TOKEN
// =====================================================

if (
    !isset($resetData["token"])
    ||
    !isset($resetData["expires"])
    ||
    !hash_equals(
        $resetData["token"],
        $token
    )
    ||
    time() >= $resetData["expires"]
) {

    unset(
        $_SESSION["password_reset"]
    );

    header(
        "Location: ../../frontend/forgot_password.php?error=" .
            urlencode(
                "This password reset link is invalid or has expired. Please try again."
            )
    );

    exit();
}


// =====================================================
// CHECK EMPTY PASSWORD
// =====================================================

if (
    $password === ""
    ||
    $confirm_password === ""
) {

    header(
        "Location: ../../frontend/reset_password.php?token=" .
            urlencode($token) .
            "&error=" .
            urlencode(
                "Please enter and confirm your new password."
            )
    );

    exit();
}


// =====================================================
// CHECK PASSWORD MATCH
// =====================================================

if (
    $password !==
    $confirm_password
) {

    header(
        "Location: ../../frontend/reset_password.php?token=" .
            urlencode($token) .
            "&error=" .
            urlencode(
                "Passwords do not match."
            )
    );

    exit();
}


// =====================================================
// PASSWORD REQUIREMENTS
// =====================================================

// Minimum 8 characters

if (
    strlen($password) < 8
) {

    header(
        "Location: ../../frontend/reset_password.php?token=" .
            urlencode($token) .
            "&error=" .
            urlencode(
                "Password must be at least 8 characters."
            )
    );

    exit();
}


// =====================================================
// UPPERCASE
// =====================================================

if (
    !preg_match(
        "/[A-Z]/",
        $password
    )
) {

    header(
        "Location: ../../frontend/reset_password.php?token=" .
            urlencode($token) .
            "&error=" .
            urlencode(
                "Password must contain at least one uppercase letter."
            )
    );

    exit();
}


// =====================================================
// LOWERCASE
// =====================================================

if (
    !preg_match(
        "/[a-z]/",
        $password
    )
) {

    header(
        "Location: ../../frontend/reset_password.php?token=" .
            urlencode($token) .
            "&error=" .
            urlencode(
                "Password must contain at least one lowercase letter."
            )
    );

    exit();
}


// =====================================================
// NUMBER
// =====================================================

if (
    !preg_match(
        "/[0-9]/",
        $password
    )
) {

    header(
        "Location: ../../frontend/reset_password.php?token=" .
            urlencode($token) .
            "&error=" .
            urlencode(
                "Password must contain at least one number."
            )
    );

    exit();
}


// =====================================================
// SPECIAL CHARACTER
// =====================================================

if (
    !preg_match(
        "/[^A-Za-z0-9]/",
        $password
    )
) {

    header(
        "Location: ../../frontend/reset_password.php?token=" .
            urlencode($token) .
            "&error=" .
            urlencode(
                "Password must contain at least one special character."
            )
    );

    exit();
}


// =====================================================
// GET USER ID
// =====================================================

$userID =
    $resetData["user_id"];


// =====================================================
// HASH PASSWORD
// =====================================================

$hashedPassword =
    password_hash(
        $password,
        PASSWORD_DEFAULT
    );


// =====================================================
// UPDATE PASSWORD
// =====================================================

$sql = "

    UPDATE users

    SET Password = ?

    WHERE UserID = ?

";


$stmt =
    $conn->prepare($sql);


// =====================================================
// CHECK PREPARE
// =====================================================

if (!$stmt) {

    header(
        "Location: ../../frontend/reset_password.php?token=" .
            urlencode($token) .
            "&error=" .
            urlencode(
                "Unable to reset your password. Please try again."
            )
    );

    exit();
}


// =====================================================
// BIND
// =====================================================

$stmt->bind_param(
    "si",
    $hashedPassword,
    $userID
);


// =====================================================
// EXECUTE
// =====================================================

if (
    $stmt->execute()
) {


    // =============================================
    // CLEAR RESET TOKEN
    // =============================================

    unset(
        $_SESSION["password_reset"]
    );


    $stmt->close();

    $conn->close();


    // =============================================
    // SUCCESS → LOGIN
    // =============================================

    header(
        "Location: ../../frontend/login.php?success=" .
            urlencode(
                "Your password has been reset successfully. You can now log in with your new password."
            )
    );

    exit();
}


// =====================================================
// FAILED
// =====================================================

$stmt->close();

$conn->close();


header(
    "Location: ../../frontend/reset_password.php?token=" .
        urlencode($token) .
        "&error=" .
        urlencode(
            "Password reset failed. Please try again."
        )
);

exit();
