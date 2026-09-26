<?php

session_start();


// =====================================================
// DATABASE CONNECTION
// =====================================================

require_once __DIR__ . "/../backend/php/db_connection.php";


// Check database connection

if (!isset($conn) || !$conn) {
    die("Database connection failed.");
}


// =====================================================
// USER LOGIN CHECK
// =====================================================

if (
    !isset($_SESSION["UserID"]) ||
    !isset($_SESSION["Role"]) ||
    $_SESSION["Role"] !== "User"
) {
    header("Location: login.php");
    exit();
}


$userID = (int) $_SESSION["UserID"];


// =====================================================
// GET USER INFORMATION
// =====================================================

$userSQL = "
    SELECT
        UserID,
        Username,
        Email,
        CreatedDate
    FROM users
    WHERE UserID = ?
";

$userStmt = $conn->prepare($userSQL);

if (!$userStmt) {
    die("User query error: " . $conn->error);
}

$userStmt->bind_param("i", $userID);
$userStmt->execute();

$userResult = $userStmt->get_result();
$user = $userResult->fetch_assoc();

$userStmt->close();


if (!$user) {
    die("User information not found.");
}


// =====================================================
// TOTAL URL CHECKS
// =====================================================

$totalSQL = "
    SELECT COUNT(*) AS total
    FROM url_history
    WHERE UserID = ?
";

$stmt = $conn->prepare($totalSQL);

if (!$stmt) {
    die("Total URL query error: " . $conn->error);
}

$stmt->bind_param("i", $userID);
$stmt->execute();

$totalResult = $stmt->get_result();
$totalChecks = (int) $totalResult->fetch_assoc()["total"];

$stmt->close();


// =====================================================
// PHISHING COUNT
// =====================================================

$phishingSQL = "
    SELECT COUNT(*) AS total
    FROM url_history
    WHERE UserID = ?
    AND PredictionResult = 'Phishing'
";

$stmt = $conn->prepare($phishingSQL);

if (!$stmt) {
    die("Phishing query error: " . $conn->error);
}

$stmt->bind_param("i", $userID);
$stmt->execute();

$phishingResult = $stmt->get_result();
$phishingCount = (int) $phishingResult->fetch_assoc()["total"];

$stmt->close();


// =====================================================
// SAFE COUNT
// =====================================================

$safeSQL = "
    SELECT COUNT(*) AS total
    FROM url_history
    WHERE UserID = ?
    AND PredictionResult = 'Safe'
";

$stmt = $conn->prepare($safeSQL);

if (!$stmt) {
    die("Safe URL query error: " . $conn->error);
}

$stmt->bind_param("i", $userID);
$stmt->execute();

$safeResult = $stmt->get_result();
$safeCount = (int) $safeResult->fetch_assoc()["total"];

$stmt->close();


// =====================================================
// QUIZ ATTEMPTS
// =====================================================

$quizSQL = "
    SELECT COUNT(*) AS total
    FROM quiz_results
    WHERE UserID = ?
";

$stmt = $conn->prepare($quizSQL);

if (!$stmt) {
    die("Quiz query error: " . $conn->error);
}

$stmt->bind_param("i", $userID);
$stmt->execute();

$quizResult = $stmt->get_result();
$quizAttempts = (int) $quizResult->fetch_assoc()["total"];

$stmt->close();


// =====================================================
// LATEST QUIZ RESULT
// =====================================================

$latestQuizSQL = "
    SELECT
        Score,
        TotalQuestions,
        Percentage,
        AttemptDate
    FROM quiz_results
    WHERE UserID = ?
    ORDER BY AttemptDate DESC
    LIMIT 1
";

$stmt = $conn->prepare($latestQuizSQL);

if (!$stmt) {
    die("Latest quiz query error: " . $conn->error);
}

$stmt->bind_param("i", $userID);
$stmt->execute();

$latestQuizResult = $stmt->get_result()->fetch_assoc();

$stmt->close();


// =====================================================
// RECENT URL HISTORY
// =====================================================

$historySQL = "
    SELECT
        URL,
        PredictionResult,
        ConfidenceScore,
        CheckedDate
    FROM url_history
    WHERE UserID = ?
    ORDER BY CheckedDate DESC
    LIMIT 5
";

$stmt = $conn->prepare($historySQL);

if (!$stmt) {
    die("URL history query error: " . $conn->error);
}

$stmt->bind_param("i", $userID);
$stmt->execute();

$historyResult = $stmt->get_result();

$stmt->close();


// =====================================================
// RECENT QUIZ RESULTS
// =====================================================

$quizHistorySQL = "
    SELECT
        Score,
        TotalQuestions,
        Percentage,
        AttemptDate
    FROM quiz_results
    WHERE UserID = ?
    ORDER BY AttemptDate DESC
    LIMIT 5
";

$stmt = $conn->prepare($quizHistorySQL);

if (!$stmt) {
    die("Quiz history query error: " . $conn->error);
}

$stmt->bind_param("i", $userID);
$stmt->execute();

$quizHistoryResult = $stmt->get_result();

$stmt->close();


// =====================================================
// PROGRESS BAR CALCULATIONS
// =====================================================

$phishingPercentage = 0;
$safePercentage = 0;

if ($totalChecks > 0) {

    $phishingPercentage =
        ($phishingCount / $totalChecks) * 100;

    $safePercentage =
        ($safeCount / $totalChecks) * 100;
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>User Dashboard - PhishGuard AI</title>


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


    <!-- =====================================================
     SIDEBAR
===================================================== -->

    <aside
        class="
    fixed
    left-0
    top-0
    h-screen
    w-64
    bg-[var(--nav)]
    border-r
    border-gray-500/20
    z-50
    flex
    flex-col
    ">


        <!-- LOGO -->

        <div class="px-6 py-6">

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

        </div>


        <!-- MENU -->

        <nav class="flex-1 px-4">


            <!-- HOME -->

            <a
                href="user_home.php"
                class="
            flex
            items-center
            gap-3
            px-4
            py-3
            rounded-lg
            nav-link
            no-underline
            mb-2
            ">

                🏠

                <span>
                    Home
                </span>

            </a>


            <!-- DETECTION -->

            <a
                href="detection.php"
                class="
            flex
            items-center
            gap-3
            px-4
            py-3
            rounded-lg
            nav-link
            no-underline
            mb-2
            ">

                🔍

                <span>
                    Detection
                </span>

            </a>


            <!-- AWARENESS -->

            <a
                href="awareness.php"
                class="
            flex
            items-center
            gap-3
            px-4
            py-3
            rounded-lg
            nav-link
            no-underline
            mb-2
            ">

                🛡️

                <span>
                    Awareness
                </span>

            </a>


            <!-- QUIZ -->

            <a
                href="take_quiz.php"
                class="
            flex
            items-center
            gap-3
            px-4
            py-3
            rounded-lg
            nav-link
            no-underline
            mb-2
            ">

                📝

                <span>
                    Quiz
                </span>

            </a>


            <!-- DASHBOARD -->

            <a
                href="user_dashboard.php"
                class="
            flex
            items-center
            gap-3
            px-4
            py-3
            rounded-lg
            bg-blue-600
            text-white
            no-underline
            mb-2
            ">

                📊

                <span>
                    Dashboard
                </span>

            </a>

            <!-- EDIT PROFILE -->

            <a
                href="edit_profile.php"
                class="
flex
items-center
gap-3
px-4
py-3
rounded-lg
nav-link
no-underline
mb-2
">

                ✏️

                <span>
                    Edit Profile
                </span>

            </a>


        </nav>


        <!-- SIDEBAR BOTTOM -->

        <div class="px-4 pb-6">


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

            <br>





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


    <!-- =====================================================
     MAIN CONTENT
===================================================== -->

    <main class="ml-64 min-h-screen">


        <!-- TOP BAR -->

        <header
            class="
        bg-[var(--nav)]
        px-8
        py-5
        border-b
        border-gray-500/20
        flex
        justify-between
        items-center
        ">


            <div>

                <h1
                    class="
                text-2xl
                font-bold
                text-[var(--secondary)]
                ">

                    User Dashboard

                </h1>


                <p
                    class="
                text-sm
                text-[var(--muted)]
                mt-1
                ">

                    Welcome back,
                    <?php
                    echo htmlspecialchars(
                        $user["Username"]
                    );
                    ?>!

                </p>

            </div>


            <!-- USER -->

            <div
                class="
            flex
            items-center
            gap-3
            ">

                <div
                    class="
                w-10
                h-10
                rounded-full
                bg-blue-600
                text-white
                flex
                items-center
                justify-center
                font-bold
                ">

                    <?php

                    echo strtoupper(
                        substr(
                            $user["Username"],
                            0,
                            1
                        )
                    );

                    ?>

                </div>


                <div>

                    <p class="font-semibold">

                        <?php

                        echo htmlspecialchars(
                            $user["Username"]
                        );

                        ?>

                    </p>


                    <p
                        class="
                    text-xs
                    text-[var(--muted)]
                    ">

                        User

                    </p>

                </div>

            </div>


        </header>


        <!-- =================================================
         DASHBOARD CONTENT
    ================================================== -->

        <section class="p-8">


            <!-- ACCOUNT CARD -->

            <div
                class="
            bg-[var(--card)]
            rounded-xl
            shadow
            p-6
            mb-8
            ">

                <div
                    class="
                flex
                flex-col
                md:flex-row
                md:items-center
                md:justify-between
                gap-4
                ">


                    <div>

                        <p
                            class="
                        text-sm
                        text-[var(--muted)]
                        ">

                            Account

                        </p>


                        <h2
                            class="
                        text-2xl
                        font-bold
                        text-[var(--secondary)]
                        ">

                            <?php

                            echo htmlspecialchars(
                                $user["Username"]
                            );

                            ?>

                        </h2>


                        <p
                            class="
                        text-[var(--muted)]
                        mt-1
                        ">

                            <?php

                            echo htmlspecialchars(
                                $user["Email"]
                            );

                            ?>

                        </p>

                    </div>


                    <div>

                        <p
                            class="
                        text-sm
                        text-[var(--muted)]
                        ">

                            Member since

                        </p>


                        <p class="font-semibold">

                            <?php

                            echo date(
                                "d M Y",
                                strtotime(
                                    $user["CreatedDate"]
                                )
                            );

                            ?>

                        </p>

                    </div>


                </div>

            </div>


            <!-- =================================================
             STATISTICS
        ================================================== -->

            <div
                class="
            grid
            grid-cols-1
            sm:grid-cols-2
            lg:grid-cols-4
            gap-6
            mb-8
            ">


                <!-- TOTAL URL CHECKS -->

                <div
                    class="
                bg-[var(--card)]
                rounded-xl
                shadow
                p-6
                ">

                    <div class="text-3xl mb-3">
                        🔍
                    </div>


                    <p
                        class="
                    text-sm
                    text-[var(--muted)]
                    ">

                        Total URL Checks

                    </p>


                    <h2
                        class="
                    text-3xl
                    font-bold
                    mt-2
                    text-[var(--secondary)]
                    ">

                        <?php echo $totalChecks; ?>

                    </h2>

                </div>


                <!-- PHISHING -->

                <div
                    class="
                bg-[var(--card)]
                rounded-xl
                shadow
                p-6
                ">

                    <div class="text-3xl mb-3">
                        ⚠️
                    </div>


                    <p
                        class="
                    text-sm
                    text-[var(--muted)]
                    ">

                        Phishing Detected

                    </p>


                    <h2
                        class="
                    text-3xl
                    font-bold
                    mt-2
                    text-red-400
                    ">

                        <?php echo $phishingCount; ?>

                    </h2>

                </div>


                <!-- SAFE -->

                <div
                    class="
                bg-[var(--card)]
                rounded-xl
                shadow
                p-6
                ">

                    <div class="text-3xl mb-3">
                        ✅
                    </div>


                    <p
                        class="
                    text-sm
                    text-[var(--muted)]
                    ">

                        Safe URLs

                    </p>


                    <h2
                        class="
                    text-3xl
                    font-bold
                    mt-2
                    text-green-400
                    ">

                        <?php echo $safeCount; ?>

                    </h2>

                </div>


                <!-- QUIZ -->

                <div
                    class="
                bg-[var(--card)]
                rounded-xl
                shadow
                p-6
                ">

                    <div class="text-3xl mb-3">
                        📝
                    </div>


                    <p
                        class="
                    text-sm
                    text-[var(--muted)]
                    ">

                        Quiz Attempts

                    </p>


                    <h2
                        class="
                    text-3xl
                    font-bold
                    mt-2
                    text-[var(--secondary)]
                    ">

                        <?php echo $quizAttempts; ?>

                    </h2>

                </div>


            </div>


            <!-- =================================================
             LATEST QUIZ + SECURITY SUMMARY
        ================================================== -->

            <div
                class="
            grid
            lg:grid-cols-2
            gap-8
            ">


                <!-- LATEST QUIZ RESULT -->

                <div
                    class="
                bg-[var(--card)]
                rounded-xl
                shadow
                p-6
                ">

                    <div
                        class="
                    flex
                    justify-between
                    items-center
                    mb-6
                    ">

                        <h2
                            class="
                        text-xl
                        font-bold
                        text-[var(--secondary)]
                        ">

                            Latest Quiz Result

                        </h2>


                        <a
                            href="take_quiz.php"
                            class="
                        text-sm
                        text-blue-400
                        no-underline
                        ">

                            Take Quiz

                        </a>

                    </div>


                    <?php if ($latestQuizResult): ?>


                        <div
                            class="
                        grid
                        grid-cols-3
                        gap-4
                        text-center
                        ">


                            <div>

                                <p
                                    class="
                                text-sm
                                text-[var(--muted)]
                                ">

                                    Score

                                </p>


                                <p
                                    class="
                                text-2xl
                                font-bold
                                mt-2
                                ">

                                    <?php
                                    echo (int)
                                    $latestQuizResult["Score"];
                                    ?>

                                    /

                                    <?php
                                    echo (int)
                                    $latestQuizResult["TotalQuestions"];
                                    ?>

                                </p>

                            </div>


                            <div>

                                <p
                                    class="
                                text-sm
                                text-[var(--muted)]
                                ">

                                    Percentage

                                </p>


                                <p
                                    class="
                                text-2xl
                                font-bold
                                mt-2
                                text-blue-400
                                ">

                                    <?php

                                    echo number_format(
                                        (float)
                                        $latestQuizResult["Percentage"],
                                        2
                                    );

                                    ?>%

                                </p>

                            </div>


                            <div>

                                <p
                                    class="
                                text-sm
                                text-[var(--muted)]
                                ">

                                    Date

                                </p>


                                <p
                                    class="
                                text-sm
                                font-semibold
                                mt-3
                                ">

                                    <?php

                                    echo date(
                                        "d M Y",
                                        strtotime(
                                            $latestQuizResult["AttemptDate"]
                                        )
                                    );

                                    ?>

                                </p>

                            </div>


                        </div>


                    <?php else: ?>


                        <p
                            class="
                        text-[var(--muted)]
                        text-center
                        py-8
                        ">

                            You have not taken the quiz yet.

                        </p>


                    <?php endif; ?>


                </div>


                <!-- SECURITY SUMMARY -->

                <div
                    class="
                bg-[var(--card)]
                rounded-xl
                shadow
                p-6
                ">

                    <h2
                        class="
                    text-xl
                    font-bold
                    text-[var(--secondary)]
                    mb-6
                    ">

                        Security Summary

                    </h2>


                    <div class="space-y-5">


                        <!-- PHISHING -->

                        <div>

                            <div
                                class="
                            flex
                            justify-between
                            text-sm
                            mb-2
                            ">

                                <span>
                                    Phishing URLs
                                </span>


                                <span>

                                    <?php echo $phishingCount; ?>

                                </span>

                            </div>


                            <div
                                class="
                            w-full
                            h-3
                            bg-gray-700/30
                            rounded-full
                            overflow-hidden
                            ">

                                <div
                                    class="
                                h-full
                                bg-red-500
                                "
                                    style="width: <?php echo $phishingPercentage; ?>%;"></div>

                            </div>

                        </div>


                        <!-- SAFE -->

                        <div>

                            <div
                                class="
                            flex
                            justify-between
                            text-sm
                            mb-2
                            ">

                                <span>
                                    Safe URLs
                                </span>


                                <span>

                                    <?php echo $safeCount; ?>

                                </span>

                            </div>


                            <div
                                class="
                            w-full
                            h-3
                            bg-gray-700/30
                            rounded-full
                            overflow-hidden
                            ">

                                <div
                                    class="
                                h-full
                                bg-green-500
                                "
                                    style="width: <?php echo $safePercentage; ?>%;"></div>

                            </div>

                        </div>


                    </div>

                </div>


            </div>


            <!-- =================================================
             RECENT URL DETECTION
        ================================================== -->

            <div
                class="
            bg-[var(--card)]
            rounded-xl
            shadow
            p-6
            mt-8
            ">


                <div
                    class="
                flex
                justify-between
                items-center
                mb-6
                ">

                    <h2
                        class="
                    text-xl
                    font-bold
                    text-[var(--secondary)]
                    ">

                        Recent URL Detection

                    </h2>


                    <a
                        href="detection.php"
                        class="
                    text-sm
                    text-blue-400
                    no-underline
                    ">

                        New Detection

                    </a>

                </div>


                <div class="overflow-x-auto">


                    <table class="w-full text-sm">


                        <thead>

                            <tr
                                class="
                            border-b
                            border-gray-500/20
                            ">

                                <th
                                    class="
                                text-left
                                py-3
                                ">

                                    URL

                                </th>


                                <th
                                    class="
                                text-left
                                py-3
                                ">

                                    Result

                                </th>


                                <th
                                    class="
                                text-left
                                py-3
                                ">

                                    Confidence

                                </th>


                                <th
                                    class="
                                text-left
                                py-3
                                ">

                                    Date

                                </th>

                            </tr>

                        </thead>


                        <tbody>


                            <?php if ($historyResult->num_rows > 0): ?>


                                <?php while ($history = $historyResult->fetch_assoc()): ?>


                                    <tr
                                        class="
                                border-b
                                border-gray-500/10
                                ">


                                        <td
                                            class="
                                    py-4
                                    max-w-xs
                                    truncate
                                    ">

                                            <?php

                                            echo htmlspecialchars(
                                                $history["URL"]
                                            );

                                            ?>

                                        </td>


                                        <td class="py-4">


                                            <?php

                                            if (
                                                $history["PredictionResult"]
                                                === "Phishing"
                                            ):

                                            ?>


                                                <span
                                                    class="
                                            px-3
                                            py-1
                                            rounded-full
                                            bg-red-500/10
                                            text-red-400
                                            ">

                                                    Phishing

                                                </span>


                                            <?php else: ?>


                                                <span
                                                    class="
                                            px-3
                                            py-1
                                            rounded-full
                                            bg-green-500/10
                                            text-green-400
                                            ">

                                                    Safe

                                                </span>


                                            <?php endif; ?>


                                        </td>


                                        <td class="py-4">

                                            <?php

                                            echo number_format(
                                                (float)
                                                $history["ConfidenceScore"],
                                                2
                                            );

                                            ?>%

                                        </td>


                                        <td class="py-4">

                                            <?php

                                            echo date(
                                                "d M Y H:i",
                                                strtotime(
                                                    $history["CheckedDate"]
                                                )
                                            );

                                            ?>

                                        </td>


                                    </tr>


                                <?php endwhile; ?>


                            <?php else: ?>


                                <tr>

                                    <td
                                        colspan="4"
                                        class="
                                text-center
                                py-8
                                text-[var(--muted)]
                                ">

                                        No URL detection history yet.

                                    </td>

                                </tr>


                            <?php endif; ?>


                        </tbody>


                    </table>


                </div>


            </div>


            <!-- =================================================
             RECENT QUIZ RESULTS
        ================================================== -->

            <div
                class="
            bg-[var(--card)]
            rounded-xl
            shadow
            p-6
            mt-8
            ">


                <h2
                    class="
                text-xl
                font-bold
                text-[var(--secondary)]
                mb-6
                ">

                    Recent Quiz Results

                </h2>


                <div class="overflow-x-auto">


                    <table class="w-full text-sm">


                        <thead>

                            <tr
                                class="
                            border-b
                            border-gray-500/20
                            ">

                                <th
                                    class="
                                text-left
                                py-3
                                ">

                                    Score

                                </th>


                                <th
                                    class="
                                text-left
                                py-3
                                ">

                                    Percentage

                                </th>


                                <th
                                    class="
                                text-left
                                py-3
                                ">

                                    Attempt Date

                                </th>

                            </tr>

                        </thead>


                        <tbody>


                            <?php if ($quizHistoryResult->num_rows > 0): ?>


                                <?php while ($quiz = $quizHistoryResult->fetch_assoc()): ?>


                                    <tr
                                        class="
                                border-b
                                border-gray-500/10
                                ">


                                        <td class="py-4">

                                            <?php
                                            echo (int) $quiz["Score"];
                                            ?>

                                            /

                                            <?php
                                            echo (int)
                                            $quiz["TotalQuestions"];
                                            ?>

                                        </td>


                                        <td class="py-4">

                                            <span
                                                class="
                                        px-3
                                        py-1
                                        rounded-full
                                        bg-blue-500/10
                                        text-blue-400
                                        ">

                                                <?php

                                                echo number_format(
                                                    (float)
                                                    $quiz["Percentage"],
                                                    2
                                                );

                                                ?>%

                                            </span>

                                        </td>


                                        <td class="py-4">

                                            <?php

                                            echo date(
                                                "d M Y H:i",
                                                strtotime(
                                                    $quiz["AttemptDate"]
                                                )
                                            );

                                            ?>

                                        </td>


                                    </tr>


                                <?php endwhile; ?>


                            <?php else: ?>


                                <tr>

                                    <td
                                        colspan="3"
                                        class="
                                text-center
                                py-8
                                text-[var(--muted)]
                                ">

                                        No quiz results yet.

                                    </td>

                                </tr>


                            <?php endif; ?>


                        </tbody>


                    </table>


                </div>


            </div>


        </section>


    </main>


    <!-- =====================================================
     BACK TO TOP BUTTON
===================================================== -->

    <button
        id="backToTop"
        type="button"
        aria-label="Back to top"
        style="
        display: none;
        position: fixed;
        right: 25px;
        bottom: 25px;
        width: 50px;
        height: 50px;
        border: none;
        border-radius: 50%;
        background: #2563eb;
        color: white;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        z-index: 99999;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    ">

        ↑

    </button>


    <!-- =====================================================
     BACK TO TOP SCRIPT
===================================================== -->

    <script>
        (function() {

            const backToTop =
                document.getElementById("backToTop");


            if (!backToTop) {
                return;
            }


            function checkScroll() {

                if (window.scrollY > 200) {

                    backToTop.style.display = "block";

                } else {

                    backToTop.style.display = "none";

                }

            }


            window.addEventListener(
                "scroll",
                checkScroll
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


            checkScroll();

        })();
    </script>


    <!-- EXISTING JAVASCRIPT -->

    <script src="js/script.js"></script>


</body>

</html>