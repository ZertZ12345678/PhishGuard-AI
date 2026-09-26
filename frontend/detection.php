<?php



// Get URL from Home page if one was passed using GET

$urlFromHome = '';



if (isset($_GET['url'])) {

    $urlFromHome = trim($_GET['url']);
}



// Prevent HTML injection

$safeUrl = htmlspecialchars(

    $urlFromHome,

    ENT_QUOTES,

    'UTF-8'

);



?>



<!DOCTYPE html>

<html lang="en">



<head>



    <meta charset="UTF-8">



    <meta

        name="viewport"

        content="width=device-width, initial-scale=1.0">



    <title>Detection - PhishGuard AI</title>



    <!-- Existing Project CSS -->

    <link rel="stylesheet" href="css/style.css">



    <!-- Tailwind CSS -->

    <script src="https://cdn.tailwindcss.com"></script>



</head>





<body class="bg-[var(--bg)] text-[var(--text)] transition duration-300">





    <!-- =====================================================

     NAVIGATION BAR

     SAME STYLE AS user_home.php

====================================================== -->



    <header class="flex justify-between items-center px-10 py-5 bg-[var(--nav)]">



        <!-- Logo -->



        <a

            href="user_home.php"

            class="text-2xl font-bold text-[var(--secondary)] no-underline">

            🛡 PhishGuard AI

        </a>





        <!-- Navigation -->



        <nav class="flex items-center gap-6">





            <!-- Home -->



            <a

                href="user_home.php"

                class="nav-link">

                Home

            </a>





            <!-- Detection - ACTIVE -->



            <a

                href="detection.php"

                class="text-[var(--primary)] font-semibold no-underline">

                Detection

            </a>





            <!-- Awareness -->



            <a

                href="awareness.php"

                class="nav-link">

                Awareness

            </a>





            <!-- Quiz -->



            <a

                href="take_quiz.php"

                class="nav-link">

                Quiz

            </a>





            <!-- Dashboard -->



            <a

                href="user_dashboard.php"

                class="nav-link">

                Dashboard

            </a>





            <!-- Logout -->



            <a

                href="index.php"

                class="nav-link">

                Logout

            </a>





            <!-- Theme Toggle -->



            <button

                id="theme-toggle"

                class="text-2xl bg-transparent border-none cursor-pointer">

                ☀

            </button>





        </nav>



    </header>







    <!-- =====================================================

     MAIN DETECTION AREA

====================================================== -->



    <main class="max-w-6xl mx-auto px-6 py-16">





        <!-- =================================================

         PAGE HEADING

    ================================================== -->



        <section class="text-center">





            <div

                class="

                inline-flex

                items-center

                gap-2

                px-4

                py-2

                rounded-full

                bg-[var(--card)]

                text-[var(--primary)]

                text-sm

                font-semibold

                mb-6

            ">

                🛡 AI-Powered Security Analysis

            </div>





            <h1

                class="

                text-4xl

                md:text-5xl

                font-bold

                text-[var(--secondary)]

            ">

                Phishing URL Detection

            </h1>





            <p

                class="

                mt-5

                text-lg

                text-[var(--muted)]

                max-w-2xl

                mx-auto

                leading-relaxed

            ">

                Enter a suspicious website URL below.

                PhishGuard AI will analyze its characteristics

                and estimate the potential phishing risk.

            </p>





        </section>







        <!-- =================================================

         DETECTION CARD

    ================================================== -->



        <section

            class="

            max-w-4xl

            mx-auto

            mt-12

            bg-[var(--card)]

            rounded-2xl

            shadow-lg

            p-6

            md:p-8

        ">





            <!-- URL FORM -->



            <form

                id="urlForm"

                class="

                flex

                flex-col

                md:flex-row

                gap-4

            ">





                <!-- Input -->



                <div class="relative flex-1">





                    <span

                        class="

                        absolute

                        left-4

                        top-1/2

                        -translate-y-1/2

                        text-[var(--muted)]

                    ">

                        🔗

                    </span>





                    <input

                        type="text"

                        id="urlInput"

                        name="url"

                        value="<?php echo $safeUrl; ?>"

                        placeholder="Enter URL e.g. https://example.com"

                        autocomplete="off"

                        required



                        class="

                        w-full

                        bg-[var(--bg)]

                        text-[var(--text)]

                        placeholder:text-[var(--muted)]

                        rounded-xl

                        py-4

                        pl-12

                        pr-4

                        outline-none

                        border

                        border-transparent

                        focus:border-[var(--primary)]

                        transition

                    ">





                </div>







                <!-- Analyze Button -->



                <button

                    type="submit"

                    id="analyzeButton"



                    class="

                    px-8

                    py-4

                    bg-[var(--primary)]

                    text-white

                    font-semibold

                    rounded-xl

                    cursor-pointer

                    hover:opacity-90

                    transition

                    min-w-[165px]

                    flex

                    items-center

                    justify-center

                    gap-2

                ">

                    Analyze URL

                </button>





            </form>







            <!-- =================================================

             ERROR MESSAGE

        ================================================== -->



            <div

                id="errorMessage"



                class="

                hidden

                mt-5

                p-4

                rounded-xl

                bg-red-500/10

                text-red-500

                text-sm

            ">

            </div>







            <!-- =================================================

 LOADING WITH PERCENTAGE

================================================== -->





            <div



                id="loadingBox"



                class="

hidden

py-10

text-center

">





                <div



                    class="

relative

w-16

h-16

mx-auto

">





                    <!-- spinning circle -->



                    <div



                        class="

absolute

inset-0

rounded-full

border-4

border-gray-500/20

border-t-[var(--primary)]

animate-spin

">



                    </div>









                    <!-- percentage inside circle -->



                    <div



                        id="loadingPercent"



                        class="

absolute

inset-0

flex

items-center

justify-center

text-sm

font-bold

text-[var(--primary)]

">



                        0%



                    </div>







                </div>











                <p



                    class="

mt-5

text-[var(--muted)]

">



                    Analyzing URL...



                </p>







            </div>







            <!-- =================================================

             RESULT BOX

        ================================================== -->



            <div

                id="resultBox"



                class="

                hidden

                mt-8

                bg-[var(--bg)]

                rounded-2xl

                p-6

                md:p-8

            ">





                <!-- Result Header -->



                <div

                    class="

                    flex

                    flex-col

                    sm:flex-row

                    sm:items-center

                    sm:justify-between

                    gap-4

                ">





                    <div class="flex items-center gap-4">





                        <!-- Result Icon -->



                        <div

                            id="resultIcon"



                            class="

                            w-14

                            h-14

                            rounded-full

                            flex

                            items-center

                            justify-center

                            text-2xl

                            font-bold

                        ">

                            ?

                        </div>







                        <div>





                            <p

                                class="

                                text-sm

                                text-[var(--muted)]

                            ">

                                Analysis Result

                            </p>





                            <h2

                                id="resultTitle"



                                class="

                                text-2xl

                                font-bold

                                mt-1

                            ">

                            </h2>





                        </div>





                    </div>







                    <!-- Risk Badge -->



                    <div

                        id="statusBadge"



                        class="

                        px-4

                        py-2

                        rounded-full

                        text-sm

                        font-bold

                        w-fit

                    ">

                    </div>





                </div>







                <!-- =============================================

                 ANALYZED URL

            ============================================== -->



                <div

                    class="

                    mt-7

                    p-4

                    bg-[var(--card)]

                    rounded-xl

                ">





                    <p

                        class="

                        text-xs

                        uppercase

                        tracking-wide

                        text-[var(--muted)]

                    ">

                        Analyzed URL

                    </p>





                    <p

                        id="analyzedUrl"



                        class="

                        mt-2

                        text-sm

                        font-medium

                        break-all

                        text-[var(--text)]

                    ">

                    </p>





                </div>







                <!-- =============================================

                 RISK SCORE

            ============================================== -->



                <div class="mt-7">





                    <div

                        class="

                        flex

                        justify-between

                        items-center

                    ">





                        <span

                            class="

                            text-sm

                            text-[var(--muted)]

                        ">

                            Risk Score

                        </span>





                        <span

                            id="riskScore"



                            class="

                            font-bold

                            text-lg

                        ">

                            0 / 100

                        </span>





                    </div>







                    <!-- Risk Progress Background -->



                    <div

                        class="

                        mt-3

                        w-full

                        h-3

                        bg-gray-500/20

                        rounded-full

                        overflow-hidden

                    ">





                        <!-- Risk Bar -->



                        <div

                            id="riskBar"



                            class="

                            h-full

                            rounded-full

                            transition-all

                            duration-700

                        "



                            style="width: 0%;">

                        </div>





                    </div>





                </div>







                <!-- =============================================
                     AI MODEL RESULTS
                ============================================== -->

                <div class="mt-7">

                    <h3 class="text-lg font-bold text-[var(--secondary)]">
                        AI Model Results
                    </h3>

                    <div class="grid md:grid-cols-2 gap-5 mt-4">

                        <!-- CNN -->
                        <div class="p-5 rounded-xl bg-[var(--card)] border border-white/5">

                            <div class="flex items-center justify-between gap-3">

                                <div class="flex items-center gap-3">

                                    <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center text-xl">
                                        🧠
                                    </div>

                                    <div>
                                        <p class="font-bold text-[var(--text)]">
                                            CNN
                                        </p>

                                        <p class="text-xs text-[var(--muted)]">
                                            Deep Learning Model
                                        </p>
                                    </div>

                                </div>

                                <span
                                    id="cnnStatus"
                                    class="px-3 py-1 rounded-full text-xs font-bold">
                                    —
                                </span>

                            </div>

                            <div class="mt-5 grid grid-cols-2 gap-4">

                                <div>
                                    <p class="text-xs text-[var(--muted)]">
                                        Prediction
                                    </p>

                                    <p
                                        id="cnnPrediction"
                                        class="mt-1 font-semibold text-[var(--text)]">
                                        —
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs text-[var(--muted)]">
                                        Confidence
                                    </p>

                                    <p
                                        id="cnnConfidence"
                                        class="mt-1 font-semibold text-[var(--text)]">
                                        —
                                    </p>
                                </div>

                            </div>

                        </div>


                        <!-- RANDOM FOREST -->
                        <div class="p-5 rounded-xl bg-[var(--card)] border border-white/5">

                            <div class="flex items-center justify-between gap-3">

                                <div class="flex items-center gap-3">

                                    <div class="w-10 h-10 rounded-lg bg-green-500/10 flex items-center justify-center text-xl">
                                        🌲
                                    </div>

                                    <div>
                                        <p class="font-bold text-[var(--text)]">
                                            Random Forest
                                        </p>

                                        <p class="text-xs text-[var(--muted)]">
                                            Machine Learning Model
                                        </p>
                                    </div>

                                </div>

                                <span
                                    id="rfStatus"
                                    class="px-3 py-1 rounded-full text-xs font-bold">
                                    —
                                </span>

                            </div>

                            <div class="mt-5 grid grid-cols-2 gap-4">

                                <div>
                                    <p class="text-xs text-[var(--muted)]">
                                        Prediction
                                    </p>

                                    <p
                                        id="rfPrediction"
                                        class="mt-1 font-semibold text-[var(--text)]">
                                        —
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs text-[var(--muted)]">
                                        Confidence
                                    </p>

                                    <p
                                        id="rfConfidence"
                                        class="mt-1 font-semibold text-[var(--text)]">
                                        —
                                    </p>
                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- MODEL AGREEMENT -->
                    <div
                        id="modelAgreement"
                        class="mt-5 p-4 rounded-xl bg-blue-500/10 text-[var(--text)] text-sm leading-relaxed">
                    </div>

                </div>


                <!-- =============================================
                     RESULT MESSAGE
                ============================================== -->

                <div
                    id="resultMessage"
                    class="mt-7 text-[var(--text)] leading-relaxed">
                </div>


                <!-- =============================================
                     DETECTION DETAILS
                ============================================== -->

                <div class="mt-7">

                    <h3 class="text-lg font-bold text-[var(--secondary)]">
                        Detection Details
                    </h3>

                    <ul
                        id="reasonList"
                        class="mt-4 space-y-3">
                    </ul>

                </div>


                <!-- =============================================
                     SECURITY RECOMMENDATION
                ============================================== -->

                <div
                    class="mt-7 p-4 rounded-xl bg-[var(--card)] text-[var(--text)] text-sm leading-relaxed">

                    <span class="font-bold text-[var(--secondary)]">
                        Security Recommendation:
                    </span>

                    <span id="securityRecommendation">
                        Always verify the website domain before entering sensitive information.
                    </span>

                </div>


                <!-- =============================================
                     WARNING / DISCLAIMER
                ============================================== -->

                <div
                    class="mt-8
                    p-4
                    rounded-xl
                    bg-blue-500/10
                    text-[var(--text)]
                    text-sm
                    leading-relaxed">

                    <span
                        class="font-bold
                        text-[var(--primary)]">
                        Security Note:
                    </span>

                    This result is a risk assessment.
                    A low-risk result does not guarantee that a website
                    is completely safe. Always check the domain carefully
                    before entering passwords, banking information or
                    personal details.

                </div>







            </div>





        </section>







        <!-- =================================================

         INFORMATION SECTION

    ================================================== -->



        <section class="max-w-4xl mx-auto mt-10">





            <h2

                class="

                text-2xl

                font-bold

                text-[var(--secondary)]

                text-center

            ">

                What Does PhishGuard Check?

            </h2>







            <div

                class="

                grid

                md:grid-cols-3

                gap-6

                mt-8

            ">





                <!-- Card 1 -->



                <div

                    class="

                    bg-[var(--card)]

                    p-6

                    rounded-xl

                    shadow

                ">





                    <div class="text-3xl">

                        🔒

                    </div>





                    <h3

                        class="

                        mt-4

                        font-bold

                        text-[var(--secondary)]

                    ">

                        HTTPS Security

                    </h3>





                    <p

                        class="

                        mt-3

                        text-sm

                        text-[var(--muted)]

                        leading-relaxed

                    ">

                        Checks whether the website uses HTTPS

                        encryption for secure communication.

                    </p>





                </div>







                <!-- Card 2 -->



                <div

                    class="

                    bg-[var(--card)]

                    p-6

                    rounded-xl

                    shadow

                ">





                    <div class="text-3xl">

                        🌐

                    </div>





                    <h3

                        class="

                        mt-4

                        font-bold

                        text-[var(--secondary)]

                    ">

                        Domain Analysis

                    </h3>





                    <p

                        class="

                        mt-3

                        text-sm

                        text-[var(--muted)]

                        leading-relaxed

                    ">

                        Examines IP addresses, subdomains,

                        URL length and suspicious domain structures.

                    </p>





                </div>







                <!-- Card 3 -->



                <div

                    class="

                    bg-[var(--card)]

                    p-6

                    rounded-xl

                    shadow

                ">





                    <div class="text-3xl">

                        ⚠

                    </div>





                    <h3

                        class="

                        mt-4

                        font-bold

                        text-[var(--secondary)]

                    ">

                        Suspicious Patterns

                    </h3>





                    <p

                        class="

                        mt-3

                        text-sm

                        text-[var(--muted)]

                        leading-relaxed

                    ">

                        Detects suspicious keywords, unusual URL

                        characters and common phishing patterns.

                    </p>





                </div>





            </div>





        </section>





    </main>



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





            <!-- Brand -->



            <div>



                <a

                    href="index.php"

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

                    text-[var(--muted)]

                    text-sm

                    leading-relaxed

                ">

                    An AI-based phishing URL detection and

                    cybersecurity awareness system that helps

                    users identify online threats and improve

                    digital security.

                </p>





            </div>







            <!-- Quick Links -->



            <div>





                <h3

                    class="

                    text-lg

                    font-bold

                    text-[var(--text)]

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

                            href="login.php"

                            class="nav-link no-underline">

                            Login

                        </a>

                    </li>





                    <li>

                        <a

                            href="register.php"

                            class="nav-link no-underline">

                            Register

                        </a>

                    </li>









                </ul>





            </div>









            <!-- Security -->



            <div>





                <h3

                    class="

                    text-lg

                    font-bold

                    text-[var(--text)]

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

                            href="awareness.php"

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









            <!-- Contact -->



            <div>





                <h3

                    class="

                    text-lg

                    font-bold

                    text-[var(--text)]

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









        <!-- Bottom Copyright -->



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



            © 2026 PhishGuard AI.

            All rights reserved.



        </div>





    </footer>







    <!-- =====================================================

     PROJECT THEME SCRIPT

====================================================== -->



    <script src="js/script.js"></script>







    <!-- =====================================================

     DETECTION JAVASCRIPT

====================================================== -->



    <script>
        // ========================================================

        // GET PAGE ELEMENTS

        // ========================================================



        const urlForm =

            document.getElementById("urlForm");



        const urlInput =

            document.getElementById("urlInput");



        const analyzeButton =

            document.getElementById("analyzeButton");



        const errorMessage =

            document.getElementById("errorMessage");



        const loadingBox =

            document.getElementById("loadingBox");



        const resultBox =

            document.getElementById("resultBox");



        const resultIcon =

            document.getElementById("resultIcon");



        const resultTitle =

            document.getElementById("resultTitle");



        const statusBadge =

            document.getElementById("statusBadge");



        const analyzedUrl =

            document.getElementById("analyzedUrl");



        const riskScore =

            document.getElementById("riskScore");



        const riskBar =

            document.getElementById("riskBar");



        const resultMessage =

            document.getElementById("resultMessage");



        const reasonList =

            document.getElementById("reasonList");







        // ========================================================

        // FORM SUBMISSION

        // ========================================================



        urlForm.addEventListener(

            "submit",

            async function(event) {



                event.preventDefault();





                const url =

                    urlInput.value.trim();





                // Reset old result

                hideError();



                resultBox.classList.add("hidden");





                // Empty input validation

                if (url === "") {



                    showError(

                        "Please enter a URL before analyzing."

                    );



                    return;



                }





                startLoading();





                try {





                    // Send URL to PHP backend

                    const response = await fetch(

                        "../backend/php/detect_process.php", {



                            method: "POST",



                            headers: {

                                "Content-Type": "application/json"

                            },



                            body: JSON.stringify({

                                url: url

                            })



                        }

                    );





                    // Read response first as text

                    const responseText =

                        await response.text();





                    let data;





                    // Convert backend response to JSON

                    try {



                        data =

                            JSON.parse(responseText);



                    } catch (error) {



                        console.error(

                            "Server Response:",

                            responseText

                        );





                        throw new Error(

                            "The detection server returned an invalid response."

                        );



                    }





                    stopLoading();





                    // Backend error

                    if (!response.ok || !data.success) {



                        showError(

                            data.message ||

                            "Unable to analyze this URL."

                        );



                        return;



                    }





                    // Show result

                    displayResult(data);





                } catch (error) {





                    stopLoading();





                    console.error(error);





                    showError(

                        error.message ||

                        "Unable to connect to the detection server."

                    );



                }



            }

        );







        // ========================================================

        // START LOADING

        // ========================================================





        let loadingInterval;







        function startLoading() {





            loadingBox.classList.remove(

                "hidden"

            );





            analyzeButton.disabled = true;





            analyzeButton.classList.add(

                "opacity-70",

                "cursor-not-allowed"

            );





            analyzeButton.innerHTML = `



    <span

        class="

        inline-block

        w-4

        h-4

        border-2

        border-white/30

        border-t-white

        rounded-full

        animate-spin

        "

    ></span>



    Analyzing...



    `;







            // Start percentage



            let percent = 1;





            const percentText =

                document.getElementById(

                    "loadingPercent"

                );







            loadingInterval =

                setInterval(

                    function() {





                        if (percent < 95) {





                            percent +=

                                Math.floor(

                                    Math.random() * 10

                                );







                            if (percent > 95) {



                                percent = 95;



                            }







                            percentText.textContent =

                                percent + "%";





                        }





                    },

                    150

                );





        }





        // ========================================================

        // STOP LOADING

        // ========================================================





        function stopLoading() {







            clearInterval(

                loadingInterval

            );







            const percentText =

                document.getElementById(

                    "loadingPercent"

                );







            percentText.textContent =

                "100%";











            setTimeout(

                function() {





                    loadingBox.classList.add(

                        "hidden"

                    );







                },

                300

            );









            analyzeButton.disabled = false;





            analyzeButton.classList.remove(

                "opacity-70",

                "cursor-not-allowed"

            );





            analyzeButton.innerHTML =

                "Analyze URL";





        }







        // ========================================================

        // ERROR MESSAGE

        // ========================================================



        function showError(message) {





            errorMessage.textContent =

                message;





            errorMessage.classList.remove(

                "hidden"

            );



        }





        function hideError() {





            errorMessage.classList.add(

                "hidden"

            );





            errorMessage.textContent =

                "";



        }







        // ========================================================

        // DISPLAY ANALYSIS RESULT

        // ========================================================




        function normalizeModelPrediction(value) {

            if (value === null || value === undefined) {
                return "Unknown";
            }

            const text = String(value).trim().toLowerCase();

            if (
                text === "safe" ||
                text === "legitimate" ||
                text === "legitimate_url" ||
                text === "1"
            ) {
                return "Safe";
            }

            if (
                text === "phishing" ||
                text === "phishing_url" ||
                text === "suspicious" ||
                text === "malicious" ||
                text === "0"
            ) {
                return "Phishing";
            }

            return String(value);
        }


        function getModelObject(data, modelName) {

            const names =
                modelName === "cnn" ? ["cnn", "CNN", "cnn_result", "cnnResult"] : [
                    "random_forest",
                    "randomForest",
                    "RandomForest",
                    "rf",
                    "rf_result",
                    "rfResult"
                ];

            for (const name of names) {

                if (
                    data[name] &&
                    typeof data[name] === "object"
                ) {
                    return data[name];
                }
            }

            return {};
        }


        function getModelValue(data, modelName, properties) {

            const modelObject =
                getModelObject(data, modelName);

            for (const property of properties) {

                if (
                    modelObject[property] !== undefined &&
                    modelObject[property] !== null
                ) {
                    return modelObject[property];
                }

                if (
                    data[property] !== undefined &&
                    data[property] !== null
                ) {
                    return data[property];
                }

                const prefix =
                    modelName === "cnn" ?
                    "cnn_" :
                    "rf_";

                const prefixed =
                    prefix + property;

                if (
                    data[prefixed] !== undefined &&
                    data[prefixed] !== null
                ) {
                    return data[prefixed];
                }
            }

            return null;
        }


        function formatConfidence(value) {

            if (
                value === null ||
                value === undefined ||
                value === ""
            ) {
                return "Unavailable";
            }

            let number = Number(value);

            if (Number.isNaN(number)) {
                return String(value);
            }

            if (number <= 1) {
                number *= 100;
            }

            number =
                Math.max(
                    0,
                    Math.min(100, number)
                );

            return number.toFixed(2) + "%";
        }


        function setModelStatus(element, prediction) {

            element.className =
                "px-3 py-1 rounded-full text-xs font-bold";

            if (prediction === "Safe") {

                element.textContent = "SAFE";

                element.classList.add(
                    "bg-green-500/10",
                    "text-green-500"
                );

            } else if (prediction === "Phishing") {

                element.textContent = "PHISHING";

                element.classList.add(
                    "bg-red-500/10",
                    "text-red-500"
                );

            } else {

                element.textContent = "UNKNOWN";

                element.classList.add(
                    "bg-gray-500/10",
                    "text-[var(--muted)]"
                );
            }
        }


        function displayResult(data) {

            resultBox.classList.remove("hidden");


            const status =
                String(
                    data.status || ""
                ).toLowerCase();


            const score =
                Math.max(
                    0,
                    Math.min(
                        100,
                        Number(data.risk_score) || 0
                    )
                );


            analyzedUrl.textContent =
                data.url || urlInput.value;


            riskScore.textContent =
                score.toFixed(2) + " / 100";


            // =========================
            // CNN
            // =========================

            const cnnPrediction =
                normalizeModelPrediction(
                    getModelValue(
                        data,
                        "cnn",
                        [
                            "status",
                            "prediction",
                            "result",
                            "classification"
                        ]
                    )
                );


            const cnnConfidence =
                getModelValue(
                    data,
                    "cnn",
                    [
                        "confidence",
                        "ai_confidence",
                        "probability"
                    ]
                );


            document.getElementById(
                    "cnnPrediction"
                ).textContent =
                cnnPrediction;


            document.getElementById(
                    "cnnConfidence"
                ).textContent =
                formatConfidence(
                    cnnConfidence
                );


            setModelStatus(
                document.getElementById("cnnStatus"),
                cnnPrediction
            );


            // =========================
            // RANDOM FOREST
            // =========================

            const rfPrediction =
                normalizeModelPrediction(
                    getModelValue(
                        data,
                        "rf",
                        [
                            "status",
                            "prediction",
                            "result",
                            "classification"
                        ]
                    )
                );


            const rfConfidence =
                getModelValue(
                    data,
                    "rf",
                    [
                        "confidence",
                        "ai_confidence",
                        "probability"
                    ]
                );


            document.getElementById(
                    "rfPrediction"
                ).textContent =
                rfPrediction;


            document.getElementById(
                    "rfConfidence"
                ).textContent =
                formatConfidence(
                    rfConfidence
                );


            setModelStatus(
                document.getElementById("rfStatus"),
                rfPrediction
            );


            // =========================
            // MODEL AGREEMENT
            // =========================

            const agreementBox =
                document.getElementById(
                    "modelAgreement"
                );


            if (
                cnnPrediction !== "Unknown" &&
                rfPrediction !== "Unknown" &&
                cnnPrediction === rfPrediction
            ) {

                agreementBox.innerHTML = `
                    <span class="font-bold text-green-500">
                        ✓ Model Agreement
                    </span>
                    <br>
                    Both AI models classified this URL as
                    <strong>${cnnPrediction}</strong>.
                `;

            } else if (
                cnnPrediction !== "Unknown" &&
                rfPrediction !== "Unknown"
            ) {

                agreementBox.innerHTML = `
                    <span class="font-bold text-yellow-400">
                        ⚠ Model Disagreement
                    </span>
                    <br>
                    CNN classified this URL as
                    <strong>${cnnPrediction}</strong>,
                    while Random Forest classified it as
                    <strong>${rfPrediction}</strong>.
                `;

            } else {

                agreementBox.innerHTML = `
                    <span class="font-bold text-[var(--primary)]">
                        AI Model Results
                    </span>
                    <br>
                    One or more model results were unavailable.
                `;
            }


            // =========================
            // RESULT MESSAGE
            // =========================

            if (status === "safe") {

                resultMessage.textContent =
                    "The AI models classify this URL as safe. " +
                    "However, users should still verify the website " +
                    "domain before entering sensitive information.";

            } else if (status === "phishing") {

                resultMessage.textContent =
                    "The AI models detected characteristics associated " +
                    "with phishing. Avoid entering passwords, payment " +
                    "details or personal information unless the website " +
                    "has been independently verified.";

            } else {

                resultMessage.textContent =
                    data.message ||
                    "The AI models could not determine a reliable result.";
            }


            // =========================
            // SECURITY RECOMMENDATION
            // =========================

            const recommendation =
                document.getElementById(
                    "securityRecommendation"
                );


            if (recommendation) {

                if (status === "safe") {

                    recommendation.textContent =
                        "The AI models classify this URL as safe. " +
                        "Nevertheless, verify the domain carefully " +
                        "before entering sensitive information.";

                } else {

                    recommendation.textContent =
                        "Do not enter passwords, banking information " +
                        "or personal details on this website until the " +
                        "domain has been independently verified.";
                }
            }


            // =========================
            // DETECTION DETAILS
            // URL CHARACTERISTICS
            // =========================

            reasonList.innerHTML = "";


            function addReason(text) {

                if (!text) {
                    return;
                }

                const listItem =
                    document.createElement("li");

                listItem.className = `
        flex
        items-start
        gap-3
        text-[var(--text)]
        text-sm
        leading-relaxed
    `;

                const bullet =
                    document.createElement("span");

                bullet.textContent = "•";

                bullet.className =
                    "text-[var(--primary)] font-bold";

                const textElement =
                    document.createElement("span");

                textElement.textContent =
                    text;

                listItem.appendChild(bullet);
                listItem.appendChild(textElement);

                reasonList.appendChild(listItem);
            }


            // ========================================================
            // ANALYZE URL STRUCTURE
            // ========================================================

            const detectedURL =
                data.url || urlInput.value.trim();


            let parsedURL;

            try {

                parsedURL =
                    new URL(
                        detectedURL.startsWith("http") ?
                        detectedURL :
                        "https://" + detectedURL
                    );

            } catch (error) {

                parsedURL = null;

            }


            // ========================================================
            // HTTPS CHECK
            // ========================================================

            if (parsedURL) {

                if (
                    parsedURL.protocol.toLowerCase() === "https:"
                ) {

                    addReason(
                        "The URL uses HTTPS encryption for communication."
                    );

                } else {

                    addReason(
                        "The URL does not use HTTPS encryption, which can increase security risk."
                    );

                }


                // ====================================================
                // IP ADDRESS CHECK
                // ====================================================

                const hostname =
                    parsedURL.hostname || "";


                const isIPAddress =
                    /^(\d{1,3}\.){3}\d{1,3}$/.test(hostname);


                if (isIPAddress) {

                    addReason(
                        "The website uses a direct IP address instead of a conventional domain name."
                    );

                } else {

                    addReason(
                        "The URL uses a conventional domain name rather than a direct IP address."
                    );

                }


                // ====================================================
                // URL LENGTH
                // ====================================================

                const urlLength =
                    detectedURL.length;


                if (urlLength > 100) {

                    addReason(
                        "The URL is unusually long, which can sometimes be associated with suspicious links."
                    );

                } else if (urlLength > 75) {

                    addReason(
                        "The URL has a moderately long structure."
                    );

                } else {

                    addReason(
                        "The URL has a relatively short and simple structure."
                    );

                }


                // ====================================================
                // SUBDOMAIN CHECK
                // ====================================================

                const domainParts =
                    hostname.split(".").filter(Boolean);


                if (domainParts.length > 3) {

                    addReason(
                        "The domain contains multiple subdomains, which should be checked carefully."
                    );

                } else {

                    addReason(
                        "The domain structure does not contain an excessive number of subdomains."
                    );

                }


                // ====================================================
                // SPECIAL CHARACTER CHECK
                // ====================================================

                const specialCharacters =
                    (
                        detectedURL.match(
                            /[^a-zA-Z0-9]/g
                        ) || []
                    ).length;


                const specialCharacterRatio =
                    urlLength > 0 ?
                    specialCharacters / urlLength :
                    0;


                if (
                    specialCharacterRatio > 0.20
                ) {

                    addReason(
                        "The URL contains a relatively high number of special characters."
                    );

                } else {

                    addReason(
                        "The URL does not contain an unusually high number of special characters."
                    );

                }


                // ====================================================
                // QUERY PARAMETER CHECK
                // ====================================================

                const query =
                    parsedURL.search;


                if (
                    query &&
                    query.length > 50
                ) {

                    addReason(
                        "The URL contains a relatively long query string with additional parameters."
                    );

                } else if (query) {

                    addReason(
                        "The URL contains query parameters that form part of the requested web address."
                    );

                } else {

                    addReason(
                        "The URL does not contain additional query parameters."
                    );

                }


                // ====================================================
                // OBFUSCATION CHECK
                // ====================================================

                const hasEncodedCharacters =
                    /%[0-9a-fA-F]{2}/.test(
                        detectedURL
                    );


                const hasAtSymbol =
                    detectedURL.includes("@");


                const hasHexPattern =
                    /0x[0-9a-fA-F]+/i.test(
                        detectedURL
                    );


                if (
                    hasEncodedCharacters ||
                    hasAtSymbol ||
                    hasHexPattern
                ) {

                    addReason(
                        "The URL contains patterns that may be used to obscure or encode parts of the address."
                    );

                } else {

                    addReason(
                        "No obvious URL obfuscation patterns were identified."
                    );

                }


                // ====================================================
                // DOMAIN DEPTH
                // ====================================================

                if (
                    domainParts.length >= 4
                ) {

                    addReason(
                        "The domain has a deeper-than-usual hostname structure and should be verified carefully."
                    );

                }


                // ====================================================
                // FINAL STRUCTURAL ASSESSMENT
                // ====================================================

                if (
                    status === "safe"
                ) {

                    addReason(
                        "Overall, the URL structure shows relatively low-risk characteristics based on the detected URL features."
                    );

                } else if (
                    status === "phishing"
                ) {

                    addReason(
                        "Several URL characteristics require caution and may be associated with phishing activity."
                    );

                }

            } else {

                addReason(
                    "The URL structure could not be fully analyzed."
                );

            }




            // =========================
            // FINAL RESULT STYLE
            // =========================

            if (status === "safe") {

                resultIcon.textContent = "✓";

                resultIcon.className = `
                    w-14 h-14 rounded-full
                    flex items-center justify-center
                    text-2xl font-bold
                    bg-green-500/10 text-green-500
                `;

                resultTitle.textContent =
                    "URL Appears Safe";

                resultTitle.className = `
                    text-2xl font-bold mt-1 text-green-500
                `;

                statusBadge.textContent =
                    "LOW RISK";

                statusBadge.className = `
                    px-4 py-2 rounded-full text-sm font-bold w-fit
                    bg-green-500/10 text-green-500
                `;

                riskBar.className = `
                    h-full rounded-full transition-all duration-700
                    bg-green-500
                `;

            } else if (status === "phishing") {

                resultIcon.textContent = "✕";

                resultIcon.className = `
                    w-14 h-14 rounded-full
                    flex items-center justify-center
                    text-2xl font-bold
                    bg-red-500/10 text-red-500
                `;

                resultTitle.textContent =
                    "Possible Phishing URL";

                resultTitle.className = `
                    text-2xl font-bold mt-1 text-red-500
                `;

                statusBadge.textContent =
                    "HIGH RISK";

                statusBadge.className = `
                    px-4 py-2 rounded-full text-sm font-bold w-fit
                    bg-red-500/10 text-red-500
                `;

                riskBar.className = `
                    h-full rounded-full transition-all duration-700
                    bg-red-500
                `;

            } else {

                resultIcon.textContent = "?";

                resultIcon.className = `
                    w-14 h-14 rounded-full
                    flex items-center justify-center
                    text-2xl font-bold
                    bg-yellow-500/10 text-yellow-400
                `;

                resultTitle.textContent =
                    "Unable to Determine Result";

                resultTitle.className = `
                    text-2xl font-bold mt-1 text-yellow-400
                `;

                statusBadge.textContent =
                    "UNKNOWN";

                statusBadge.className = `
                    px-4 py-2 rounded-full text-sm font-bold w-fit
                    bg-yellow-500/10 text-yellow-400
                `;

                riskBar.className = `
                    h-full rounded-full transition-all duration-700
                    bg-yellow-400
                `;
            }


            riskBar.style.width = "0%";

            setTimeout(
                function() {
                    riskBar.style.width =
                        score + "%";
                },
                100
            );


            resultBox.scrollIntoView({
                behavior: "smooth",
                block: "nearest"
            });

        }


        // ========================================================

        // AUTO ANALYZE URL FROM HOME PAGE

        // detection.php?url=https://example.com

        // ========================================================



        <?php if ($urlFromHome !== ''): ?>





            window.addEventListener(

                "load",

                function() {





                    setTimeout(

                        function() {



                            urlForm.requestSubmit();



                        },



                        300

                    );



                }

            );





        <?php endif; ?>
    </script>





</body>



</html>