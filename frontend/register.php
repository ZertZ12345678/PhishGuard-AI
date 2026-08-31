<!DOCTYPE html>
<html>

<head>
    <title>Register - PhishGuard AI</title>

    <link rel="stylesheet" href="css/style.css">

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[var(--bg)] text-[var(--text)] transition duration-300">

    <header class="flex justify-between items-center px-10 py-5 bg-[var(--nav)]">

        <a href="index.php"
            class="text-2xl font-bold text-[var(--secondary)]">
            🛡 PhishGuard AI
        </a>

        <nav class="flex items-center gap-6">

            <a href="index.php" class="nav-link">
                Home
            </a>

            <a href="login.php" class="nav-link">
                Login
            </a>

            <button id="theme-toggle"
                class="text-2xl bg-transparent cursor-pointer">
                ☀
            </button>

        </nav>

    </header>


    <section class="flex justify-center items-center px-5 py-20">

        <div class="bg-[var(--card)] p-10 rounded-xl shadow-lg w-full max-w-md">

            <h1 class="text-3xl font-bold text-center text-[var(--secondary)] mb-8">
                Create Account
            </h1>


            <form id="register-form"
                action="../backend/php/register_process.php"
                method="POST">


                <div class="mb-5">

                    <label class="block mb-2">
                        Username
                    </label>

                    <input
                        type="text"
                        name="username"
                        id="username"
                        minlength="3"
                        required
                        placeholder="Enter username"
                        class="w-full px-4 py-3 rounded-lg bg-[var(--bg)] text-[var(--text)] outline-none">

                </div>


                <div class="mb-5">

                    <label class="block mb-2">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        id="email"
                        required
                        placeholder="example@gmail.com"
                        class="w-full px-4 py-3 rounded-lg bg-[var(--bg)] text-[var(--text)] outline-none">

                </div>


                <div class="mb-5">

                    <label class="block mb-2">
                        Password
                    </label>

                    <div class="relative">

                        <input
                            type="password"
                            name="password"
                            id="password"
                            required
                            placeholder="Enter password"
                            class="w-full px-4 py-3 rounded-lg bg-[var(--bg)] text-[var(--text)] outline-none pr-12">

                        <button
                            type="button"
                            id="toggle-password"
                            class="absolute right-3 top-3 text-xl">
                            👁
                        </button>

                    </div>

                </div>


                <div class="mb-5">

                    <label class="block mb-2">
                        Confirm Password
                    </label>

                    <div class="relative">

                        <input
                            type="password"
                            name="confirm_password"
                            id="confirm_password"
                            required
                            placeholder="Confirm password"
                            class="w-full px-4 py-3 rounded-lg bg-[var(--bg)] text-[var(--text)] outline-none pr-12">

                        <button
                            type="button"
                            id="toggle-confirm-password"
                            class="absolute right-3 top-3 text-xl">
                            👁
                        </button>

                    </div>

                </div>


                <p id="password-message"
                    class="text-red-500 text-sm mb-4">
                </p>


                <button
                    type="submit"
                    class="w-full py-3 rounded-lg bg-[var(--primary)] text-white hover:bg-[var(--secondary)] transition">

                    Create Account

                </button>


            </form>


            <p class="text-center mt-5">

                Already have an account?

                <a href="login.php"
                    class="text-[var(--secondary)]">
                    Login
                </a>

            </p>


        </div>

    </section>


    <script src="js/script.js"></script>

</body>

</html>