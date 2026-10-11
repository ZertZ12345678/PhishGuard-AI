<?php

session_start();

// =====================================

// DATABASE CONNECTION

// =====================================

include "../backend/php/db_connection.php";

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

$message = "";

$messageType = "";

$username_value = "";

$email_value = "";

// =====================================

// CREATE NEW ADMIN

// =====================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username =

        trim($_POST["username"]);

    $email =

        trim($_POST["email"]);

    // Keep values after validation error

    $username_value =

        htmlspecialchars(

            $username

        );

    $email_value =

        htmlspecialchars(

            $email

        );

    $password =

        $_POST["password"];

    $confirm_password =

        $_POST["confirm_password"];

    // =================================

    // CHECK PASSWORD MATCH

    // =================================

    if (

        $password !== $confirm_password

    ) {

        $message =

            "Passwords do not match.";

        $messageType =

            "error";
    }

    // =================================

    // CHECK EMAIL FORMAT

    // =================================

    elseif (

        !filter_var(

            $email,

            FILTER_VALIDATE_EMAIL

        )

    ) {

        $message =

            "Please enter a valid email address.";

        $messageType =

            "error";
    }

    // =================================

    // CHECK PASSWORD LENGTH

    // =================================

    elseif (

        strlen($password) < 8

    ) {

        $message =

            "Password must contain at least 8 characters.";

        $messageType =

            "error";
    } else {

        // =================================

        // CHECK EXISTING USERNAME / EMAIL

        // =================================

        $check_sql =

            "

        SELECT *

        FROM users

        WHERE Username = ?

        OR Email = ?

        ";

        $check_stmt =

            $conn->prepare(

                $check_sql

            );

        $check_stmt->bind_param(

            "ss",

            $username,

            $email

        );

        $check_stmt->execute();

        $result =

            $check_stmt->get_result();

        if (

            $result->num_rows > 0

        ) {

            $message =

                "Username or email already exists.";

            $messageType =

                "error";
        } else {

            // =================================

            // HASH PASSWORD

            // =================================

            $hashed_password =

                password_hash(

                    $password,

                    PASSWORD_DEFAULT

                );

            // =================================

            // ADMIN ROLE

            // =================================

            $role =

                "Admin";

            // =================================

            // INSERT ADMIN ACCOUNT

            // =================================

            $sql =

                "

            INSERT INTO users

            (

                Username,

                Email,

                Password,

                Role

            )

            VALUES

            (

                ?,

                ?,

                ?,

                ?

            )

            ";

            $stmt =

                $conn->prepare(

                    $sql

                );

            $stmt->bind_param(

                "ssss",

                $username,

                $email,

                $hashed_password,

                $role

            );

            if (

                $stmt->execute()

            ) {

                $message =

                    "Admin account created successfully.";

                $messageType =

                    "success";
            } else {

                $message =

                    "Failed to create Admin.";

                $messageType =

                    "error";
            }

            $stmt->close();
        }

        $check_stmt->close();
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"

        content="width=device-width, initial-scale=1.0">

    <title>

        Add New Admin - PhishGuard AI

    </title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link

        rel="stylesheet"

        href="css/style.css">

    <style>
        @media (max-width:1023px) {
            main {
                margin-left: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
                padding: 28px 20px !important;
                box-sizing: border-box;
            }

            main>div {
                width: 100%;
                max-width: 100%;
                min-width: 0;
            }

            main h1 {
                font-size: clamp(1.5rem, 4.8vw, 2.25rem);
                line-height: 1.25;
            }

            main .max-w-6xl {
                max-width: 720px;
                margin-left: auto;
                margin-right: auto;
                padding: 28px !important;
            }

            #adminMenuButton {
                display: inline-flex !important;
                position: relative;
                z-index: 2;
            }

            #adminSidebar {
                transform: translateX(-105%);
                transition: transform .25s ease;
                z-index: 60;
                overflow-y: auto;
            }

            #adminSidebar.admin-open {
                transform: translateX(0);
            }

            #adminOverlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, .65);
                z-index: 50;
            }

            #adminOverlay.admin-open {
                display: block;
            }

            #adminMenuButton {
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
        }

        @media (max-width:639px) {
            main {
                padding: 22px 16px !important;
            }

            main .max-w-6xl {
                padding: 22px 18px !important;
                margin-top: 24px;
            }

            main p {
                font-size: .95rem;
            }

            main h1 {
                font-size: 1.55rem;
            }
        }

        @media (min-width:1024px) {

            #adminMenuButton,
            #adminCloseButton,
            #adminOverlay {
                display: none !important;
            }
        }

        #adminSidebar nav a {
            text-align: left;
        }

        #adminSidebar nav {
            padding-bottom: 130px;
        }

        #adminSidebar {
            scrollbar-width: thin;
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

    <!-- =====================================

     ADMIN SIDEBAR

\===================================== -->

    <div id="adminOverlay" aria-hidden="true"></div>
    <aside
        id="adminSidebar"

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

        <button type="button" id="adminCloseButton" aria-label="Close menu" class="absolute top-6 right-4 text-[var(--text)] text-2xl bg-transparent border-none cursor-pointer">×</button>
        <!-- Logo -->

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

        <!-- Navigation -->

        <nav

            class="

mt-10

space-y-3

">

            <a

                href="admin_dashboard.php"

                class="

block

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

block

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

block

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

block

px-4

py-3

rounded-lg

bg-[var(--primary)]

text-white

no-underline

">

                👑 Add New Admin

            </a>

            <a

                href="detection_history.php"

                class="

block

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

nav-link

no-underline

">

                👤 Edit Profile

            </a>

        </nav>

        <!-- =====================================

     BOTTOM CONTROLS

\===================================== -->

        <div

            class="

absolute

bottom-8

left-6

right-6

space-y-4

">

            <!-- Theme Button -->

            <button

                id="theme-toggle"

                class="

w-full

flex

items-center

justify-center

gap-3

py-3

rounded-lg

bg-blue-500/10

border-none

cursor-pointer

">

                <span>

                    ☀

                </span>

                <span>

                    Light Mode

                </span>

            </button>

            <!-- Logout -->

            <a

                href="logout.php"

                class="

block

text-center

py-3

rounded-lg

bg-red-500/10

text-red-400

no-underline

">

                🚪 Logout

            </a>

        </div>

    </aside>

    <!-- =====================================

     MAIN CONTENT

\===================================== -->

    <main

        class="

ml-72

min-h-screen

p-10

w-[calc(100%-18rem)]

">

        <div

            class="

w-full

">

            <!-- Page Title -->
            <div class="flex items-center gap-3 min-w-0">
                <button id="adminMenuButton" type="button" aria-label="Open navigation menu" aria-controls="adminSidebar" aria-expanded="false" class="hidden shrink-0 px-3 py-2 rounded-lg bg-[var(--primary)] text-white border-none cursor-pointer shadow">☰</button>

                <h1

                    class="

text-4xl

font-bold

text-[var(--secondary)]

">

                    👑 Add New Admin

                </h1>
            </div>

            <p

                class="

mt-3

text-lg

text-[var(--muted)]

">

                Create another administrator account for PhishGuard AI.

            </p>

            <!-- FORM CARD -->

            <div

                class="

mt-10

w-full

max-w-6xl

bg-[var(--card)]

rounded-2xl

shadow-xl

p-10

">

                <!-- Message Display -->

                <?php if ($message != ""): ?>

                    <div

                        class="

mb-6

p-4

rounded-lg

<?php

                    echo $messageType == "success"

                        ?

                        "bg-green-500/10 text-green-400"

                        :

                        "bg-red-500/10 text-red-400";

?>

">

                        <?php echo $message; ?>

                    </div>

                <?php endif; ?>

                <!-- FORM -->

                <form

                    method="POST">

                    <!-- Username -->

                    <label

                        class="

block

mb-2

font-semibold

">

                        Username

                    </label>

                    <input

                        type="text"

                        name="username"

                        required

                        value="<?php echo $username_value; ?>"

                        placeholder="Enter username"

                        class="

w-full

px-5

py-4

mb-6

rounded-xl

bg-[var(--bg)]

text-[var(--text)]

outline-none

focus:ring-2

focus:ring-blue-500

">

                    <!-- Email -->

                    <label

                        class="

block

mb-2

font-semibold

">

                        Email

                    </label>

                    <input

                        type="email"

                        name="email"

                        required

                        value="<?php echo $email_value; ?>"

                        placeholder="admin@example.com"

                        class="

w-full

px-5

py-4

mb-6

rounded-xl

bg-[var(--bg)]

text-[var(--text)]

outline-none

focus:ring-2

focus:ring-blue-500

">

                    <!-- Password -->

                    <label

                        class="

block

mb-2

font-semibold

">

                        Password

                    </label>

                    <div

                        class="

relative

mb-6

">

                        <input

                            id="admin-password"

                            type="password"

                            name="password"

                            required

                            placeholder="Create password"

                            class="

w-full

px-5

py-4

pr-14

rounded-xl

bg-[var(--bg)]

text-[var(--text)]

outline-none

focus:ring-2

focus:ring-blue-500

">

                        <button

                            type="button"

                            id="toggle-admin-password"

                            class="

absolute

right-4

top-1/2

-translate-y-1/2

text-xl

cursor-pointer

bg-transparent

border-none

">

                            👁

                        </button>

                    </div>

                    <!-- Confirm Password -->

                    <label

                        class="

block

mb-2

font-semibold

">

                        Confirm Password

                    </label>

                    <div

                        class="

relative

mb-8

">

                        <input

                            id="confirm-password"

                            type="password"

                            name="confirm_password"

                            required

                            placeholder="Confirm password"

                            class="

w-full

px-5

py-4

pr-14

rounded-xl

bg-[var(--bg)]

text-[var(--text)]

outline-none

focus:ring-2

focus:ring-blue-500

">

                        <button

                            type="button"

                            id="toggle-confirm-password"

                            class="

absolute

right-4

top-1/2

-translate-y-1/2

text-xl

cursor-pointer

bg-transparent

border-none

">

                            👁

                        </button>

                    </div>

                    <!-- Create Admin Button -->

                    <button

                        type="submit"

                        class="

w-full

py-4

rounded-xl

bg-[var(--primary)]

text-white

font-bold

text-lg

hover:opacity-90

transition

">

                        👑 Create Admin

                    </button>

                </form>

            </div>

        </div>

    </main>

    <!-- =====================================

     JAVASCRIPT

\===================================== -->

    <script src="js/script.js"></script>

    <script>
        // =====================================

        // PASSWORD SHOW / HIDE FUNCTION

        // =====================================

        function setupPasswordToggle(

            inputId,

            buttonId

        ) {

            const input =

                document.getElementById(

                    inputId

                );

            const button =

                document.getElementById(

                    buttonId

                );

            if (

                input &&

                button

            ) {

                button.addEventListener(

                    "click",

                    function() {

                        if (

                            input.type === "password"

                        ) {

                            input.type =

                                "text";

                            button.textContent =

                                "🙈";

                        } else {

                            input.type =

                                "password";

                            button.textContent =

                                "👁";

                        }

                    }

                );

            }

        }

        // Password

        setupPasswordToggle(

            "admin-password",

            "toggle-admin-password"

        );

        // Confirm Password

        setupPasswordToggle(

            "confirm-password",

            "toggle-confirm-password"

        );
    </script>

    <script>
        (function() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('adminOverlay');
            const openButton = document.getElementById('adminMenuButton');
            const closeButton = document.getElementById('adminCloseButton');
            if (!sidebar || !overlay || !openButton || !closeButton) return;

            function setOpen(open) {
                sidebar.classList.toggle('admin-open', open);
                overlay.classList.toggle('admin-open', open);
                openButton.setAttribute('aria-expanded', String(open));
                document.body.style.overflow = open ? 'hidden' : '';
            }
            openButton.addEventListener('click', () => setOpen(true));
            closeButton.addEventListener('click', () => setOpen(false));
            overlay.addEventListener('click', () => setOpen(false));
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') setOpen(false)
            });
            sidebar.querySelectorAll('nav a').forEach(a => a.addEventListener('click', () => setOpen(false)));
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) setOpen(false)
            });
        })();
    </script>
</body>

</html>