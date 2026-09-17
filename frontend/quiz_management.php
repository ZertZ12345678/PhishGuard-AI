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
    } else {


        $message =
            "Failed to add question.";

        $messageType =
            "error";
    }




    $insertStmt->close();
}







// =====================================
// EDIT QUESTION DATA
// =====================================


$editQuestion = null;



if (
    isset($_GET["edit"])
) {


    $editID =
        $_GET["edit"];




    $editSQL =
        "
    SELECT *
    FROM quiz_questions
    WHERE QuestionID = ?
    ";




    $editStmt =
        $conn->prepare(
            $editSQL
        );



    $editStmt->bind_param(
        "i",
        $editID
    );



    $editStmt->execute();



    $editResult =
        $editStmt->get_result();




    if (
        $editResult->num_rows == 1
    ) {


        $editQuestion =
            $editResult->fetch_assoc();
    }



    $editStmt->close();
}








// =====================================
// UPDATE QUESTION
// =====================================


if (
    isset($_POST["update_question"])
) {


    $questionID =
        $_POST["question_id"];



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






    $updateSQL =

        "
    UPDATE quiz_questions

    SET

    QuestionText = ?,
    OptionA = ?,
    OptionB = ?,
    OptionC = ?,
    OptionD = ?,
    CorrectAnswer = ?

    WHERE QuestionID = ?

    ";





    $updateStmt =
        $conn->prepare(
            $updateSQL
        );




    $updateStmt->bind_param(
        "ssssssi",
        $question,
        $optionA,
        $optionB,
        $optionC,
        $optionD,
        $correctAnswer,
        $questionID
    );





    if (
        $updateStmt->execute()
    ) {


        header(
            "Location: quiz_management.php"
        );

        exit();
    }



    $updateStmt->close();
}








// =====================================
// LOAD QUESTIONS
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
     MOBILE MENU BUTTON
===================================== -->


    <button

        id="menuButton"

        class="
md:hidden
fixed
top-5
left-5
z-50
px-4
py-3
rounded-lg
bg-[var(--primary)]
text-white
shadow-lg
">

        ☰

    </button>









    <!-- =====================================
     ADMIN SIDEBAR
===================================== -->


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
transform
transition-transform
duration-300
-translate-x-full
md:translate-x-0
z-40
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
bg-[var(--primary)]
text-white
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









        <!-- =====================================
     BOTTOM CONTROL
===================================== -->


        <div

            class="
absolute
bottom-8
left-6
right-6
space-y-4
">






            <!-- Theme Toggle -->


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
py-3
rounded-lg
bg-red-500/10
text-red-400
no-underline
hover:bg-red-500/20
transition
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
md:ml-72
min-h-screen
px-5
md:px-10
py-10
">





        <!-- Header -->


        <div

            class="
flex
flex-col
md:flex-row
md:justify-between
md:items-start
gap-5
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






            <!-- Add Question Button -->


            <button

                onclick="
document
.getElementById('questionForm')
.scrollIntoView({
behavior:'smooth'
});
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









        <!-- QUESTION LIST -->


        <div

            class="
mt-10
space-y-6
">


            <?php

            $displayNumber = 1;

            while ($row = $result->fetch_assoc()):

            ?>




                <div

                    class="
bg-[var(--card)]
rounded-2xl
shadow
p-5
md:p-8
">





                    <!-- Question -->


                    <h2

                        class="
text-xl
md:text-2xl
font-bold
text-[var(--secondary)]
">

                        Q<?php echo $displayNumber; ?>.

                        <?php echo $row["QuestionText"]; ?>


                    </h2>








                    <!-- OPTIONS -->


                    <div

                        class="
grid
md:grid-cols-2
gap-4
mt-6
">




                        <div

                            class="
bg-[var(--bg)]
p-4
rounded-xl
">

                            <b>
                                A.
                            </b>

                            <?php echo $row["OptionA"]; ?>


                        </div>





                        <div

                            class="
bg-[var(--bg)]
p-4
rounded-xl
">

                            <b>
                                B.
                            </b>

                            <?php echo $row["OptionB"]; ?>


                        </div>





                        <div

                            class="
bg-[var(--bg)]
p-4
rounded-xl
">

                            <b>
                                C.
                            </b>

                            <?php echo $row["OptionC"]; ?>


                        </div>





                        <div

                            class="
bg-[var(--bg)]
p-4
rounded-xl
">

                            <b>
                                D.
                            </b>

                            <?php echo $row["OptionD"]; ?>


                        </div>






                    </div>







                    <!-- Correct Answer -->


                    <div

                        class="
mt-6
text-green-400
font-semibold
">

                        ✅ Correct Answer:

                        <?php echo $row["CorrectAnswer"]; ?>


                    </div>







                    <!-- ACTION BUTTONS -->


                    <div

                        class="
mt-6
flex
flex-col
sm:flex-row
gap-3
">



                        <a

                            href="
quiz_management.php?edit=<?php echo $row['QuestionID']; ?>
"

                            class="
px-5
py-3
rounded-lg
bg-blue-500/10
text-blue-400
text-center
no-underline
">

                            ✏ Edit

                        </a>







                        <a

                            href="
quiz_management.php?delete=<?php echo $row['QuestionID']; ?>
"

                            onclick="
return confirm('Are you sure you want to delete this question?');
"

                            class="
px-5
py-3
rounded-lg
bg-red-500/10
text-red-400
text-center
no-underline
">

                            🗑 Delete

                        </a>







                    </div>







                </div>





            <?php

                $displayNumber++;

            endwhile;

            ?>






        </div>

        <!-- =====================================
     ADD / EDIT QUESTION FORM
===================================== -->


        <div

            id="questionForm"

            class="
mt-10
w-full
bg-[var(--card)]
border
border-white/10
rounded-2xl
shadow-lg
p-6
md:p-8
">




            <?php if ($editQuestion): ?>


                <h2

                    class="
text-3xl
font-bold
text-[var(--secondary)]
mb-8
">

                    ✏ Edit Question

                </h2>


            <?php else: ?>


                <h2

                    class="
text-3xl
font-bold
text-[var(--secondary)]
mb-8
">

                    ➕ Add New Question

                </h2>


            <?php endif; ?>







            <form

                method="POST">





                <?php if ($editQuestion): ?>


                    <input

                        type="hidden"

                        name="question_id"

                        value="
<?php echo $editQuestion['QuestionID']; ?>
">


                <?php endif; ?>








                <!-- Question Text -->


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

                    rows="3"

                    placeholder="Enter quiz question"

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
"><?php

    echo $editQuestion
        ?
        htmlspecialchars(
            $editQuestion["QuestionText"]
        )
        :
        "";

    ?></textarea>









                <!-- Options Grid -->


                <div

                    class="
grid
md:grid-cols-2
gap-5
">





                    <!-- Option A -->

                    <div>


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

                            value="<?php

                                    echo $editQuestion
                                        ?
                                        htmlspecialchars(
                                            $editQuestion["OptionA"]
                                        )
                                        :
                                        "";

                                    ?>"

                            class="
w-full
px-5
py-4
rounded-xl
bg-[var(--bg)]
text-[var(--text)]
outline-none
focus:ring-2
focus:ring-blue-500
">


                    </div>






                    <!-- Option B -->

                    <div>


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

                            value="<?php

                                    echo $editQuestion
                                        ?
                                        htmlspecialchars(
                                            $editQuestion["OptionB"]
                                        )
                                        :
                                        "";

                                    ?>"

                            class="
w-full
px-5
py-4
rounded-xl
bg-[var(--bg)]
text-[var(--text)]
outline-none
focus:ring-2
focus:ring-blue-500
">


                    </div>







                    <!-- Option C -->

                    <div>


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

                            value="<?php

                                    echo $editQuestion
                                        ?
                                        htmlspecialchars(
                                            $editQuestion["OptionC"]
                                        )
                                        :
                                        "";

                                    ?>"

                            class="
w-full
px-5
py-4
rounded-xl
bg-[var(--bg)]
text-[var(--text)]
outline-none
focus:ring-2
focus:ring-blue-500
">


                    </div>







                    <!-- Option D -->

                    <div>


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

                            value="<?php

                                    echo $editQuestion
                                        ?
                                        htmlspecialchars(
                                            $editQuestion["OptionD"]
                                        )
                                        :
                                        "";

                                    ?>"

                            class="
w-full
px-5
py-4
rounded-xl
bg-[var(--bg)]
text-[var(--text)]
outline-none
focus:ring-2
focus:ring-blue-500
">


                    </div>



                </div>







                <!-- Correct Answer -->


                <label

                    class="
block
mt-6
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
rounded-xl
bg-[var(--bg)]
text-[var(--text)]
outline-none
focus:ring-2
focus:ring-blue-500
">


                    <option value="">

                        Select Answer

                    </option>



                    <option

                        value="A"

                        <?php

                        if (
                            $editQuestion &&
                            $editQuestion["CorrectAnswer"] == "A"
                        ) {

                            echo "selected";
                        }

                        ?>>

                        A

                    </option>





                    <option

                        value="B"

                        <?php

                        if (
                            $editQuestion &&
                            $editQuestion["CorrectAnswer"] == "B"
                        ) {

                            echo "selected";
                        }

                        ?>>

                        B

                    </option>





                    <option

                        value="C"

                        <?php

                        if (
                            $editQuestion &&
                            $editQuestion["CorrectAnswer"] == "C"
                        ) {

                            echo "selected";
                        }

                        ?>>

                        C

                    </option>





                    <option

                        value="D"

                        <?php

                        if (
                            $editQuestion &&
                            $editQuestion["CorrectAnswer"] == "D"
                        ) {

                            echo "selected";
                        }

                        ?>>

                        D

                    </option>



                </select>









                <!-- Submit -->


                <?php if ($editQuestion): ?>


                    <button

                        type="submit"

                        name="update_question"

                        class="
mt-8
w-full
py-4
rounded-xl
bg-blue-600
text-white
font-bold
text-lg
hover:opacity-90
transition
">

                        💾 Update Question

                    </button>




                <?php else: ?>


                    <button

                        type="submit"

                        name="add_question"

                        class="
mt-8
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



                <?php endif; ?>





            </form>





        </div>

        <!-- =====================================
     JAVASCRIPT
===================================== -->


        <script src="js/script.js"></script>





        <script>
            // =====================================
            // MOBILE SIDEBAR TOGGLE
            // =====================================


            const menuButton =
                document.getElementById(
                    "menuButton"
                );


            const adminSidebar =
                document.getElementById(
                    "adminSidebar"
                );



            if (
                menuButton &&
                adminSidebar
            ) {


                menuButton.addEventListener(
                    "click",
                    function() {


                        adminSidebar.classList.toggle(
                            "-translate-x-full"
                        );


                    }
                );


            }







            // =====================================
            // CLOSE SIDEBAR WHEN CLICK OUTSIDE
            // MOBILE ONLY
            // =====================================


            document.addEventListener(
                "click",
                function(event) {


                    if (
                        window.innerWidth < 768
                    ) {


                        if (
                            !adminSidebar.contains(event.target) &&
                            !menuButton.contains(event.target)
                        ) {


                            adminSidebar.classList.add(
                                "-translate-x-full"
                            );


                        }


                    }


                }
            );
        </script>

        <script>
            <?php if ($editQuestion): ?>

                window.onload = function() {


                    const form =
                        document.getElementById(
                            "questionForm"
                        );


                    if (form) {

                        setTimeout(
                            function() {

                                form.scrollIntoView({
                                    behavior: "smooth",
                                    block: "start"
                                });


                            },
                            300
                        );

                    }


                };


            <?php endif; ?>
        </script>



</body>

</html>