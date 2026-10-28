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


    <style>
        #adminProfileSidebar {
            z-index: 60;
        }

        #adminProfileOverlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .65);
            z-index: 50;
        }

        #adminProfileMenuButton,
        #adminProfileCloseButton {
            display: none;
        }

        #adminProfileSidebar nav a {
            text-align: left;
            justify-content: flex-start;
        }

        #adminProfileMain {
            min-width: 0;
        }

        #adminProfileCard {
            min-width: 0;
        }

        @media (max-width: 1023px) {
            #adminProfileSidebar {
                transform: translateX(-105%);
                transition: transform .25s ease;
                width: min(18rem, 85vw);
            }

            #adminProfileSidebar.profile-open {
                transform: translateX(0);
            }

            #adminProfileOverlay.profile-open {
                display: block;
            }

            #adminProfileMain {
                margin-left: 0 !important;
                width: 100% !important;
                padding: 26px 20px !important;
            }

            #adminProfileMenuButton {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                flex: 0 0 auto;
                width: 40px;
                height: 40px;
                background: var(--primary, #3b82f6);
                color: white;
                border: 0;
                border-radius: 8px;
                cursor: pointer;
            }

            #adminProfileCloseButton {
                display: inline-flex;
                position: absolute;
                top: 19px;
                right: 13px;
                padding: 8px;
                background: transparent;
                color: var(--text, #fff);
                border: 0;
                font-size: 24px;
                cursor: pointer;
            }

            #adminProfileCard {
                padding: 24px !important;
                margin-top: 28px !important;
            }

            #adminProfileHeading {
                font-size: clamp(22px, 4vw, 32px);
                line-height: 1.2;
            }
        }

        @media (max-width: 480px) {
            #adminProfileMain {
                padding: 20px 14px !important;
            }

            #adminProfileCard {
                padding: 18px !important;
            }

            #adminProfileActions {
                flex-direction: column;
            }

            #adminProfileActions>* {
                width: 100%;
                text-align: center;
            }
        }
    </style>
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

    <div id="adminProfileOverlay" aria-hidden="true"></div>
    <aside id="adminProfileSidebar"

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
        <button type="button" id="adminProfileCloseButton" aria-label="Close navigation menu">×</button>

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

    <main id="adminProfileMain"

        class="

            ml-72

            p-10

            w-[calc(100%-18rem)]

        ">

        <div style="display:flex;align-items:center;gap:12px;min-width:0;">
            <button type="button" id="adminProfileMenuButton" aria-label="Open navigation menu" aria-controls="adminProfileSidebar" aria-expanded="false">☰</button>
            <h1 id="adminProfileHeading"

                class="

                text-4xl

                font-bold

                text-[var(--secondary)]

            ">

                👤 Edit Admin Profile

            </h1>
        </div>

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

        <div id="adminProfileCard"

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

                                z-10

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

                <div id="adminProfileActions"

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

                // Password visibility is handled by the standalone script below.

            }

        );
    </script>

    <!-- =====================================
         PASSWORD SHOW / HIDE
         Same toggle flow as User Edit
    ====================================== -->
    <script>
        (function() {
            const password = document.getElementById("password");
            const passwordToggle = document.getElementById("password-toggle");

            if (!password || !passwordToggle) {
                return;
            }

            passwordToggle.addEventListener("click", function(event) {
                event.preventDefault();

                if (password.type === "password") {
                    password.type = "text";
                    passwordToggle.textContent = "🙈";
                    passwordToggle.setAttribute("aria-label", "Hide password");
                } else {
                    password.type = "password";
                    passwordToggle.textContent = "👁";
                    passwordToggle.setAttribute("aria-label", "Show password");
                }
            });
        })();
    </script>

    <script>
        (function() {
            const sidebar = document.getElementById('adminProfileSidebar');
            const overlay = document.getElementById('adminProfileOverlay');
            const menu = document.getElementById('adminProfileMenuButton');
            const close = document.getElementById('adminProfileCloseButton');
            if (!sidebar || !overlay || !menu || !close) return;

            function setOpen(open) {
                sidebar.classList.toggle('profile-open', open);
                overlay.classList.toggle('profile-open', open);
                menu.setAttribute('aria-expanded', String(open));
                overlay.setAttribute('aria-hidden', String(!open));
                document.body.style.overflow = open ? 'hidden' : '';
            }
            menu.addEventListener('click', () => setOpen(true));
            close.addEventListener('click', () => setOpen(false));
            overlay.addEventListener('click', () => setOpen(false));
            sidebar.querySelectorAll('nav a').forEach(link => link.addEventListener('click', () => setOpen(false)));
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') setOpen(false);
            });
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) setOpen(false);
            });
        })();
    </script>
</body>

</html>