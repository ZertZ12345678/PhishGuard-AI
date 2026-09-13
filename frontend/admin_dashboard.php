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
// STATISTICS
// =====================================


// Users

$userQuery =
    "SELECT COUNT(*) AS total FROM users";

$userResult =
    $conn->query($userQuery);

$totalUsers =
    $userResult->fetch_assoc()["total"];


// Quiz

$quizQuery =
    "SELECT COUNT(*) AS total FROM quiz_questions";

$quizResult =
    $conn->query($quizQuery);

$totalQuiz =
    $quizResult->fetch_assoc()["total"];


// Detection

$detectionQuery =
    "SELECT COUNT(*) AS total FROM detection_history";

$detectionResult =
    $conn->query($detectionQuery);

$totalDetection =
    $detectionResult->fetch_assoc()["total"];


?>


<!DOCTYPE html>
<html lang="en">


<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">


    <title>
        Admin Dashboard - PhishGuard AI
    </title>


    <script src="https://cdn.tailwindcss.com"></script>


    <link rel="stylesheet"
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







        <!-- Menu -->

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
bg-[var(--primary)]
text-white
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

                href="user_management.php"

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

                href="reports.php"

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

                📈 Reports

            </a>



        </nav>






        <!-- ===============================
BOTTOM
================================ -->


        <div

            class="
absolute
bottom-8
left-6
right-6
space-y-4
">


            <!-- Light Mode -->

            <button

                id="theme-toggle"

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
">


                <span class="text-xl">
                    ☀
                </span>


                <span class="font-semibold">
                    Light Mode
                </span>


            </button>





            <!-- Logout -->

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
hover:bg-red-500/20
transition
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
">



        <h1

            class="
text-4xl
font-bold
text-[var(--secondary)]
">

            Welcome, Admin 👋

        </h1>



        <p

            class="
mt-3
text-[var(--muted)]
">

            Manage PhishGuard AI system functions from here.

        </p>






        <!-- Statistics -->

        <div

            class="
grid
md:grid-cols-3
gap-8
mt-12
">



            <div

                class="
bg-[var(--card)]
p-8
rounded-xl
shadow
">

                <h2 class="text-xl font-bold">

                    👥 Total Users

                </h2>


                <p

                    class="
text-5xl
font-bold
text-[var(--primary)]
mt-5
">

                    <?php echo $totalUsers; ?>

                </p>


            </div>







            <div

                class="
bg-[var(--card)]
p-8
rounded-xl
shadow
">

                <h2 class="text-xl font-bold">

                    📝 Quiz Questions

                </h2>


                <p

                    class="
text-5xl
font-bold
text-[var(--primary)]
mt-5
">

                    <?php echo $totalQuiz; ?>

                </p>


            </div>







            <div

                class="
bg-[var(--card)]
p-8
rounded-xl
shadow
">

                <h2 class="text-xl font-bold">

                    🔍 Detection Records

                </h2>


                <p

                    class="
text-5xl
font-bold
text-[var(--primary)]
mt-5
">

                    <?php echo $totalDetection; ?>

                </p>


            </div>



        </div>






        <!-- Quick Actions -->

        <h2

            class="
text-2xl
font-bold
text-[var(--secondary)]
mt-14
">

            Quick Management

        </h2>





        <div

            class="
grid
md:grid-cols-3
gap-8
mt-8
">




            <a

                href="quiz_management.php"

                class="
bg-[var(--card)]
p-6
rounded-xl
shadow
no-underline
hover:-translate-y-1
transition
">


                <h3 class="text-xl font-bold">

                    📝 Manage Quiz

                </h3>


                <p class="mt-3 text-[var(--muted)]">

                    Add, edit and delete quiz questions.

                </p>


            </a>






            <a

                href="user_management.php"

                class="
bg-[var(--card)]
p-6
rounded-xl
shadow
no-underline
hover:-translate-y-1
transition
">


                <h3 class="text-xl font-bold">

                    👥 Manage Users

                </h3>


                <p class="mt-3 text-[var(--muted)]">

                    View and manage registered users.

                </p>


            </a>






            <a

                href="add_admin.php"

                class="
bg-[var(--card)]
p-6
rounded-xl
shadow
no-underline
hover:-translate-y-1
transition
">


                <h3 class="text-xl font-bold">

                    👑 Add New Admin

                </h3>


                <p class="mt-3 text-[var(--muted)]">

                    Create another administrator account.

                </p>


            </a>




        </div>



    </main>





    <script src="js/script.js"></script>


</body>

</html>