<?php

session_start();


// =====================================================
// DATABASE CONNECTION
// =====================================================

require_once __DIR__ . "/../backend/php/db_connection.php";


// =====================================================
// CHECK DATABASE CONNECTION
// =====================================================

if (!isset($conn) || !$conn) {

    die("Database connection failed.");
}


// =====================================================
// USER LOGIN CHECK
// =====================================================

if (
    !isset($_SESSION["UserID"]) ||
    !isset($_SESSION["Role"]) ||
    $_SESSION["Role"] !== "User"
) {

    header("Location: login.php");

    exit();
}


$userID = (int) $_SESSION["UserID"];


// =====================================================
// GET CURRENT USER
// =====================================================

$userSQL = "
    SELECT
        UserID,
        Username,
        Email,
        CreatedDate
    FROM users
    WHERE UserID = ?
    LIMIT 1
";


$userStmt = $conn->prepare($userSQL);


if (!$userStmt) {

    die("User query error: " . $conn->error);
}


$userStmt->bind_param(
    "i",
    $userID
);


$userStmt->execute();


$userResult = $userStmt->get_result();


$user = $userResult->fetch_assoc();


$userStmt->close();


if (!$user) {

    die("User information not found.");
}


// =====================================================
// FORM VARIABLES
// =====================================================

$email = $user["Email"];

$error = "";

$success = "";


// =====================================================
// UPDATE PROFILE
// =====================================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    // Get form values

    $email =
        trim(
            $_POST["email"] ?? ""
        );


    $newPassword =
        $_POST["password"] ?? "";


    $confirmPassword =
        $_POST["confirm_password"] ?? "";


    // =================================================
    // EMAIL VALIDATION
    // =================================================

    if ($email === "") {

        $error =
            "Email is required.";
    } elseif (
        !filter_var(
            $email,
            FILTER_VALIDATE_EMAIL
        )
    ) {

        $error =
            "Please enter a valid email address.";
    }


    // =================================================
    // PASSWORD VALIDATION
    // =================================================

    elseif ($newPassword !== "") {


        if (strlen($newPassword) < 8) {

            $error =
                "Password must contain at least 8 characters.";
        } elseif (
            !preg_match(
                "/[A-Z]/",
                $newPassword
            )
        ) {

            $error =
                "Password must contain at least one uppercase letter.";
        } elseif (
            !preg_match(
                "/[a-z]/",
                $newPassword
            )
        ) {

            $error =
                "Password must contain at least one lowercase letter.";
        } elseif (
            !preg_match(
                "/[0-9]/",
                $newPassword
            )
        ) {

            $error =
                "Password must contain at least one number.";
        } elseif (
            !preg_match(
                "/[@$!%*?&]/",
                $newPassword
            )
        ) {

            $error =
                "Password must contain at least one special character.";
        } elseif (
            $newPassword !==
            $confirmPassword
        ) {

            $error =
                "Passwords do not match.";
        }
    }


    // =================================================
    // CHECK DUPLICATE EMAIL
    // =================================================

    if ($error === "") {


        $emailCheckSQL = "
            SELECT UserID
            FROM users
            WHERE Email = ?
            AND UserID != ?
            LIMIT 1
        ";


        $emailCheckStmt =
            $conn->prepare(
                $emailCheckSQL
            );


        if (!$emailCheckStmt) {

            $error =
                "Email check error: " .
                $conn->error;
        } else {


            $emailCheckStmt->bind_param(
                "si",
                $email,
                $userID
            );


            $emailCheckStmt->execute();


            $emailCheckResult =
                $emailCheckStmt->get_result();


            if (
                $emailCheckResult->num_rows > 0
            ) {

                $error =
                    "This email address is already being used.";
            }


            $emailCheckStmt->close();
        }
    }


    // =================================================
    // UPDATE DATABASE
    // =================================================

    if ($error === "") {


        // =================================================
        // EMAIL + PASSWORD
        // =================================================

        if ($newPassword !== "") {


            $hashedPassword =
                password_hash(
                    $newPassword,
                    PASSWORD_DEFAULT
                );


            $updateSQL = "
                UPDATE users
                SET
                    Email = ?,
                    Password = ?
                WHERE UserID = ?
            ";


            $updateStmt =
                $conn->prepare(
                    $updateSQL
                );


            if (!$updateStmt) {

                $error =
                    "Update error: " .
                    $conn->error;
            } else {


                $updateStmt->bind_param(
                    "ssi",
                    $email,
                    $hashedPassword,
                    $userID
                );


                if (
                    $updateStmt->execute()
                ) {

                    $success =
                        "Your profile has been updated successfully.";

                    // Update session email

                    $_SESSION["Email"] =
                        $email;
                } else {

                    $error =
                        "Unable to update your profile.";
                }


                $updateStmt->close();
            }
        }


        // =================================================
        // EMAIL ONLY
        // =================================================

        else {


            $updateSQL = "
                UPDATE users
                SET Email = ?
                WHERE UserID = ?
            ";


            $updateStmt =
                $conn->prepare(
                    $updateSQL
                );


            if (!$updateStmt) {

                $error =
                    "Update error: " .
                    $conn->error;
            } else {


                $updateStmt->bind_param(
                    "si",
                    $email,
                    $userID
                );


                if (
                    $updateStmt->execute()
                ) {

                    $success =
                        "Your email has been updated successfully.";

                    // Update session email

                    $_SESSION["Email"] =
                        $email;
                } else {

                    $error =
                        "Unable to update your email.";
                }


                $updateStmt->close();
            }
        }


        // Update current user data

        if ($success !== "") {

            $user["Email"] =
                $email;
        }
    }
}

?>


<!DOCTYPE html>

<html lang="en">


<head>

    <meta charset="UTF-8">


    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">


    <title>
        Edit Profile - PhishGuard AI
    </title>


    <!-- Tailwind -->

    <script src="https://cdn.tailwindcss.com"></script>


    <!-- Main CSS -->

    <link
        rel="stylesheet"
        href="css/style.css">

</head>


<body
    class="
        min-h-screen
        bg-[var(--bg)]
        text-[var(--text)]
        transition
        duration-300
    ">


    <!-- =====================================================
     SIDEBAR
====================================================== -->


    <aside
        class="
        fixed
        left-0
        top-0
        h-screen
        w-64
        bg-[var(--nav)]
        border-r
        border-gray-500/20
        z-50
        flex
        flex-col
    ">


        <!-- LOGO -->

        <div class="px-6 py-6">

            <a
                href="user_home.php"
                class="
                text-2xl
                font-bold
                text-[var(--secondary)]
                no-underline
            ">

                🛡 PhishGuard AI

            </a>

        </div>



        <!-- NAVIGATION -->

        <nav class="flex-1 px-4">


            <!-- HOME -->

            <a
                href="user_home.php"
                class="
                flex
                items-center
                gap-3
                px-4
                py-3
                rounded-lg
                nav-link
                no-underline
                mb-2
            ">

                🏠

                <span>
                    Home
                </span>

            </a>



            <!-- DETECTION -->

            <a
                href="detection.php"
                class="
                flex
                items-center
                gap-3
                px-4
                py-3
                rounded-lg
                nav-link
                no-underline
                mb-2
            ">

                🔍

                <span>
                    Detection
                </span>

            </a>



            <!-- AWARENESS -->

            <a
                href="awareness.php?source=user"
                class="
                flex
                items-center
                gap-3
                px-4
                py-3
                rounded-lg
                nav-link
                no-underline
                mb-2
            ">

                🛡️

                <span>
                    Awareness
                </span>

            </a>



            <!-- QUIZ -->

            <a
                href="take_quiz.php"
                class="
                flex
                items-center
                gap-3
                px-4
                py-3
                rounded-lg
                nav-link
                no-underline
                mb-2
            ">

                📝

                <span>
                    Quiz
                </span>

            </a>



            <!-- DASHBOARD -->

            <a
                href="user_dashboard.php"
                class="
                flex
                items-center
                gap-3
                px-4
                py-3
                rounded-lg
                nav-link
                no-underline
                mb-2
            ">

                📊

                <span>
                    Dashboard
                </span>

            </a>



            <!-- EDIT PROFILE -->

            <a
                href="edit_profile.php"
                class="
                flex
                items-center
                gap-3
                px-4
                py-3
                rounded-lg
                bg-blue-600
                text-white
                no-underline
                mb-2
            ">

                ✏️

                <span>
                    Edit Profile
                </span>

            </a>


        </nav>



        <!-- SIDEBAR BOTTOM -->

        <div class="px-4 pb-6">


            <!-- THEME BUTTON -->

            <button
                id="theme-toggle"
                type="button"
                class="
                w-full
                flex
                items-center
                justify-center
                gap-3
                px-4
                py-3
                rounded-lg
                bg-blue-500/10
                text-[var(--text)]
                hover:bg-blue-500/20
                transition
                border-none
                cursor-pointer
                mb-3
            ">

                <span
                    id="theme-icon"
                    class="text-xl">
                    ☀
                </span>

                <span
                    class="font-semibold">
                    Theme
                </span>

            </button>



            <!-- LOGOUT -->

            <a
                href="logout.php"
                class="
                block
                text-center
                bg-red-500/10
                text-red-400
                py-3
                rounded-lg
                no-underline
                hover:bg-red-500/20
                transition
            ">

                🚪 Logout

            </a>


        </div>


    </aside>



    <!-- =====================================================
     MAIN CONTENT
====================================================== -->


    <main
        class="
        ml-64
        min-h-screen
    ">


        <!-- TOP BAR -->

        <header
            class="
            bg-[var(--nav)]
            px-8
            py-5
            border-b
            border-gray-500/20
            flex
            justify-between
            items-center
        ">


            <div>

                <h1
                    class="
                    text-2xl
                    font-bold
                    text-[var(--secondary)]
                ">

                    Edit Profile

                </h1>


                <p
                    class="
                    text-sm
                    text-[var(--muted)]
                    mt-1
                ">

                    Update your account information

                </p>

            </div>



            <!-- USER INFORMATION -->

            <div
                class="
                flex
                items-center
                gap-3
            ">


                <div
                    class="
                    w-10
                    h-10
                    rounded-full
                    bg-blue-600
                    text-white
                    flex
                    items-center
                    justify-center
                    font-bold
                ">

                    <?php

                    echo strtoupper(
                        substr(
                            $user["Username"],
                            0,
                            1
                        )
                    );

                    ?>

                </div>


                <div>

                    <p class="font-semibold">

                        <?php

                        echo htmlspecialchars(
                            $user["Username"]
                        );

                        ?>

                    </p>


                    <p
                        class="
                        text-xs
                        text-[var(--muted)]
                    ">

                        User

                    </p>

                </div>


            </div>


        </header>



        <!-- =====================================================
         PROFILE FORM
    ====================================================== -->


        <section
            class="
            p-8
        ">


            <div
                class="
                max-w-3xl
                mx-auto
                bg-[var(--card)]
                rounded-2xl
                shadow
                p-8
            ">


                <!-- TITLE -->

                <div
                    class="
                    mb-8
                ">

                    <h2
                        class="
                        text-2xl
                        font-bold
                        text-[var(--secondary)]
                    ">

                        Account Information

                    </h2>


                    <p
                        class="
                        mt-2
                        text-[var(--muted)]
                    ">

                        Change your email address or password.

                    </p>

                </div>



                <!-- SUCCESS MESSAGE -->

                <?php if ($success !== ""): ?>

                    <div
                        class="
                        mb-6
                        p-4
                        rounded-lg
                        bg-green-500/10
                        text-green-400
                        border
                        border-green-500/20
                    ">

                        ✅

                        <?php

                        echo htmlspecialchars(
                            $success
                        );

                        ?>

                    </div>

                <?php endif; ?>



                <!-- ERROR MESSAGE -->

                <?php if ($error !== ""): ?>

                    <div
                        class="
                        mb-6
                        p-4
                        rounded-lg
                        bg-red-500/10
                        text-red-400
                        border
                        border-red-500/20
                    ">

                        ⚠️

                        <?php

                        echo htmlspecialchars(
                            $error
                        );

                        ?>

                    </div>

                <?php endif; ?>



                <!-- FORM -->

                <form
                    method="POST"
                    action=""
                    class="space-y-6">


                    <!-- USERNAME -->

                    <div>

                        <label
                            for="username"
                            class="
                            block
                            mb-2
                            font-semibold
                        ">

                            Username

                        </label>


                        <input
                            type="text"
                            id="username"
                            value="<?php
                                    echo htmlspecialchars(
                                        $user["Username"]
                                    );
                                    ?>"
                            disabled
                            class="
                            w-full
                            px-4
                            py-3
                            rounded-lg
                            bg-gray-500/10
                            border
                            border-gray-500/20
                            text-[var(--muted)]
                            cursor-not-allowed
                        ">


                        <p
                            class="
                            mt-2
                            text-xs
                            text-[var(--muted)]
                        ">

                            Username cannot be changed.

                        </p>

                    </div>



                    <!-- EMAIL -->

                    <div>

                        <label
                            for="email"
                            class="
                            block
                            mb-2
                            font-semibold
                        ">

                            Email Address

                        </label>


                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="<?php
                                    echo htmlspecialchars(
                                        $email
                                    );
                                    ?>"
                            required
                            class="
                            w-full
                            px-4
                            py-3
                            rounded-lg
                            bg-[var(--bg)]
                            border
                            border-gray-500/20
                            text-[var(--text)]
                            focus:outline-none
                            focus:ring-2
                            focus:ring-blue-500
                        ">

                    </div>



                    <!-- NEW PASSWORD -->

                    <div>

                        <label
                            for="password"
                            class="
                            block
                            mb-2
                            font-semibold
                        ">

                            New Password

                        </label>


                        <div class="relative">


                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Leave blank to keep current password"
                                class="
                                w-full
                                px-4
                                py-3
                                pr-12
                                rounded-lg
                                bg-[var(--bg)]
                                border
                                border-gray-500/20
                                text-[var(--text)]
                                focus:outline-none
                                focus:ring-2
                                focus:ring-blue-500
                            ">


                            <!-- SHOW / HIDE -->

                            <button
                                type="button"
                                id="toggle-edit-password"
                                aria-label="Show or hide password"
                                class="
                                absolute
                                right-3
                                top-1/2
                                -translate-y-1/2
                                text-xl
                                bg-transparent
                                border-none
                                cursor-pointer
                            ">

                                👁

                            </button>


                        </div>


                        <p
                            class="
                            mt-2
                            text-xs
                            text-[var(--muted)]
                        ">

                            At least 8 characters with uppercase,
                            lowercase, number and special character.

                        </p>

                    </div>



                    <!-- CONFIRM PASSWORD -->

                    <div>

                        <label
                            for="confirm_password"
                            class="
                            block
                            mb-2
                            font-semibold
                        ">

                            Confirm New Password

                        </label>


                        <div class="relative">


                            <input
                                type="password"
                                id="confirm_password"
                                name="confirm_password"
                                placeholder="Confirm your new password"
                                class="
                                w-full
                                px-4
                                py-3
                                pr-12
                                rounded-lg
                                bg-[var(--bg)]
                                border
                                border-gray-500/20
                                text-[var(--text)]
                                focus:outline-none
                                focus:ring-2
                                focus:ring-blue-500
                            ">


                            <!-- SHOW / HIDE -->

                            <button
                                type="button"
                                id="toggle-edit-confirm-password"
                                aria-label="Show or hide confirm password"
                                class="
                                absolute
                                right-3
                                top-1/2
                                -translate-y-1/2
                                text-xl
                                bg-transparent
                                border-none
                                cursor-pointer
                            ">

                                👁

                            </button>


                        </div>

                    </div>



                    <!-- BUTTONS -->

                    <div
                        class="
                        flex
                        gap-4
                        pt-4
                    ">


                        <!-- SAVE -->

                        <button
                            type="submit"
                            class="
                            px-6
                            py-3
                            rounded-lg
                            bg-blue-600
                            text-white
                            font-semibold
                            hover:bg-blue-700
                            transition
                            cursor-pointer
                        ">

                            💾 Save Changes

                        </button>



                        <!-- CANCEL -->

                        <a
                            href="user_dashboard.php"
                            class="
                            px-6
                            py-3
                            rounded-lg
                            bg-gray-500/20
                            text-[var(--text)]
                            font-semibold
                            no-underline
                            hover:bg-gray-500/30
                            transition
                        ">

                            Cancel

                        </a>


                    </div>


                </form>


            </div>


        </section>


    </main>



    <!-- =====================================================
     BACK TO TOP
====================================================== -->


    <button
        id="backToTop"
        type="button"
        aria-label="Back to top"
        class="
        fixed
        bottom-6
        right-6
        hidden
        bg-blue-600
        text-white
        w-12
        h-12
        rounded-full
        shadow-lg
        text-2xl
        font-bold
        hover:bg-blue-700
        transition
        z-50
    ">

        ↑

    </button>



    <!-- =====================================================
     PASSWORD SHOW / HIDE
====================================================== -->


    <script>
        // =====================================================
        // NEW PASSWORD SHOW / HIDE
        // =====================================================

        const editPassword =
            document.getElementById(
                "password"
            );


        const editPasswordToggle =
            document.getElementById(
                "toggle-edit-password"
            );


        if (
            editPassword &&
            editPasswordToggle
        ) {


            editPasswordToggle.addEventListener(
                "click",
                function() {


                    if (
                        editPassword.type ===
                        "password"
                    ) {


                        editPassword.type =
                            "text";


                        editPasswordToggle.textContent =
                            "🙈";


                    } else {


                        editPassword.type =
                            "password";


                        editPasswordToggle.textContent =
                            "👁";

                    }

                }
            );

        }



        // =====================================================
        // CONFIRM PASSWORD SHOW / HIDE
        // =====================================================

        const editConfirmPassword =
            document.getElementById(
                "confirm_password"
            );


        const editConfirmPasswordToggle =
            document.getElementById(
                "toggle-edit-confirm-password"
            );


        if (
            editConfirmPassword &&
            editConfirmPasswordToggle
        ) {


            editConfirmPasswordToggle.addEventListener(
                "click",
                function() {


                    if (
                        editConfirmPassword.type ===
                        "password"
                    ) {


                        editConfirmPassword.type =
                            "text";


                        editConfirmPasswordToggle.textContent =
                            "🙈";


                    } else {


                        editConfirmPassword.type =
                            "password";


                        editConfirmPasswordToggle.textContent =
                            "👁";

                    }

                }
            );

        }


        // =====================================================
        // BACK TO TOP
        // =====================================================

        const backToTop =
            document.getElementById(
                "backToTop"
            );


        if (backToTop) {


            window.addEventListener(
                "scroll",
                function() {


                    if (
                        window.scrollY > 300
                    ) {

                        backToTop.classList.remove(
                            "hidden"
                        );

                    } else {

                        backToTop.classList.add(
                            "hidden"
                        );

                    }

                }
            );


            backToTop.addEventListener(
                "click",
                function() {

                    window.scrollTo({

                        top: 0,

                        behavior: "smooth"

                    });

                }
            );

        }
    </script>



    <!-- =====================================================
     MAIN JAVASCRIPT
====================================================== -->

    <script src="js/script.js"></script>


</body>

</html>