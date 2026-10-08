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
// SEARCH BY USERNAME
// =====================================

$search = "";


if (isset($_GET["search"])) {

    $search = trim($_GET["search"]);
}



// =====================================
// GET QUIZ RESULTS
// =====================================

if ($search != "") {


    $sql = "

    SELECT

    quiz_results.*,
    users.Username


    FROM quiz_results


    LEFT JOIN users

    ON quiz_results.UserID = users.UserID


    WHERE users.Username LIKE ?


    ORDER BY quiz_results.ResultID DESC

    ";


    $stmt = $conn->prepare($sql);


    $keyword = "%" . $search . "%";


    $stmt->bind_param(

        "s",

        $keyword

    );


    $stmt->execute();


    $result = $stmt->get_result();
} else {


    $sql = "

    SELECT

    quiz_results.*,
    users.Username


    FROM quiz_results


    LEFT JOIN users

    ON quiz_results.UserID = users.UserID


    ORDER BY quiz_results.ResultID DESC

    ";


    $result = $conn->query($sql);
}


?>



<!DOCTYPE html>

<html lang="en">


<head>


    <meta charset="UTF-8">


    <title>
        Quiz Results - PhishGuard AI
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

                href="quiz_results.php"

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

                🏆 Quiz Results

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

            🏆 Quiz Results

        </h1>




        <p

            class="
mt-3
text-[var(--muted)]
">

            View quiz results and performance of registered users.

        </p>







        <!-- ===============================
        SEARCH
        ================================ -->


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

                value="<?php

                        echo htmlspecialchars(
                            $search
                        );

                        ?>"

                placeholder="Search username"

                class="
w-96
px-5
py-3
rounded-lg
bg-[var(--card)]
outline-none
">


            <button

                type="submit"

                class="
px-6
py-3
bg-blue-600
text-white
rounded-lg
">

                Search

            </button>



            <?php

            if ($search != ""):

            ?>

                <a

                    href="quiz_results.php"

                    class="
px-6
py-3
bg-gray-500/20
text-[var(--text)]
rounded-lg
no-underline
flex
items-center
">

                    Clear

                </a>

            <?php endif; ?>



        </form>









        <!-- ===============================
        TABLE
        ================================ -->


        <div

            class="
mt-10
bg-[var(--card)]
rounded-xl
shadow
overflow-x-auto
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
                            Score
                        </th>


                        <th class="p-5">
                            Total Questions
                        </th>


                        <th class="p-5">
                            Percentage
                        </th>


                        <th class="p-5">
                            Attempt Date
                        </th>


                    </tr>


                </thead>









                <tbody>


                    <?php

                    if (
                        $result &&
                        $result->num_rows > 0
                    ):

                        while (
                            $row =
                            $result->fetch_assoc()
                        ):

                    ?>


                            <tr

                                class="
border-t
border-gray-500/20
">


                                <!-- RESULT ID -->

                                <td class="p-5">

                                    <?php

                                    echo $row["ResultID"];

                                    ?>

                                </td>





                                <!-- USERNAME -->

                                <td
                                    class="
p-5
font-semibold
">

                                    <?php

                                    echo htmlspecialchars(
                                        $row["Username"] ?? "Unknown"
                                    );

                                    ?>

                                </td>





                                <!-- SCORE -->

                                <td class="p-5">

                                    <?php

                                    echo $row["Score"];

                                    ?>

                                </td>





                                <!-- TOTAL QUESTIONS -->

                                <td class="p-5">

                                    <?php

                                    echo $row["TotalQuestions"];

                                    ?>

                                </td>





                                <!-- PERCENTAGE -->

                                <td class="p-5">

                                    <?php

                                    echo number_format(
                                        (float)$row["Percentage"],
                                        2
                                    );

                                    ?>%

                                </td>





                                <!-- ATTEMPT DATE -->

                                <td class="p-5">

                                    <?php

                                    echo $row["AttemptDate"];

                                    ?>

                                </td>


                            </tr>


                        <?php

                        endwhile;


                    else:

                        ?>


                        <tr>

                            <td

                                colspan="6"

                                class="
p-8
text-center
text-[var(--muted)]
">

                                <?php

                                if ($search != "") {

                                    echo "No quiz results found for username: ";

                                    echo htmlspecialchars(
                                        $search
                                    );
                                } else {

                                    echo "No quiz results available.";
                                }

                                ?>

                            </td>

                        </tr>


                    <?php

                    endif;

                    ?>


                </tbody>




            </table>


        </div>




    </main>









    <script src="js/script.js"></script>


</body>


</html>