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
// SEARCH
// =====================================

$search = "";


if (isset($_GET["search"])) {

    $search = $_GET["search"];
}




// =====================================
// GET DETECTION HISTORY
// =====================================


if ($search != "") {


    $sql = "

    SELECT

    url_history.*,
    users.Username


    FROM url_history


    LEFT JOIN users

    ON url_history.UserID = users.UserID



    WHERE

    url_history.URL LIKE ?

    OR url_history.PredictionResult LIKE ?

    OR users.Username LIKE ?



    ORDER BY url_history.HistoryID DESC

    ";



    $stmt = $conn->prepare($sql);



    $keyword = "%" . $search . "%";



    $stmt->bind_param(

        "sss",

        $keyword,

        $keyword,

        $keyword

    );



    $stmt->execute();



    $result = $stmt->get_result();
} else {


    $sql = "

    SELECT

    url_history.*,
    users.Username


    FROM url_history


    LEFT JOIN users

    ON url_history.UserID = users.UserID



    ORDER BY url_history.HistoryID DESC

    ";



    $result = $conn->query($sql);
}


?>



<!DOCTYPE html>

<html lang="en">


<head>


    <meta charset="UTF-8">


    <title>
        Detection History - PhishGuard AI
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

                href="add_admin.php"

                class="
block
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
block
px-4
py-3
rounded-lg
bg-[var(--primary)]
text-white
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
py-3
rounded-lg
bg-blue-500/10
border-none
cursor-pointer
">

                ☀ Light Mode

            </button>







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

            🔍 Detection History

        </h1>




        <p

            class="
mt-3
text-[var(--muted)]
">

            View all URL detection activities.

        </p>







        <!-- SEARCH -->


        <form

            method="GET"

            class="
mt-8
flex
gap-3
">


            <input

                type="text"

                name="search"

                value="<?php echo htmlspecialchars($search); ?>"

                placeholder="Search username, URL or result"

                class="
w-96
px-5
py-3
rounded-lg
bg-[var(--card)]
outline-none
">



            <button

                class="
px-6
py-3
bg-blue-600
text-white
rounded-lg
">

                Search

            </button>



        </form>









        <!-- TABLE -->


        <div

            class="
mt-10
bg-[var(--card)]
rounded-xl
shadow
overflow-hidden
">




            <table

                class="
w-full
text-left
">




                <thead

                    class="
bg-black/10
">


                    <tr>


                        <th class="p-5">
                            ID
                        </th>


                        <th class="p-5">
                            Username
                        </th>


                        <th class="p-5">
                            URL
                        </th>


                        <th class="p-5">
                            Prediction
                        </th>


                        <th class="p-5">
                            Confidence
                        </th>


                        <th class="p-5">
                            Checked Date
                        </th>


                    </tr>


                </thead>









                <tbody>


                    <?php while ($row = $result->fetch_assoc()): ?>


                        <tr

                            class="
border-t
border-gray-500/20
">



                            <td class="p-5">

                                <?php echo $row["HistoryID"]; ?>

                            </td>







                            <td class="p-5 font-semibold">

                                <?php

                                echo $row["Username"] ?? "Unknown";

                                ?>

                            </td>







                            <td class="p-5 max-w-md truncate">


                                <?php

                                echo htmlspecialchars(
                                    $row["URL"]
                                );

                                ?>


                            </td>







                            <td class="p-5">


                                <?php


                                if (
                                    $row["PredictionResult"] == "Safe"
                                ) {


                                    echo "

<span class='text-green-400 font-bold'>

✓ Safe

</span>

";
                                } else {


                                    echo "

<span class='text-red-400 font-bold'>

⚠ Phishing

</span>

";
                                }



                                ?>


                            </td>







                            <td class="p-5">

                                <?php

                                echo $row["ConfidenceScore"];

                                ?>%

                            </td>







                            <td class="p-5">

                                <?php

                                echo $row["CheckedDate"];

                                ?>

                            </td>






                        </tr>



                    <?php endwhile; ?>



                </tbody>






            </table>





        </div>






    </main>








    <script src="js/script.js"></script>


</body>


</html>