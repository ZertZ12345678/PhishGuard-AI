<?php

session_start();


// =====================================
// LOGOUT
// =====================================

if (isset($_GET["logout"])) {

    session_unset();

    session_destroy();

    header("Location: index.php");

    exit();
}


// =====================================
// LOGIN STATUS
// =====================================

$isLoggedIn =
    isset($_SESSION["UserID"]) &&
    isset($_SESSION["Role"]) &&
    $_SESSION["Role"] == "User";


// =====================================
// ADMIN ACCESS
// =====================================

if (
    isset($_SESSION["Role"]) &&
    $_SESSION["Role"] == "Admin"
) {

    header("Location: admin_dashboard.php");

    exit();
}

?>



<!DOCTYPE html>

<html lang="en">


<head>

    <meta charset="UTF-8">


    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">


    <title>
        Awareness - PhishGuard AI
    </title>


    <!-- Tailwind CSS -->

    <script src="https://cdn.tailwindcss.com"></script>


    <!-- Custom CSS -->

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
     NAVBAR
===================================== -->


    <header
        class="
    flex
    justify-between
    items-center
    px-10
    py-5
    bg-[var(--nav)]
    ">


        <!-- Logo -->


        <a
            href="<?php echo $isLoggedIn ? 'user_home.php' : 'index.php'; ?>"
            class="
        text-2xl
        font-bold
        text-[var(--secondary)]
        no-underline
        ">

            🛡 PhishGuard AI

        </a>





        <!-- Navigation -->


        <nav
            class="
        flex
        items-center
        gap-6
        ">



            <!-- Home -->


            <a
                href="<?php echo $isLoggedIn ? 'user_home.php' : 'index.php'; ?>"
                class="nav-link">

                Home

            </a>





            <!-- Detection -->

            <?php if ($isLoggedIn): ?>

                <a
                    href="detection.php"
                    class="nav-link">
                    Detection
                </a>

            <?php else: ?>

                <a
                    href="login.php"
                    class="nav-link">
                    Detection
                </a>

            <?php endif; ?>





            <!-- Awareness -->


            <a
                href="awareness.php"

                class="
            text-[var(--primary)]
            font-semibold
            no-underline
            ">

                Awareness

            </a>





            <!-- Quiz -->


            <?php if ($isLoggedIn): ?>


                <a
                    href="quiz.php"
                    class="nav-link">

                    Quiz

                </a>


            <?php else: ?>


                <a
                    href="login.php"
                    class="nav-link">

                    Quiz

                </a>


            <?php endif; ?>





            <!-- =================================
             LOGGED-IN USER
        ================================= -->


            <?php if ($isLoggedIn): ?>


                <!-- Dashboard -->


                <a
                    href="user_home.php"
                    class="nav-link">

                    Dashboard

                </a>





                <!-- Logout -->


                <a
                    href="awareness.php?logout=1"
                    class="nav-link">

                    Logout

                </a>




            <?php else: ?>



                <!-- =================================
                 GUEST USER
            ================================= -->


                <!-- Login -->


                <a
                    href="login.php"
                    class="nav-link">

                    Login

                </a>





                <!-- Register -->


                <a
                    href="register.php"
                    class="nav-link">

                    Register

                </a>



            <?php endif; ?>





            <!-- Theme Toggle -->


            <button
                id="theme-toggle"

                class="
            text-2xl
            bg-transparent
            border-none
            cursor-pointer
            ">

                ☀

            </button>



        </nav>


    </header>







    <!-- =====================================
     HERO
===================================== -->


    <section
        class="
    text-center
    py-20
    px-6
    ">


        <div
            class="
        inline-block
        px-5
        py-2
        rounded-full
        bg-blue-500/10
        text-[var(--primary)]
        font-semibold
        text-sm
        ">

            🛡 Cybersecurity Awareness

        </div>





        <h1
            class="
        mt-6
        text-4xl
        md:text-5xl
        font-bold
        text-[var(--secondary)]
        ">

            Stay Safe From Phishing Attacks

        </h1>





        <p
            class="
        mt-5
        max-w-3xl
        mx-auto
        text-[var(--muted)]
        text-lg
        leading-relaxed
        ">

            Learn how phishing attacks work, how attackers
            trick users, and how you can protect your personal
            information online.

        </p>


    </section>







    <!-- =====================================
     WHAT IS PHISHING
===================================== -->


    <section
        class="
    max-w-6xl
    mx-auto
    px-6
    ">


        <div
            class="
        bg-[var(--card)]
        rounded-2xl
        shadow
        p-8
        ">


            <h2
                class="
            text-2xl
            font-bold
            text-[var(--secondary)]
            ">

                What is Phishing?

            </h2>



            <p
                class="
            mt-4
            text-[var(--muted)]
            leading-relaxed
            ">

                Phishing is a cyberattack technique where attackers
                pretend to be trusted organizations or individuals
                to steal sensitive information such as passwords,
                bank details, and personal data.

                Phishing attacks commonly happen through fake
                websites, emails, messages, and malicious links.

            </p>


        </div>


    </section>







    <!-- =====================================
     ATTACK TYPES
===================================== -->


    <section
        class="
    max-w-6xl
    mx-auto
    px-6
    py-12
    ">


        <h2
            class="
        text-3xl
        font-bold
        text-center
        text-[var(--secondary)]
        ">

            Common Types of Phishing Attacks

        </h2>




        <div
            class="
        grid
        md:grid-cols-3
        gap-8
        mt-10
        ">



            <!-- Email -->


            <div
                class="
            bg-[var(--card)]
            rounded-xl
            p-6
            shadow
            ">

                <div class="text-4xl">

                    📧

                </div>


                <h3
                    class="
                mt-4
                font-bold
                text-xl
                ">

                    Email Phishing

                </h3>


                <p
                    class="
                mt-3
                text-[var(--muted)]
                ">

                    Fake emails pretending to be banks,
                    companies, or services to steal information.

                </p>

            </div>





            <!-- Website -->


            <div
                class="
            bg-[var(--card)]
            rounded-xl
            p-6
            shadow
            ">

                <div class="text-4xl">

                    🌐

                </div>


                <h3
                    class="
                mt-4
                font-bold
                text-xl
                ">

                    Website Phishing

                </h3>


                <p
                    class="
                mt-3
                text-[var(--muted)]
                ">

                    Fake websites designed to look like
                    real websites and collect user credentials.

                </p>

            </div>





            <!-- SMS -->


            <div
                class="
            bg-[var(--card)]
            rounded-xl
            p-6
            shadow
            ">

                <div class="text-4xl">

                    📱

                </div>


                <h3
                    class="
                mt-4
                font-bold
                text-xl
                ">

                    SMS Phishing

                </h3>


                <p
                    class="
                mt-3
                text-[var(--muted)]
                ">

                    Fraudulent text messages containing
                    dangerous links or fake requests.

                </p>

            </div>


        </div>


    </section>







    <!-- =====================================
     WARNING SIGNS
===================================== -->


    <section
        class="
    max-w-6xl
    mx-auto
    px-6
    py-12
    ">


        <h2
            class="
        text-3xl
        font-bold
        text-center
        text-[var(--secondary)]
        ">

            Warning Signs of Phishing URLs

        </h2>




        <div
            class="
        grid
        md:grid-cols-2
        gap-6
        mt-10
        ">



            <!-- Suspicious Domain -->


            <div
                class="
            bg-[var(--card)]
            p-6
            rounded-xl
            shadow
            ">

                <h3
                    class="font-bold text-xl">

                    ⚠ Suspicious Domain

                </h3>


                <p
                    class="
                mt-3
                text-[var(--muted)]
                ">

                    Attackers often create domains that look similar
                    to legitimate websites.

                    <br><br>

                    Example:

                    <br>

                    google-security-login.com

                    <br><br>

                    instead of:

                    <br>

                    google.com

                </p>

            </div>





            <!-- Urgent Requests -->


            <div
                class="
            bg-[var(--card)]
            p-6
            rounded-xl
            shadow
            ">

                <h3
                    class="font-bold text-xl">

                    ⚠ Urgent Requests

                </h3>


                <p
                    class="
                mt-3
                text-[var(--muted)]
                ">

                    Messages that force immediate action,
                    such as "verify now" or "account suspended",
                    are common phishing techniques.

                </p>

            </div>





            <!-- Unusual URLs -->


            <div
                class="
            bg-[var(--card)]
            p-6
            rounded-xl
            shadow
            ">

                <h3
                    class="font-bold text-xl
                ">

                    ⚠ Unusual URLs

                </h3>


                <p
                    class="
                mt-3
                text-[var(--muted)]
                ">

                    Long URLs, many symbols, strange domains,
                    and IP addresses can indicate phishing.

                </p>

            </div>





            <!-- Fake Login -->


            <div
                class="
            bg-[var(--card)]
            p-6
            rounded-xl
            shadow
            ">

                <h3
                    class="font-bold text-xl">

                    ⚠ Fake Login Pages

                </h3>


                <p
                    class="
                mt-3
                text-[var(--muted)]
                ">

                    Never enter passwords on websites accessed
                    through suspicious links.

                </p>

            </div>


        </div>


    </section>







    <!-- =====================================
     PROTECTION
===================================== -->


    <section
        class="
    max-w-6xl
    mx-auto
    px-6
    py-12
    ">


        <h2
            class="
        text-3xl
        font-bold
        text-center
        text-[var(--secondary)]
        ">

            How To Protect Yourself

        </h2>




        <div
            class="
        bg-[var(--card)]
        rounded-2xl
        shadow
        p-8
        mt-10
        ">


            <ul
                class="
            space-y-4
            text-[var(--muted)]
            ">


                <li>

                    ✅ Check the website domain carefully before logging in.

                </li>


                <li>

                    ✅ Avoid clicking unknown links from emails or messages.

                </li>


                <li>

                    ✅ Use strong passwords and enable two-factor authentication.

                </li>


                <li>

                    ✅ Keep software and security tools updated.

                </li>


                <li>

                    ✅ Use PhishGuard AI to analyze suspicious URLs.

                </li>


            </ul>


        </div>


    </section>







    <!-- =====================================
     FAQ
===================================== -->


    <section
        class="
    max-w-6xl
    mx-auto
    px-6
    py-12
    ">


        <h2
            class="
        text-3xl
        font-bold
        text-center
        text-[var(--secondary)]
        ">

            Frequently Asked Cybersecurity Questions

        </h2>




        <p
            class="
        text-center
        mt-4
        text-[var(--muted)]
        ">

            Learn common phishing and cybersecurity questions to improve
            your online safety.

        </p>





        <div
            class="
        mt-10
        space-y-5
        ">



            <!-- Q1 -->


            <div
                class="
            bg-[var(--card)]
            rounded-xl
            p-6
            shadow
            ">

                <h3 class="font-bold text-xl">

                    Q1. What is phishing?

                </h3>


                <p
                    class="
                mt-3
                text-[var(--muted)]
                leading-relaxed
                ">

                    Phishing is a cyberattack where attackers pretend to be
                    trusted people or organizations to trick users into revealing
                    sensitive information such as passwords, banking details,
                    or personal data.

                </p>

            </div>





            <!-- Q2 -->


            <div
                class="
            bg-[var(--card)]
            rounded-xl
            p-6
            shadow
            ">

                <h3 class="font-bold text-xl">

                    Q2. How can I identify a phishing website?

                </h3>


                <p
                    class="
                mt-3
                text-[var(--muted)]
                ">

                    Check the website address carefully. Warning signs include
                    strange domain names, incorrect spelling, missing HTTPS,
                    unusual subdomains, and requests for sensitive information.

                </p>

            </div>





            <!-- Q3 -->


            <div
                class="
            bg-[var(--card)]
            rounded-xl
            p-6
            shadow
            ">

                <h3 class="font-bold text-xl">

                    Q3. Why do attackers create fake websites?

                </h3>


                <p
                    class="
                mt-3
                text-[var(--muted)]
                ">

                    Attackers create fake websites to steal login credentials,
                    financial information, or personal data by making users
                    believe they are visiting a legitimate website.

                </p>

            </div>





            <!-- Q4 -->


            <div
                class="
            bg-[var(--card)]
            rounded-xl
            p-6
            shadow
            ">

                <h3 class="font-bold text-xl">

                    Q4. Is a website with HTTPS always safe?

                </h3>


                <p
                    class="
                mt-3
                text-[var(--muted)]
                ">

                    No. HTTPS only means the connection is encrypted.
                    Phishing websites can also use HTTPS certificates.
                    Users should check the domain name and website reputation.

                </p>

            </div>





            <!-- Q5 -->


            <div
                class="
            bg-[var(--card)]
            rounded-xl
            p-6
            shadow
            ">

                <h3 class="font-bold text-xl">

                    Q5. What information should never be shared online?

                </h3>


                <p
                    class="
                mt-3
                text-[var(--muted)]
                ">

                    Never share passwords, one-time verification codes,
                    bank account details, credit card information,
                    or personal security answers with unknown sources.

                </p>

            </div>





            <!-- Q6 -->


            <div
                class="
            bg-[var(--card)]
            rounded-xl
            p-6
            shadow
            ">

                <h3 class="font-bold text-xl">

                    Q6. Why do phishing messages create urgency?

                </h3>


                <p
                    class="
                mt-3
                text-[var(--muted)]
                ">

                    Attackers use urgency and fear to make victims act quickly
                    without checking whether the message or link is legitimate.

                    Examples:

                    "Your account will be closed today"

                    and

                    "Verify immediately".

                </p>

            </div>





            <!-- Q7 -->


            <div
                class="
            bg-[var(--card)]
            rounded-xl
            p-6
            shadow
            ">

                <h3 class="font-bold text-xl">

                    Q7. What should I do after clicking a suspicious link?

                </h3>


                <p
                    class="
                mt-3
                text-[var(--muted)]
                ">

                    Do not enter any personal information.
                    Close the website, change passwords if necessary,
                    enable two-factor authentication, and scan your device
                    for possible threats.

                </p>

            </div>





            <!-- Q8 -->


            <div
                class="
            bg-[var(--card)]
            rounded-xl
            p-6
            shadow
            ">

                <h3 class="font-bold text-xl">

                    Q8. How does PhishGuard AI detect phishing URLs?

                </h3>


                <p
                    class="
                mt-3
                text-[var(--muted)]
                ">

                    PhishGuard AI uses Machine Learning technology.
                    A Random Forest classifier analyzes URL features such as
                    domain structure, URL length, HTTPS usage, and suspicious
                    patterns to estimate phishing risk.

                </p>

            </div>





            <!-- Q9 -->


            <div
                class="
            bg-[var(--card)]
            rounded-xl
            p-6
            shadow
            ">

                <h3 class="font-bold text-xl">

                    Q9. Can AI detection be wrong?

                </h3>


                <p
                    class="
                mt-3
                text-[var(--muted)]
                ">

                    Yes. Machine Learning models can sometimes produce false
                    positives or false negatives. Users should combine AI results
                    with careful security practices.

                </p>

            </div>





            <!-- Q10 -->


            <div
                class="
            bg-[var(--card)]
            rounded-xl
            p-6
            shadow
            ">

                <h3 class="font-bold text-xl">

                    Q10. How can I improve my cybersecurity habits?

                </h3>


                <p
                    class="
                mt-3
                text-[var(--muted)]
                ">

                    Use strong passwords, enable two-factor authentication,
                    avoid suspicious links, update software regularly,
                    and stay informed about new cyber threats.

                </p>

            </div>


        </div>


    </section>







    <!-- =====================================
     FOOTER
===================================== -->


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

                        <?php if ($isLoggedIn): ?>

                            <a
                                href="detection.php"
                                class="nav-link">
                                Detection
                            </a>

                        <?php else: ?>

                            <a
                                href="login.php"
                                class="nav-link">
                                Detection
                            </a>

                        <?php endif; ?>

                    </li>


                    <li>

                        <?php if ($isLoggedIn): ?>

                            <a
                                href="quiz.php"
                                class="nav-link no-underline">

                                Security Quiz

                            </a>

                        <?php else: ?>

                            <a
                                href="login.php"
                                class="nav-link no-underline">

                                Security Quiz

                            </a>

                        <?php endif; ?>

                    </li>


                </ul>


            </div>





            <!-- About System -->


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





        <!-- Copyright -->


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





    <!-- =====================================
     JAVASCRIPT
===================================== -->


    <script src="js/script.js"></script>


</body>

</html>