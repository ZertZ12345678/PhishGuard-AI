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

            <a href="awareness.php?source=user"
                class="nav-link">
                Awareness
            </a>

            <a href="take_quiz.php"
                class="nav-link">
                Quiz
            </a>

            <a href="user_dashboard.php"
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

    <!-- HOW IT WORKS -->


    <section
        class="
px-10
py-16
">


        <h2
            class="
text-3xl
font-bold
text-center
text-[var(--secondary)]
mb-10
">

            How PhishGuard AI Works

        </h2>





        <div
            class="
grid
md:grid-cols-3
gap-8
">





            <div
                class="
bg-[var(--card)]
p-8
rounded-xl
shadow
text-center
">

                <h3
                    class="
text-xl
font-bold
text-[var(--secondary)]
">

                    1. Detect

                </h3>


                <p class="mt-4">

                    Analyze suspicious URLs using
                    AI-powered phishing detection.

                </p>


            </div>







            <div
                class="
bg-[var(--card)]
p-8
rounded-xl
shadow
text-center
">

                <h3
                    class="
text-xl
font-bold
text-[var(--secondary)]
">

                    2. Learn

                </h3>


                <p class="mt-4">

                    Improve cybersecurity knowledge
                    through awareness materials.

                </p>


            </div>







            <div
                class="
bg-[var(--card)]
p-8
rounded-xl
shadow
text-center
">

                <h3
                    class="
text-xl
font-bold
text-[var(--secondary)]
">

                    3. Test

                </h3>


                <p class="mt-4">

                    Complete quizzes and evaluate
                    your security awareness.

                </p>


            </div>




        </div>


    </section>

    <!-- SECURITY TIPS -->


    <section
        class="
px-10
py-16
">


        <div
            class="
max-w-6xl
mx-auto
">


            <h2
                class="
text-3xl
font-bold
text-center
text-[var(--secondary)]
mb-10
">

                Cybersecurity Tips

            </h2>





            <div
                class="
grid
md:grid-cols-2
gap-6
">





                <div
                    class="
bg-[var(--card)]
p-6
rounded-xl
">

                    🔐

                    <strong>
                        Use Strong Passwords
                    </strong>

                    <p class="mt-2">

                        Create unique passwords and avoid
                        sharing them with others.

                    </p>

                </div>







                <div
                    class="
bg-[var(--card)]
p-6
rounded-xl
">

                    📧

                    <strong>
                        Be Careful With Emails
                    </strong>


                    <p class="mt-2">

                        Avoid clicking suspicious links
                        from unknown senders.

                    </p>


                </div>








                <div
                    class="
bg-[var(--card)]
p-6
rounded-xl
">

                    🌐

                    <strong>
                        Check Website URLs
                    </strong>


                    <p class="mt-2">

                        Verify website addresses before
                        entering personal information.

                    </p>


                </div>








                <div
                    class="
bg-[var(--card)]
p-6
rounded-xl
">

                    🛡

                    <strong>
                        Keep Software Updated
                    </strong>


                    <p class="mt-2">

                        Regular updates improve security
                        and protect against threats.

                    </p>


                </div>





            </div>


        </div>


    </section>

    <!-- CTA -->


    <section
        class="
px-10
py-20
text-center
">


        <h2
            class="
text-3xl
font-bold
text-[var(--secondary)]
">

            Stay Safe Online With PhishGuard AI

        </h2>



        <p
            class="
mt-4
">

            Detect threats, learn cybersecurity,
            and improve your online protection.

        </p>



        <div
            class="
mt-8
flex
justify-center
gap-5
">


            <a
                href="detection.php"
                class="
px-8
py-3
bg-blue-600
text-white
rounded-lg
no-underline
">

                Check URL

            </a>



            <a
                href="take_quiz.php"
                class="
px-8
py-3
bg-green-600
text-white
rounded-lg
no-underline
">

                Take Quiz

            </a>



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
                        <a href="awareness.php?source=user"
                            class="nav-link">
                            Awareness
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

    <script src="js/script.js"></script>

</body>

</html>