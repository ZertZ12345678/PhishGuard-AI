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


// =====================================
// ADD NEW QUESTION
// =====================================


if (
    isset($_POST["add_question"])
) {


    $question =
        trim($_POST["question"]);


    $optionA =
        trim($_POST["option_a"]);


    $optionB =
        trim($_POST["option_b"]);


    $optionC =
        trim($_POST["option_c"]);


    $optionD =
        trim($_POST["option_d"]);


    $correctAnswer =
        $_POST["correct_answer"];





    $insertSQL =

        "
    INSERT INTO quiz_questions
    (
        QuestionText,
        OptionA,
        OptionB,
        OptionC,
        OptionD,
        CorrectAnswer
    )

    VALUES
    (
        ?,
        ?,
        ?,
        ?,
        ?,
        ?
    )
    ";



    $insertStmt =
        $conn->prepare(
            $insertSQL
        );



    $insertStmt->bind_param(
        "ssssss",
        $question,
        $optionA,
        $optionB,
        $optionC,
        $optionD,
        $correctAnswer
    );




    if (
        $insertStmt->execute()
    ) {


        $message =
            "Question added successfully.";


        $messageType =
            "success";



        // Refresh question list

        header(
            "Location: quiz_management.php"
        );

        exit();
    } else {


        $message =
            "Failed to add question.";


        $messageType =
            "error";
    }



    $insertStmt->close();
}





// =====================================
// DELETE QUESTION
// =====================================


if (
    isset($_GET["delete"])
) {


    $questionID =
        $_GET["delete"];



    $deleteSQL =
        "
    DELETE FROM quiz_questions
    WHERE QuestionID = ?
    ";



    $deleteStmt =
        $conn->prepare(
            $deleteSQL
        );



    $deleteStmt->bind_param(
        "i",
        $questionID
    );



    if (
        $deleteStmt->execute()
    ) {

        $message =
            "Question deleted successfully.";

        $messageType =
            "success";
    } else {

        $message =
            "Failed to delete question.";

        $messageType =
            "error";
    }



    $deleteStmt->close();
}






// =====================================
// LOAD ALL QUESTIONS
// =====================================


$sql =

    "
SELECT *
FROM quiz_questions
ORDER BY QuestionID DESC
";



$result =
    $conn->query(
        $sql
    );



?>

<!DOCTYPE html>

<html lang="en">


<head>


    <meta charset="UTF-8">


    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">


    <title>
        Quiz Management - PhishGuard AI
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






    <!-- =====================================
     ADMIN SIDEBAR
===================================== -->


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



        <p

            class="
mt-2
text-sm
text-[var(--muted)]
">

            Admin Panel

        </p>







        <!-- Menu -->


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
bg-[var(--primary)]
text-white
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







        <!-- Bottom Controls -->


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

    <!-- =====================================
     MAIN CONTENT
===================================== -->


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
max-w-6xl
mx-auto
">




            <!-- Page Header -->


            <div

                class="
flex
justify-between
items-center
">


                <div>


                    <h1

                        class="
text-4xl
font-bold
text-[var(--secondary)]
">

                        📝 Quiz Management

                    </h1>



                    <p

                        class="
mt-3
text-lg
text-[var(--muted)]
">

                        Manage cybersecurity quiz questions.

                    </p>



                </div>





                <!-- Add Button -->


                <button

                    onclick="
document.getElementById('addQuestionForm').scrollIntoView();
"

                    class="
px-6
py-3
rounded-lg
bg-[var(--primary)]
text-white
font-semibold
hover:opacity-90
transition
">

                    ➕ Add Question

                </button>



            </div>








            <!-- Message -->


            <?php if ($message != ""): ?>


                <div

                    class="
mt-8
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









            <!-- Question List -->


            <div

                class="
mt-10
space-y-6
">



                <?php while ($row = $result->fetch_assoc()): ?>




                    <div

                        class="
bg-[var(--card)]
rounded-xl
shadow
p-8
">




                        <!-- Question -->


                        <h2

                            class="
text-xl
font-bold
text-[var(--secondary)]
">

                            Q<?php echo $row["QuestionID"]; ?>.

                            <?php echo $row["QuestionText"]; ?>


                        </h2>






                        <!-- Options -->


                        <div

                            class="
mt-5
grid
md:grid-cols-2
gap-4
">



                            <div
                                class="
bg-[var(--bg)]
p-4
rounded-lg
">

                                A.

                                <?php echo $row["OptionA"]; ?>


                            </div>



                            <div
                                class="
bg-[var(--bg)]
p-4
rounded-lg
">

                                B.

                                <?php echo $row["OptionB"]; ?>


                            </div>



                            <div
                                class="
bg-[var(--bg)]
p-4
rounded-lg
">

                                C.

                                <?php echo $row["OptionC"]; ?>


                            </div>



                            <div
                                class="
bg-[var(--bg)]
p-4
rounded-lg
">

                                D.

                                <?php echo $row["OptionD"]; ?>


                            </div>



                        </div>







                        <!-- Correct Answer -->


                        <div

                            class="
mt-5
text-green-400
font-semibold
">

                            Correct Answer:

                            <?php echo $row["CorrectAnswer"]; ?>


                        </div>







                        <!-- Action Buttons -->


                        <div

                            class="
mt-6
flex
gap-4
">



                            <a

                                href="edit_question.php?id=<?php echo $row['QuestionID']; ?>"

                                class="
px-5
py-2
rounded-lg
bg-blue-500/10
text-blue-400
no-underline
">

                                ✏ Edit

                            </a>







                            <a

                                href="quiz_management.php?delete=<?php echo $row['QuestionID']; ?>"

                                onclick="
return confirm('Delete this question?');
"

                                class="
px-5
py-2
rounded-lg
bg-red-500/10
text-red-400
no-underline
">

                                🗑 Delete

                            </a>






                        </div>







                    </div>





                <?php endwhile; ?>



            </div>

            <!-- =====================================
     ADD QUESTION FORM
===================================== -->


            <div

                id="addQuestionForm"

                class="
mt-16
max-w-4xl
bg-[var(--card)]
rounded-2xl
shadow-xl
p-10
">


                <h2

                    class="
text-3xl
font-bold
text-[var(--secondary)]
mb-8
">

                    ➕ Add New Question

                </h2>





                <form

                    method="POST">





                    <!-- Question -->


                    <label

                        class="
block
mb-2
font-semibold
">

                        Question

                    </label>



                    <textarea

                        name="question"

                        required

                        placeholder="Enter quiz question"

                        rows="3"

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
"></textarea>








                    <!-- Option A -->


                    <label

                        class="
block
mb-2
font-semibold
">

                        Option A

                    </label>


                    <input

                        type="text"

                        name="option_a"

                        required

                        placeholder="Enter option A"

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







                    <!-- Option B -->


                    <label

                        class="
block
mb-2
font-semibold
">

                        Option B

                    </label>


                    <input

                        type="text"

                        name="option_b"

                        required

                        placeholder="Enter option B"

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








                    <!-- Option C -->


                    <label

                        class="
block
mb-2
font-semibold
">

                        Option C

                    </label>


                    <input

                        type="text"

                        name="option_c"

                        required

                        placeholder="Enter option C"

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








                    <!-- Option D -->


                    <label

                        class="
block
mb-2
font-semibold
">

                        Option D

                    </label>


                    <input

                        type="text"

                        name="option_d"

                        required

                        placeholder="Enter option D"

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








                    <!-- Correct Answer -->


                    <label

                        class="
block
mb-2
font-semibold
">

                        Correct Answer

                    </label>



                    <select

                        name="correct_answer"

                        required

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


                        <option value="">
                            Select Correct Answer
                        </option>


                        <option value="A">
                            Option A
                        </option>


                        <option value="B">
                            Option B
                        </option>


                        <option value="C">
                            Option C
                        </option>


                        <option value="D">
                            Option D
                        </option>


                    </select>







                    <!-- Submit -->


                    <button

                        type="submit"

                        name="add_question"

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

                        ➕ Add Question

                    </button>






                </form>



            </div>