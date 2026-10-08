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
        "Location: ../../frontend/forgot_password.php"
    );

    exit();
}


// =====================================================
// GET EMAIL
// =====================================================

$email = trim(
    $_POST["email"] ?? ""
);


// =====================================================
// EMPTY EMAIL
// =====================================================

if ($email === "") {

    header(
        "Location: ../../frontend/forgot_password.php?error=" .
            urlencode(
                "Please enter your email address."
            )
    );

    exit();
}


// =====================================================
// VALIDATE EMAIL
// =====================================================

if (
    !filter_var(
        $email,
        FILTER_VALIDATE_EMAIL
    )
) {

    header(
        "Location: ../../frontend/forgot_password.php?error=" .
            urlencode(
                "Please enter a valid email address."
            )
    );

    exit();
}


// =====================================================
// FIND USER
// =====================================================

$sql = "

    SELECT
        UserID,
        Username,
        Email

    FROM users

    WHERE Email = ?

    LIMIT 1

";


$stmt = $conn->prepare($sql);


if (!$stmt) {

    header(
        "Location: ../../frontend/forgot_password.php?error=" .
            urlencode(
                "Unable to process your request."
            )
    );

    exit();
}


$stmt->bind_param(
    "s",
    $email
);


$stmt->execute();


$result =
    $stmt->get_result();


// =====================================================
// EMAIL NOT FOUND
// =====================================================

if (
    $result->num_rows === 0
) {

    $stmt->close();

    header(
        "Location: ../../frontend/forgot_password.php?error=" .
            urlencode(
                "No account was found with this email address. Please check your email and try again."
            )
    );

    exit();
}


// =====================================================
// GET USER
// =====================================================

$user =
    $result->fetch_assoc();


$stmt->close();


// =====================================================
// GENERATE RESET TOKEN
// =====================================================

$token =
    bin2hex(
        random_bytes(32)
    );


// =====================================================
// TOKEN EXPIRES AFTER 15 MINUTES
// =====================================================

$expires =
    time() + (15 * 60);


// =====================================================
// STORE RESET INFORMATION
// =====================================================

$_SESSION["password_reset"] = [

    "user_id" =>
    $user["UserID"],

    "username" =>
    $user["Username"],

    "email" =>
    $user["Email"],

    "token" =>
    $token,

    "expires" =>
    $expires

];


// =====================================================
// GO TO RESET PASSWORD PAGE
// =====================================================

header(
    "Location: ../../frontend/reset_password.php?token=" .
        urlencode($token)
);

exit();
