<?php

session_start();


// =====================================
// ADMIN ACCESS CHECK
// =====================================

if (
    !isset($_SESSION["Role"]) ||
    $_SESSION["Role"] != "Admin"
) {

    header("Location: login.php");
    exit();
}


include "../backend/php/db_connection.php";


// =====================================
// GET ADMIN ID
// =====================================

$userID = $_SESSION["UserID"];


// =====================================
// UPDATE PROFILE
// =====================================

$message = "";


if (isset($_POST["update_profile"])) {


    $username =
        $_POST["username"];


    $email =
        $_POST["email"];


    if (!empty($_POST["password"])) {


        $password =
            password_hash(
                $_POST["password"],
                PASSWORD_DEFAULT
            );


        $sql = "

        UPDATE users

        SET
        Username=?,
        Email=?,
        Password=?

        WHERE UserID=?

        ";


        $stmt =
            $conn->prepare($sql);


        $stmt->bind_param(
            "sssi",
            $username,
            $email,
            $password,
            $userID
        );
    } else {


        $sql = "

        UPDATE users

        SET
        Username=?,
        Email=?

        WHERE UserID=?

        ";


        $stmt =
            $conn->prepare($sql);


        $stmt->bind_param(
            "ssi",
            $username,
            $email,
            $userID
        );
    }


    $stmt->execute();


    $_SESSION["Username"] =
        $username;


    $message =
        "Profile updated successfully";
}


// =====================================
// GET ADMIN DATA
// =====================================

$sql = "

SELECT *

FROM users

WHERE UserID=?

";


$stmt =
    $conn->prepare($sql);


$stmt->bind_param(
    "i",
    $userID
);


$stmt->execute();


$result =
    $stmt->get_result();


$admin =
    $result->fetch_assoc();

?>



<!DOCTYPE html>

<html lang="en">


<head>


    <meta charset="UTF-8">


    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">


    <title>
        Admin Profile - PhishGuard AI
    </title>



    <script src="https://cdn.tailwindcss.com"></script>


    <link
        rel="stylesheet"
        href="css/style.css">


</head>



<body

    class="
        bg-[var(--bg)]
        text-[var(--text)]
        transition
        duration-300
    ">


    <!-- ===============================
         SIDEBAR
    ================================ -->


    <aside

        class="
            fixed
            left-0
            top-0
            h-screen
            w-72
            bg-[var(--nav)]
            px-6
            py-8
            shadow-xl
        ">


        <a

            href="admin_dashboard.php"

            class="
                text-2xl
                font-bold
                text-[var(--secondary)]
                no-underline
            ">

            🛡 PhishGuard AI

        </a>



        <nav

            class="
                mt-10
                space-y-3
            ">


            <a

                href="admin_dashboard.php"

                class="
                    flex
                    items-center
                    gap-3
                    px-4
                    py-3
                    rounded-lg
                    nav-link
                    no-underline
                ">

                📊 Dashboard

            </a>



            <a

                href="quiz_management.php"

                class="
                    flex
                    items-center
                    gap-3
                    px-4
                    py-3
                    rounded-lg
                    nav-link
                    no-underline
                ">

                📝 Quiz Management

            </a>



            <a

                href="show_user.php"

                class="
                    flex
                    items-center
                    gap-3
                    px-4
                    py-3
                    rounded-lg
                    nav-link
                    no-underline
                ">

                👥 Users

            </a>



            <a

                href="quiz_results.php"

                class="
                    flex
                    items-center
                    gap-3
                    px-4
                    py-3
                    rounded-lg
                    nav-link
                    no-underline
                ">

                🏆 Quiz Results

            </a>



            <a

                href="add_admin.php"

                class="
                    flex
                    items-center
                    gap-3
                    px-4
                    py-3
                    rounded-lg
                    nav-link
                    no-underline
                ">

                👑 Add New Admin

            </a>



            <a

                href="detection_history.php"

                class="
                    flex
                    items-center
                    gap-3
                    px-4
                    py-3
                    rounded-lg
                    nav-link
                    no-underline
                ">

                🔍 Detection History

            </a>



            <a

                href="admin_profile.php"

                class="
                    flex
                    items-center
                    gap-3
                    px-4
                    py-3
                    rounded-lg
                    bg-[var(--primary)]
                    text-white
                    no-underline
                ">

                👤 Edit Profile

            </a>


        </nav>



        <div

            class="
                absolute
                bottom-8
                left-6
                right-6
                space-y-4
            ">


            <!-- ===============================
                 THEME BUTTON
            ================================ -->

            <button

                id="theme-toggle"

                type="button"

                class="
                    w-full
                    flex
                    justify-center
                    items-center
                    gap-3
                    px-4
                    py-3
                    rounded-lg
                    bg-blue-500/10
                    border-none
                    cursor-pointer
                ">

                <span id="theme-icon">
                    ☀
                </span>

                <span id="theme-text">
                    Light Mode
                </span>

            </button>



            <!-- LOGOUT -->

            <a

                href="index.php"

                class="
                    block
                    text-center
                    bg-red-500/10
                    text-red-400
                    py-3
                    rounded-lg
                    no-underline
                ">

                🚪 Logout

            </a>


        </div>


    </aside>



    <!-- ===============================
         MAIN CONTENT
    ================================ -->

    <main

        class="
            ml-72
            p-10
            w-[calc(100%-18rem)]
        ">


        <h1

            class="
                text-4xl
                font-bold
                text-[var(--secondary)]
            ">

            👤 Edit Admin Profile

        </h1>


        <p

            class="
                mt-3
                text-[var(--muted)]
            ">

            Update your account information.

        </p>



        <?php if ($message != ""): ?>

            <div

                class="
                    mt-6
                    bg-green-500/10
                    text-green-400
                    p-4
                    rounded-lg
                ">

                <?= htmlspecialchars($message) ?>

            </div>

        <?php endif; ?>



        <div

            class="
                mt-10
                w-full
                max-w-6xl
                bg-[var(--card)]
                p-10
                rounded-xl
                shadow
            ">


            <form

                method="POST"

                class="
                    space-y-6
                ">


                <!-- USERNAME -->

                <div>

                    <label
                        for="username"
                        class="font-semibold">

                        Username

                    </label>


                    <input

                        type="text"

                        id="username"

                        name="username"

                        value="<?= htmlspecialchars($admin["Username"]); ?>"

                        class="
                            w-full
                            mt-2
                            px-5
                            py-3
                            rounded-lg
                            bg-[var(--bg)]
                            text-[var(--text)]
                            outline-none
                        "

                        required>

                </div>



                <!-- EMAIL -->

                <div>

                    <label
                        for="email"
                        class="font-semibold">

                        Email

                    </label>


                    <input

                        type="email"

                        id="email"

                        name="email"

                        value="<?= htmlspecialchars($admin["Email"]); ?>"

                        class="
                            w-full
                            mt-2
                            px-5
                            py-3
                            rounded-lg
                            bg-[var(--bg)]
                            text-[var(--text)]
                            outline-none
                        "

                        required>

                </div>



                <!-- PASSWORD -->

                <div>

                    <label
                        for="password"
                        class="font-semibold">

                        New Password

                        <span
                            class="
                                text-gray-400
                                text-sm
                            ">

                            (Optional)

                        </span>

                    </label>


                    <div
                        class="
                            relative
                            mt-2
                        ">


                        <input

                            id="password"

                            type="password"

                            name="password"

                            placeholder="Enter new password"

                            class="
                                w-full
                                px-5
                                py-3
                                pr-14
                                rounded-lg
                                bg-[var(--bg)]
                                text-[var(--text)]
                                outline-none
                            ">


                        <button

                            type="button"

                            id="password-toggle"

                            class="
                                absolute
                                right-4
                                top-1/2
                                -translate-y-1/2
                                text-xl
                                cursor-pointer
                                bg-transparent
                                border-none
                            "

                            aria-label="Show password">

                            👁

                        </button>


                    </div>


                </div>



                <!-- BUTTONS -->

                <div
                    class="
                        flex
                        gap-4
                        pt-5
                    ">


                    <button

                        type="submit"

                        name="update_profile"

                        class="
                            px-8
                            py-3
                            bg-blue-600
                            text-white
                            rounded-lg
                            hover:bg-blue-700
                            transition
                        ">

                        Save Changes

                    </button>



                    <a

                        href="admin_dashboard.php"

                        class="
                            px-8
                            py-3
                            bg-gray-600
                            text-white
                            rounded-lg
                            no-underline
                        ">

                        Back

                    </a>


                </div>


            </form>


        </div>


    </main>



    <!-- =====================================
         EXISTING PROJECT JAVASCRIPT
    ====================================== -->

    <script src="js/script.js"></script>



    <!-- =====================================
         ADMIN PROFILE JAVASCRIPT
    ====================================== -->

    <script>
        document.addEventListener(
            "DOMContentLoaded",
            function() {


                // =================================
                // DARK / LIGHT MODE
                // =================================

                const themeToggle =
                    document.getElementById(
                        "theme-toggle"
                    );


                const themeIcon =
                    document.getElementById(
                        "theme-icon"
                    );


                const themeText =
                    document.getElementById(
                        "theme-text"
                    );



                // =================================
                // APPLY SAVED THEME
                // =================================

                const savedTheme =
                    localStorage.getItem(
                        "theme"
                    );


                if (
                    savedTheme === "dark"
                ) {

                    document.body.classList.add(
                        "dark"
                    );

                    themeIcon.textContent =
                        "🌙";

                    themeText.textContent =
                        "Dark Mode";

                } else {

                    document.body.classList.remove(
                        "dark"
                    );

                    themeIcon.textContent =
                        "☀";

                    themeText.textContent =
                        "Light Mode";

                }



                // =================================
                // THEME BUTTON
                // =================================

                if (themeToggle) {

                    themeToggle.addEventListener(
                        "click",
                        function() {


                            document.body.classList.toggle(
                                "dark"
                            );


                            const isDark =
                                document.body.classList.contains(
                                    "dark"
                                );


                            if (isDark) {

                                localStorage.setItem(
                                    "theme",
                                    "dark"
                                );

                                themeIcon.textContent =
                                    "🌙";

                                themeText.textContent =
                                    "Dark Mode";

                            } else {

                                localStorage.setItem(
                                    "theme",
                                    "light"
                                );

                                themeIcon.textContent =
                                    "☀";

                                themeText.textContent =
                                    "Light Mode";

                            }

                        }
                    );

                }



                // =================================
                // PASSWORD SHOW / HIDE
                // =================================

                const password =
                    document.getElementById(
                        "password"
                    );


                const passwordToggle =
                    document.getElementById(
                        "password-toggle"
                    );


                if (
                    password &&
                    passwordToggle
                ) {

                    passwordToggle.addEventListener(
                        "click",
                        function() {


                            if (
                                password.type ===
                                "password"
                            ) {

                                password.type =
                                    "text";

                                passwordToggle.textContent =
                                    "🙈";

                                passwordToggle.setAttribute(
                                    "aria-label",
                                    "Hide password"
                                );

                            } else {

                                password.type =
                                    "password";

                                passwordToggle.textContent =
                                    "👁";

                                passwordToggle.setAttribute(
                                    "aria-label",
                                    "Show password"
                                );

                            }

                        }
                    );

                }

            }

        );
    </script>


</body>

</html>