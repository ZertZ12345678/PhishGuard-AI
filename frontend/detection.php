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
                href="#"
                class="nav-link">
                Quiz
            </a>


            <!-- Dashboard -->

            <a
                href="#"
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
                 RESULT MESSAGE
            ============================================== -->

                <div
                    id="resultMessage"

                    class="
                    mt-7
                    text-[var(--text)]
                    leading-relaxed
                ">
                </div>



                <!-- =============================================
                 DETECTION DETAILS
            ============================================== -->

                <div class="mt-7">


                    <h3
                        class="
                        text-lg
                        font-bold
                        text-[var(--secondary)]
                    ">
                        Detection Details
                    </h3>


                    <ul
                        id="reasonList"

                        class="
                        mt-4
                        space-y-3
                    ">
                    </ul>


                </div>



                <!-- =============================================
                 WARNING / DISCLAIMER
            ============================================== -->

                <div
                    class="
                    mt-8
                    p-4
                    rounded-xl
                    bg-blue-500/10
                    text-[var(--text)]
                    text-sm
                    leading-relaxed
                ">

                    <span
                        class="
                        font-bold
                        text-[var(--primary)]
                    ">
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
                            href="index.php"
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


                    <li>
                        <a
                            href="#"
                            class="nav-link no-underline">
                            Detection
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
                            href="#"
                            class="nav-link no-underline">
                            Phishing Awareness
                        </a>
                    </li>


                    <li>
                        <a
                            href="#"
                            class="nav-link no-underline">
                            Cybersecurity Tips
                        </a>
                    </li>


                    <li>
                        <a
                            href="#"
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

                    Powered by Machine Learning
                    technology using a Random Forest
                    classification model for phishing
                    URL detection.

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

        function displayResult(data) {


            // Show result box
            resultBox.classList.remove(
                "hidden"
            );


            const status =
                String(data.status || "")
                .toLowerCase();


            // Make sure score stays 0-100
            const score =
                Math.max(
                    0,
                    Math.min(
                        100,
                        Number(data.risk_score) || 0
                    )
                );


            // URL
            analyzedUrl.textContent =
                data.url || urlInput.value;


            // Score
            riskScore.textContent =
                score + " / 100";


            // Message
            resultMessage.textContent =
                data.message || "";


            // Clear old reasons
            reasonList.innerHTML = "";


            // Add detection reasons
            if (
                Array.isArray(data.reasons) &&
                data.reasons.length > 0
            ) {


                data.reasons.forEach(
                    function(reason) {


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


                        const text =
                            document.createElement("span");


                        text.textContent =
                            reason;


                        listItem.appendChild(
                            bullet
                        );


                        listItem.appendChild(
                            text
                        );


                        reasonList.appendChild(
                            listItem
                        );

                    }
                );

            }



            // ====================================================
            // SAFE RESULT
            // ====================================================

            if (status === "safe") {


                resultIcon.textContent =
                    "✓";


                resultIcon.className = `
            w-14
            h-14
            rounded-full
            flex
            items-center
            justify-center
            text-2xl
            font-bold
            bg-green-500/10
            text-green-500
        `;


                resultTitle.textContent =
                    "URL Appears Safe";


                resultTitle.className = `
            text-2xl
            font-bold
            mt-1
            text-green-500
        `;


                statusBadge.textContent =
                    "LOW RISK";


                statusBadge.className = `
            px-4
            py-2
            rounded-full
            text-sm
            font-bold
            w-fit
            bg-green-500/10
            text-green-500
        `;


                riskBar.className = `
            h-full
            rounded-full
            transition-all
            duration-700
            bg-green-500
        `;

            }



            // ====================================================
            // SUSPICIOUS RESULT
            // ====================================================
            else if (status === "suspicious") {


                resultIcon.textContent =
                    "!";


                resultIcon.className = `
            w-14
            h-14
            rounded-full
            flex
            items-center
            justify-center
            text-2xl
            font-bold
            bg-yellow-500/10
            text-yellow-500
        `;


                resultTitle.textContent =
                    "Suspicious URL";


                resultTitle.className = `
            text-2xl
            font-bold
            mt-1
            text-yellow-500
        `;


                statusBadge.textContent =
                    "MEDIUM RISK";


                statusBadge.className = `
            px-4
            py-2
            rounded-full
            text-sm
            font-bold
            w-fit
            bg-yellow-500/10
            text-yellow-500
        `;


                riskBar.className = `
            h-full
            rounded-full
            transition-all
            duration-700
            bg-yellow-500
        `;

            }



            // ====================================================
            // PHISHING RESULT
            // ====================================================
            else {


                resultIcon.textContent =
                    "✕";


                resultIcon.className = `
            w-14
            h-14
            rounded-full
            flex
            items-center
            justify-center
            text-2xl
            font-bold
            bg-red-500/10
            text-red-500
        `;


                resultTitle.textContent =
                    "Possible Phishing URL";


                resultTitle.className = `
            text-2xl
            font-bold
            mt-1
            text-red-500
        `;


                statusBadge.textContent =
                    "HIGH RISK";


                statusBadge.className = `
            px-4
            py-2
            rounded-full
            text-sm
            font-bold
            w-fit
            bg-red-500/10
            text-red-500
        `;


                riskBar.className = `
            h-full
            rounded-full
            transition-all
            duration-700
            bg-red-500
        `;

            }



            // ====================================================
            // RISK BAR ANIMATION
            // ====================================================

            riskBar.style.width =
                "0%";


            setTimeout(
                function() {

                    riskBar.style.width =
                        score + "%";

                },

                100
            );



            // Scroll result into view
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