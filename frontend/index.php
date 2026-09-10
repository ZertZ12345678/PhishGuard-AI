<!DOCTYPE html>
<html class="transition duration-300">

<head>
    <title>PhishGuard AI</title>

    <link rel="stylesheet" href="css/style.css">

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[var(--bg)] text-[var(--text)]">



    <header class="flex justify-between items-center px-10 py-5 bg-[var(--nav)]">

        <a href="index.php"
            class="text-2xl font-bold text-[var(--secondary)] no-underline">
            🛡 PhishGuard AI
        </a>

        <nav class="flex items-center gap-6">

            <a href="index.php"
                class="text-[var(--primary)] font-semibold no-underline">
                Home
            </a>


            <a href="awareness.php"
                class="nav-link">
                Awareness
            </a>

            <a href="#"
                class="nav-link">
                Quiz
            </a>

            <a href="register.php"
                class="nav-link">
                Register
            </a>

            <a href="login.php"
                class="nav-link">
                Login
            </a>

            <button id="theme-toggle"
                class="text-2xl bg-transparent border-none cursor-pointer">
                ☀
            </button>

        </nav>

    </header>


    <section class="bg-[var(--hero)] text-center px-10 py-28">

        <h1 class="text-5xl font-bold text-[var(--text)]">
            Detect Phishing Before It Detects You
        </h1>

        <p class="max-w-4xl mx-auto mt-6 text-lg text-[var(--muted)]">
            PhishGuard AI is an AI-powered phishing URL detection and cybersecurity awareness platform that helps users identify suspicious links and stay safe online.
        </p>


        <div class="flex justify-center mt-10">

            <input
                type="text"
                placeholder="Enter suspicious URL to analyze"
                class="w-96 px-5 py-4 rounded-l-lg bg-[var(--card)] text-[var(--text)] outline-none">

            <a href="register.php">

                <button
                    class="px-8 py-4 bg-[var(--primary)] text-white rounded-r-lg hover:bg-[var(--secondary)] transition">
                    Analyze URL
                </button>

            </a>


        </div>


        <div class="flex justify-center gap-5 mt-8">

            <a href="register.php">

                <button
                    class="px-8 py-3 bg-[var(--primary)] text-white rounded-lg hover:bg-[var(--secondary)] transition">
                    Create Account
                </button>

            </a>


            <a href="login.php">

                <button
                    class="px-8 py-3 bg-slate-600 text-white rounded-lg hover:bg-slate-500 transition">
                    Login
                </button>

            </a>

        </div>

    </section>


    <section class="px-10 py-16">

        <h2 class="text-center text-3xl font-bold text-[var(--secondary)] mb-10">
            Protect Yourself With Smart Security
        </h2>


        <div class="grid md:grid-cols-3 gap-8">


            <div class="bg-[var(--card)] p-8 rounded-xl shadow">

                <h3 class="text-xl font-bold text-[var(--secondary)]">
                    🔍 AI URL Detection
                </h3>

                <p class="mt-4 text-[var(--muted)]">
                    Analyze suspicious URLs using machine learning algorithms and classify them as safe or phishing.
                </p>

            </div>


            <div class="bg-[var(--card)] p-8 rounded-xl shadow">

                <h3 class="text-xl font-bold text-[var(--secondary)]">
                    🛡 Cybersecurity Awareness
                </h3>

                <p class="mt-4 text-[var(--muted)]">
                    Learn phishing techniques, online threats, and effective prevention methods.
                </p>

            </div>


            <div class="bg-[var(--card)] p-8 rounded-xl shadow">

                <h3 class="text-xl font-bold text-[var(--secondary)]">
                    📝 Security Quiz
                </h3>

                <p class="mt-4 text-[var(--muted)]">
                    Test your cybersecurity knowledge and track your learning progress.
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


                    <li>
                        <a
                            href="register.php"
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
                            href="awareness.php"
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


    <script src="js/script.js"></script>

</body>

</html>