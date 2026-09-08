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

            <a href="#"
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


    <script src="js/script.js"></script>

</body>

</html>