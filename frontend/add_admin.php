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


$message = "";
$messageType = "";



// =====================================
// CREATE ADMIN
// =====================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $username = trim($_POST["username"]);

    $email = trim($_POST["email"]);

    $password = $_POST["password"];



    // Check email

    $check =
        "SELECT * FROM users WHERE Email=?";


    $checkStmt =
        $conn->prepare($check);


    $checkStmt->bind_param(
        "s",
        $email
    );


    $checkStmt->execute();


    $result =
        $checkStmt->get_result();



    if ($result->num_rows > 0) {


        $message =
            "Email already exists.";

        $messageType =
            "error";
    } else {


        $hashedPassword =
            password_hash(
                $password,
                PASSWORD_DEFAULT
            );



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
            'Admin'
        )
        ";



        $stmt =
            $conn->prepare($sql);



        $stmt->bind_param(
            "sss",
            $username,
            $email,
            $hashedPassword
        );



        if ($stmt->execute()) {


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


    $checkStmt->close();
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

    <link rel="stylesheet" href="css/style.css">


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



        <p
            class="
mt-2
text-sm
text-[var(--muted)]
">

            Admin Panel

        </p>




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
                href="user_management.php"

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
                href="reports.php"

                class="
block
px-4
py-3
rounded-lg
nav-link
no-underline
">

                📈 Reports

            </a>



        </nav>






        <!-- Bottom -->

        <div

            class="
absolute
bottom-8
left-6
right-6
space-y-4
">


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

                ☀

                <span>
                    Light Mode
                </span>


            </button>




            <a

                href="index.php"

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







    <!-- ===============================
MAIN CONTENT
================================ -->


    <main

        class="
ml-72
min-h-screen
pl-24
pr-10
py-10
">


        <div

            class="
max-w-5xl
mx-auto
">


            <h1

                class="
text-4xl
font-bold
text-[var(--secondary)]
">

                👑 Add New Admin

            </h1>



            <p

                class="
mt-3
text-lg
text-[var(--muted)]
">

                Create another administrator account for PhishGuard AI.

            </p>






            <!-- FORM -->


            <div

                class="
mt-10
max-w-2xl
ml-10
bg-[var(--card)]
rounded-2xl
shadow-xl
p-10
">




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







                <form method="POST">


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

                        placeholder="Enter username"

                        class="
w-full
px-5
py-4
mb-6
rounded-xl
bg-[var(--bg)]
outline-none
focus:ring-2
focus:ring-blue-500
">





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

                        placeholder="admin@example.com"

                        class="
w-full
px-5
py-4
mb-6
rounded-xl
bg-[var(--bg)]
outline-none
focus:ring-2
focus:ring-blue-500
">





                    <label
                        class="
block
mb-2
font-semibold
">

                        Password

                    </label>


                    <input

                        type="password"

                        name="password"

                        required

                        placeholder="Create password"

                        class="
w-full
px-5
py-4
mb-8
rounded-xl
bg-[var(--bg)]
outline-none
focus:ring-2
focus:ring-blue-500
">






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






    <script src="js/script.js"></script>


</body>

</html>