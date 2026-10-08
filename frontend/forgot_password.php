<?php

$errorMessage = $_GET["error"] ?? "";

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Forgot Password - PhishGuard AI
    </title>


    <link
        rel="stylesheet"
        href="css/style.css">

    <script src="https://cdn.tailwindcss.com"></script>

</head>


<body
    class="
        bg-[var(--bg)]
        text-[var(--text)]
        transition
        duration-300
    ">


    <!-- =====================================
         HEADER
    ====================================== -->

    <header
        class="
            flex
            justify-between
            items-center
            px-10
            py-5
            bg-[var(--nav)]
        ">

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


        <nav
            class="
                flex
                items-center
                gap-6
            ">

            <a
                href="index.php"
                class="nav-link">

                Home

            </a>


            <a
                href="register.php"
                class="nav-link">

                Register

            </a>


            <button
                id="theme-toggle"
                type="button"
                class="
                    text-2xl
                    bg-transparent
                    border-none
                    cursor-pointer
                "
                aria-label="Toggle theme">

                ☀

            </button>

        </nav>

    </header>



    <!-- =====================================
         FORGOT PASSWORD
    ====================================== -->

    <section
        class="
            flex
            justify-center
            items-center
            px-5
            py-20
        ">

        <div
            class="
                bg-[var(--card)]
                p-10
                rounded-xl
                shadow-lg
                w-full
                max-w-md
            ">

            <h1
                class="
                    text-3xl
                    font-bold
                    text-center
                    text-[var(--secondary)]
                    mb-4
                ">

                Forgot Password?

            </h1>


            <p
                class="
                    text-center
                    text-[var(--muted)]
                    mb-8
                ">

                Enter your registered email address
                to reset your password.

            </p>



            <!-- ERROR MESSAGE -->

            <?php if ($errorMessage !== ""): ?>

                <div
                    class="
                        mb-6
                        p-4
                        rounded-lg
                        bg-red-500/10
                        border
                        border-red-500/20
                        text-red-400
                        text-sm
                    ">

                    ⚠️

                    <?php

                    echo htmlspecialchars(
                        $errorMessage,
                        ENT_QUOTES,
                        "UTF-8"
                    );

                    ?>

                </div>

            <?php endif; ?>



            <!-- FORM -->

            <form
                action="../backend/php/forgot_password_process.php"
                method="POST">

                <div class="mb-6">

                    <label
                        for="email"
                        class="block mb-2">

                        Email

                    </label>


                    <input
                        type="email"
                        name="email"
                        id="email"
                        required
                        placeholder="example@gmail.com"
                        autocomplete="email"
                        class="
                            w-full
                            px-4
                            py-3
                            rounded-lg
                            bg-[var(--bg)]
                            text-[var(--text)]
                            outline-none
                        ">

                </div>


                <button
                    type="submit"
                    class="
                        w-full
                        py-3
                        rounded-lg
                        bg-[var(--primary)]
                        text-white
                        hover:bg-[var(--secondary)]
                        transition
                    ">

                    Send Reset Link

                </button>

            </form>



            <p
                class="
                    text-center
                    mt-6
                ">

                Remember your password?

                <a
                    href="login.php"
                    class="
                        text-[var(--secondary)]
                        no-underline
                        hover:underline
                    ">

                    Back to Login

                </a>

            </p>

        </div>

    </section>



    <script src="js/script.js"></script>


</body>

</html>