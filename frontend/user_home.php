<!DOCTYPE html>
<html>

<head>
    <title>User Home - PhishGuard AI</title>

    <link rel="stylesheet" href="css/style.css">

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[var(--bg)] text-[var(--text)] transition duration-300">

    <header class="flex justify-between items-center px-10 py-5 bg-[var(--nav)]">

        <a href="user_home.php"
            class="text-2xl font-bold text-[var(--secondary)] no-underline">
            🛡 PhishGuard AI
        </a>


        <nav class="flex items-center gap-6">

            <a href="user_home.php"
                class="text-[var(--primary)] font-semibold no-underline">
                Home
            </a>

            <a href="detection.php"
                class="nav-link">
                Detection
            </a>

            <a href="awareness.php"
                class="nav-link">
                Awareness
            </a>

            <a href="#"
                class="nav-link">
                Quiz
            </a>

            <a href="#"
                class="nav-link">
                Dashboard
            </a>

            <a href="index.php"
                class="nav-link">
                Logout
            </a>


            <button id="theme-toggle"
                class="text-2xl bg-transparent border-none cursor-pointer">
                ☀
            </button>

        </nav>

    </header>


    <section class="text-center py-24 px-10">

        <h1 class="text-5xl font-bold text-[var(--secondary)]">
            Welcome to PhishGuard AI
        </h1>


        <p class="mt-6 text-lg text-[var(--muted)]">
            Protect yourself from phishing attacks with AI-powered security tools.
        </p>


        <div class="grid md:grid-cols-3 gap-8 mt-12">


            <div class="bg-[var(--card)] p-8 rounded-xl shadow">

                <h2 class="text-xl font-bold text-[var(--secondary)]">
                    🔍 URL Detection
                </h2>

                <p class="mt-4 text-[var(--text)]">
                    Analyze suspicious URLs and identify phishing threats.
                </p>

            </div>



            <div class="bg-[var(--card)] p-8 rounded-xl shadow">

                <h2 class="text-xl font-bold text-[var(--secondary)]">
                    🛡 Awareness
                </h2>

                <p class="mt-4 text-[var(--text)]">
                    Learn cybersecurity protection methods.
                </p>

            </div>



            <div class="bg-[var(--card)] p-8 rounded-xl shadow">

                <h2 class="text-xl font-bold text-[var(--secondary)]">
                    📝 Quiz
                </h2>

                <p class="mt-4 text-[var(--text)]">
                    Test your security knowledge.
                </p>

            </div>


        </div>


    </section>

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

    <script src="js/script.js"></script>

</body>

</html>