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


    <title>
        Admin Profile - PhishGuard AI
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



            <button

                id="theme-toggle"

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

                ☀ Light Mode

            </button>






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

                <?= $message ?>

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



                <div>

                    <label class="font-semibold">

                        Username

                    </label>


                    <input

                        type="text"

                        name="username"

                        value="<?= htmlspecialchars($admin["Username"]); ?>"

                        class="
w-full
mt-2
px-5
py-3
rounded-lg
bg-[var(--bg)]
outline-none
"

                        required>

                </div>







                <div>

                    <label class="font-semibold">

                        Email

                    </label>


                    <input

                        type="email"

                        name="email"

                        value="<?= htmlspecialchars($admin["Email"]); ?>"

                        class="
w-full
mt-2
px-5
py-3
rounded-lg
bg-[var(--bg)]
outline-none
"

                        required>

                </div>








                <div>

                    <label class="font-semibold">

                        New Password

                        <span class="text-gray-400 text-sm">
                            (Optional)
                        </span>

                    </label>


                    <div class="flex mt-2">


                        <input

                            id="password"

                            type="password"

                            name="password"

                            placeholder="Enter new password"

                            class="
w-full
px-5
py-3
rounded-l-lg
bg-[var(--bg)]
outline-none
">



                        <button

                            type="button"

                            onclick="togglePassword()"

                            class="
px-6
bg-blue-600
text-white
rounded-r-lg
">

                            👁

                        </button>


                    </div>


                </div>







                <div class="flex gap-4 pt-5">


                    <button

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









    <script>
        function togglePassword() {


            let password =
                document.getElementById("password");



            if (password.type === "password") {


                password.type = "text";


            } else {


                password.type = "password";


            }


        }
    </script>







    <script src="js/script.js"></script>




</body>


</html>