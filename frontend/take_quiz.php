<?php

session_start();


// =====================================================
// DATABASE CONNECTION
// =====================================================

require_once __DIR__ . "/../backend/php/db_connection.php";


if (!isset($conn) || !$conn) {

    die("Database connection failed.");
}





// =====================================================
// LOGIN CHECK
// =====================================================

if (
    !isset($_SESSION["UserID"]) ||
    !isset($_SESSION["Role"]) ||
    $_SESSION["Role"] !== "User"
) {

    header("Location: login.php");
    exit();
}



$userID = $_SESSION["UserID"];








// =====================================================
// RETAKE QUIZ
// CLEAR OLD RESULT
// =====================================================


if (isset($_GET["retry"])) {


    unset($_SESSION["quiz_result"]);

    unset($_SESSION["quiz_review"]);
}









// =====================================================
// FUNCTION TO SHOW FULL ANSWER TEXT
// ONLY ONE FUNCTION
// =====================================================


function showAnswerText($answer, $question)
{


    switch ($answer) {


        case "A":

            return "A. " .
                $question["OptionA"];



        case "B":

            return "B. " .
                $question["OptionB"];



        case "C":

            return "C. " .
                $question["OptionC"];



        case "D":

            return "D. " .
                $question["OptionD"];



        default:

            return "No Answer";
    }
}









// =====================================================
// LOAD QUESTIONS
// =====================================================


$sql = "

SELECT

QuestionID,
QuestionText,
OptionA,
OptionB,
OptionC,
OptionD,
CorrectAnswer

FROM quiz_questions

ORDER BY QuestionID ASC

";




$result = $conn->query($sql);



if (!$result) {


    die("Quiz loading error: "
        .
        $conn->error);
}





$questions = [];




while ($row = $result->fetch_assoc()) {


    $questions[] = $row;
}




$totalQuestions = count($questions);









// =====================================================
// SUBMIT QUIZ
// =====================================================


if ($_SERVER["REQUEST_METHOD"] === "POST") {



    $score = 0;


    $userAnswers = [];






    foreach ($questions as $question) {



        $questionID =
            $question["QuestionID"];





        $answer =
            $_POST["answer"][$questionID]
            ?? "";





        $userAnswers[$questionID] =
            $answer;







        if (
            $answer ===
            $question["CorrectAnswer"]
        ) {


            $score++;
        }
    }









    $percentage = 0;



    if ($totalQuestions > 0) {


        $percentage =
            ($score / $totalQuestions) * 100;
    }









    // =================================================
    // SAVE RESULT DATABASE
    // =================================================


    $insert = "

    INSERT INTO quiz_results

    (

    UserID,
    Score,
    TotalQuestions,
    Percentage,
    AttemptDate

    )

    VALUES

    (?,?,?,?,NOW())

    ";




    $stmt =
        $conn->prepare($insert);




    if ($stmt) {


        $stmt->bind_param(

            "iiid",

            $userID,

            $score,

            $totalQuestions,

            $percentage

        );



        $stmt->execute();


        $stmt->close();
    }









    // =================================================
    // STORE RECENT SCORE
    // =================================================


    $_SESSION["quiz_result"] = [

        "score" => $score,

        "total" => $totalQuestions,

        "percentage" =>
        number_format(
            $percentage,
            2
        )

    ];









    // =================================================
    // STORE USER ANSWERS
    // =================================================


    $_SESSION["quiz_review"] =
        $userAnswers;
}









// =====================================================
// GET RECENT RESULT
// =====================================================


$recentResult = null;



if (isset($_SESSION["quiz_result"])) {


    $recentResult =
        $_SESSION["quiz_result"];
}









// =====================================================
// GET REVIEW ANSWERS
// =====================================================


$reviewAnswers =
    $_SESSION["quiz_review"]
    ?? [];



?>

<!DOCTYPE html>

<html lang="en">


<head>

    <meta charset="UTF-8">


    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">


    <title>
        Cybersecurity Quiz - PhishGuard AI
    </title>


    <script src="https://cdn.tailwindcss.com"></script>


    <link
        rel="stylesheet"
        href="css/style.css" />


</head>





<body
    class="
bg-[var(--bg)]
text-[var(--text)]
transition
duration-300
">






    <!-- =====================================================
     USER NAVBAR
====================================================== -->


    <header
        class="
flex
justify-between
items-center
px-10
py-5
bg-[var(--nav)]
">


        <!-- LOGO -->


        <a
            href="user_home.php"
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
flex
items-center
gap-6
">


            <a
                href="user_home.php"
                class="nav-link no-underline">
                Home
            </a>



            <a
                href="detection.php"
                class="nav-link no-underline">
                Detection
            </a>




            <a
                href="awareness.php?source=user"
                class="nav-link no-underline">
                Awareness
            </a>





            <a
                href="take_quiz.php"
                class="
text-blue-400
font-semibold
no-underline
">
                Quiz
            </a>





            <a
                href="user_dashboard.php"
                class="nav-link no-underline">
                Dashboard
            </a>





            <a
                href="index.php"
                class="nav-link no-underline">
                Logout
            </a>





            <button

                id="theme-toggle"

                type="button"

                class="
text-2xl
cursor-pointer
bg-transparent
border-none
">

                ☀

            </button>



        </nav>



    </header>









    <!-- =====================================================
     QUIZ SECTION
====================================================== -->


    <section
        class="
max-w-5xl
mx-auto
px-8
py-16
">





        <h1
            class="
text-4xl
font-bold
text-center
text-[var(--secondary)]
mb-4
">

            📝 Cybersecurity Awareness Quiz

        </h1>





        <p
            class="
text-center
text-[var(--muted)]
mb-10
">

            Test your knowledge about phishing and cybersecurity.

        </p>










        <!-- =====================================================
     RECENT SCORE
====================================================== -->


        <?php if ($recentResult): ?>


            <div
                class="
bg-green-500/10
border
border-green-500/20
rounded-xl
p-8
mb-10
text-center
">


                <h2
                    class="
text-2xl
font-bold
text-green-400
">

                    🎉 Quiz Completed!

                </h2>





                <p class="mt-4 text-lg">

                    Score:

                    <b>

                        <?php echo $recentResult["score"]; ?>

                        /

                        <?php echo $recentResult["total"]; ?>

                    </b>

                </p>





                <p class="text-lg">

                    Percentage:

                    <b>

                        <?php echo $recentResult["percentage"]; ?>%

                    </b>

                </p>





                <p
                    class="
mt-3
text-green-400
">

                    ✅ Result saved successfully

                </p>



            </div>



        <?php endif; ?>









        <!-- =====================================================
     QUIZ FORM
     ONLY SHOW BEFORE SUBMIT
====================================================== -->


        <?php if (!$recentResult): ?>



            <form
                method="POST"
                action="">







                <?php foreach ($questions as $index => $question): ?>




                    <div
                        class="
bg-[var(--card)]
rounded-xl
shadow
p-8
mb-8
">







                        <h2
                            class="
text-xl
font-bold
mb-6
text-[var(--secondary)]
">


                            <?php echo $index + 1; ?>.

                            <?php

                            echo htmlspecialchars(
                                $question["QuestionText"]
                            );

                            ?>


                        </h2>









                        <!-- OPTION A -->


                        <label
                            class="
block
mb-4
cursor-pointer
">


                            <input

                                type="radio"

                                name="answer[<?php echo $question['QuestionID']; ?>]"

                                value="A"

                                class="mr-3">


                            A.

                            <?php

                            echo htmlspecialchars(
                                $question["OptionA"]
                            );

                            ?>



                        </label>









                        <!-- OPTION B -->


                        <label
                            class="
block
mb-4
cursor-pointer
">


                            <input

                                type="radio"

                                name="answer[<?php echo $question['QuestionID']; ?>]"

                                value="B"

                                class="mr-3">


                            B.

                            <?php

                            echo htmlspecialchars(
                                $question["OptionB"]
                            );

                            ?>



                        </label>









                        <!-- OPTION C -->


                        <label
                            class="
block
mb-4
cursor-pointer
">


                            <input

                                type="radio"

                                name="answer[<?php echo $question['QuestionID']; ?>]"

                                value="C"

                                class="mr-3">


                            C.

                            <?php

                            echo htmlspecialchars(
                                $question["OptionC"]
                            );

                            ?>



                        </label>









                        <!-- OPTION D -->


                        <label
                            class="
block
mb-4
cursor-pointer
">


                            <input

                                type="radio"

                                name="answer[<?php echo $question['QuestionID']; ?>]"

                                value="D"

                                class="mr-3">


                            D.

                            <?php

                            echo htmlspecialchars(
                                $question["OptionD"]
                            );

                            ?>



                        </label>





                    </div>






                <?php endforeach; ?>









                <!-- BUTTONS -->


                <div
                    class="
flex
justify-center
gap-4
mt-10
">




                    <button

                        type="submit"

                        class="
px-8
py-3
bg-blue-600
text-white
rounded-lg
font-semibold
hover:bg-blue-700
transition
">

                        Submit Quiz

                    </button>







                    <button

                        type="reset"

                        class="
px-8
py-3
bg-gray-500/20
rounded-lg
font-semibold
hover:bg-gray-500/30
transition
">

                        Clear

                    </button>





                </div>





            </form>



        <?php endif; ?>






    </section>

    <!-- =====================================================
     REVIEW ANSWERS
====================================================== -->


    <?php if ($recentResult): ?>


        <section
            class="
max-w-5xl
mx-auto
px-8
pb-10
">


            <div
                class="
bg-[var(--card)]
rounded-xl
shadow
p-8
">



                <h2
                    class="
text-2xl
font-bold
text-[var(--secondary)]
mb-8
">

                    📘 Review Your Answers

                </h2>







                <?php foreach ($questions as $index => $question): ?>



                    <?php


                    $userAnswer =
                        $reviewAnswers[$question["QuestionID"]]
                        ?? "";



                    $correctAnswer =
                        $question["CorrectAnswer"];





                    $userAnswerText =
                        showAnswerText(
                            $userAnswer,
                            $question
                        );



                    $correctAnswerText =
                        showAnswerText(
                            $correctAnswer,
                            $question
                        );





                    $isCorrect =
                        (
                            $userAnswer === $correctAnswer
                        );



                    ?>








                    <div
                        class="
mb-8
p-6
rounded-xl
bg-black/10
border
border-gray-500/20
">







                        <h3
                            class="
text-lg
font-bold
mb-6
">


                            <?php echo $index + 1; ?>.

                            <?php

                            echo htmlspecialchars(
                                $question["QuestionText"]
                            );

                            ?>


                        </h3>









                        <!-- USER ANSWER -->


                        <div
                            class="
mb-5
">


                            <p
                                class="
font-semibold
">

                                Your Answer:

                            </p>




                            <p
                                class="
mt-2
">

                                <?php

                                echo htmlspecialchars(
                                    $userAnswerText
                                );

                                ?>


                            </p>



                        </div>









                        <!-- CORRECT ANSWER -->


                        <div
                            class="
mb-5
">


                            <p
                                class="
font-semibold
">

                                Correct Answer:

                            </p>




                            <p
                                class="
mt-2
">

                                <?php

                                echo htmlspecialchars(
                                    $correctAnswerText
                                );

                                ?>


                            </p>



                        </div>









                        <!-- RESULT -->


                        <?php if ($isCorrect): ?>


                            <p
                                class="
text-green-400
font-bold
text-lg
">

                                ✅ Correct

                            </p>



                        <?php else: ?>


                            <p
                                class="
text-red-400
font-bold
text-lg
">

                                ❌ Incorrect

                            </p>



                        <?php endif; ?>






                    </div>







                <?php endforeach; ?>






            </div>


        </section>



    <?php endif; ?>









    <!-- =====================================================
     TAKE QUIZ AGAIN
====================================================== -->


    <?php if ($recentResult): ?>


        <section
            class="
max-w-5xl
mx-auto
px-8
pb-16
text-center
">


            <div
                class="
bg-[var(--card)]
rounded-xl
shadow
p-8
">



                <h2
                    class="
text-2xl
font-bold
mb-4
">

                    Want to try again?

                </h2>





                <p
                    class="
text-[var(--muted)]
mb-6
">

                    You can take the quiz again to improve your score.

                </p>







                <a
                    href="take_quiz.php?retry=1"
                    class="
inline-block
px-8
py-3
bg-blue-600
text-white
rounded-lg
font-semibold
no-underline
hover:bg-blue-700
transition
">

                    🔄 Take Quiz Again

                </a>






            </div>


        </section>



    <?php endif; ?>

    <!-- =====================================================
     FOOTER
====================================================== -->


    <footer
        class="
bg-[var(--nav)]
mt-20
px-6
py-12
">


        <div
            class="
max-w-7xl
mx-auto
grid
md:grid-cols-4
gap-10
">





            <!-- BRAND -->


            <div>


                <a
                    href="user_home.php"
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
mt-4
text-sm
text-[var(--muted)]
leading-relaxed
">

                    An AI-based phishing URL detection and
                    cybersecurity awareness system that helps
                    users identify online threats and improve
                    digital security.

                </p>


            </div>







            <!-- QUICK LINKS -->


            <div>


                <h3
                    class="
text-lg
font-bold
mb-4
">

                    Quick Links

                </h3>




                <ul
                    class="
space-y-3
text-sm
">


                    <li>

                        <a
                            href="user_home.php"
                            class="nav-link no-underline">

                            Home

                        </a>

                    </li>



                    <li>

                        <a
                            href="detection.php"
                            class="nav-link no-underline">

                            Detection

                        </a>

                    </li>



                    <li>

                        <a
                            href="take_quiz.php"
                            class="nav-link no-underline">

                            Quiz

                        </a>

                    </li>

                </ul>


            </div>









            <!-- SECURITY -->


            <div>


                <h3
                    class="
text-lg
font-bold
mb-4
">

                    Security

                </h3>





                <ul
                    class="
space-y-3
text-sm
">



                    <li>

                        <a
                            href="awareness.php?source=user"
                            class="nav-link no-underline">

                            Phishing Awareness

                        </a>

                    </li>




                    <li>

                        <a
                            href="detection.php"
                            class="nav-link no-underline">

                            Detection

                        </a>

                    </li>




                    <li>

                        <a
                            href="take_quiz.php"
                            class="nav-link no-underline">

                            Security Quiz

                        </a>

                    </li>




                </ul>


            </div>









            <!-- ABOUT -->


            <div>


                <h3
                    class="
text-lg
font-bold
mb-4
">

                    About System

                </h3>




                <p
                    class="
text-sm
text-[var(--muted)]
leading-relaxed
">

                    Powered by AI using Random Forest and CNN models for phishing URL detection and cybersecurity analysis.

                </p>



            </div>





        </div>







        <!-- COPYRIGHT -->


        <div
            class="
max-w-7xl
mx-auto
mt-10
pt-6
border-t
border-gray-500/20
text-center
text-sm
text-[var(--muted)]
">

            © 2026 PhishGuard AI. All rights reserved.

        </div>






    </footer>









    <!-- =====================================================
     BACK TO TOP BUTTON
====================================================== -->


    <button
        id="backToTop"
        class="
fixed
bottom-6
right-6
hidden
bg-blue-600
text-white
w-12
h-12
rounded-full
shadow-lg
text-2xl
font-bold
hover:bg-blue-700
transition
z-50
">

        ↑

    </button>









    <!-- =====================================================
     BACK TO TOP SCRIPT
====================================================== -->


    <script>
        const backToTop =
            document.getElementById("backToTop");



        if (backToTop) {



            window.addEventListener(
                "scroll",
                function() {



                    if (window.scrollY > 300) {


                        backToTop.classList.remove("hidden");


                    } else {


                        backToTop.classList.add("hidden");


                    }



                }

            );







            backToTop.addEventListener(
                "click",
                function() {



                    window.scrollTo({

                        top: 0,

                        behavior: "smooth"

                    });



                }

            );



        }
    </script>






    <!-- MAIN JS -->

    <script src="js/script.js"></script>





</body>

</html>