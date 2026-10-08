<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Login - PhishGuard AI
    </title>


    <link
        rel="stylesheet"
        href="css/style.css">


    <script
        src="https://cdn.tailwindcss.com">
    </script>

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


        <!-- LOGO -->

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



        <!-- NAVIGATION -->

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


            <!-- THEME TOGGLE -->

            <button
                id="theme-toggle"
                type="button"
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
         LOGIN SECTION
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


            <!-- TITLE -->

            <h1
                class="
                    text-3xl
                    font-bold
                    text-center
                    text-[var(--secondary)]
                    mb-8
                ">

                Login

            </h1>



            <!-- =================================
                 LOGIN FORM
            ================================== -->

            <form
                action="../backend/php/login_process.php"
                method="POST">


                <!-- EMAIL -->

                <div class="mb-5">

                    <label
                        for="email"
                        class="
                            block
                            mb-2
                        ">

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



                <!-- PASSWORD -->

                <div class="mb-2">

                    <label
                        for="login-password"
                        class="
                            block
                            mb-2
                        ">

                        Password

                    </label>


                    <div class="relative">

                        <input
                            type="password"
                            name="password"
                            id="login-password"
                            required
                            placeholder="Enter password"
                            autocomplete="current-password"
                            class="
                                w-full
                                px-4
                                py-3
                                rounded-lg
                                bg-[var(--bg)]
                                text-[var(--text)]
                                outline-none
                                pr-12
                            ">


                        <!-- SHOW / HIDE PASSWORD -->

                        <button
                            type="button"
                            id="toggle-login-password"
                            class="
                                absolute
                                right-3
                                top-3
                                text-xl
                                bg-transparent
                                border-none
                                cursor-pointer
                            "
                            aria-label="Show password">

                            👁

                        </button>

                    </div>

                </div>



                <!-- =================================
                     FORGOT PASSWORD
                ================================== -->

                <div
                    class="
                        text-right
                        mb-5
                    ">

                    <a
                        href="forgot_password.php"
                        class="
                            text-sm
                            text-[var(--secondary)]
                            no-underline
                            hover:underline
                        ">

                        Forgot Password?

                    </a>

                </div>



                <!-- LOGIN MESSAGE -->

                <p
                    id="login-message"
                    class="
                        text-red-500
                        text-sm
                        mb-4
                    ">
                </p>



                <!-- LOGIN BUTTON -->

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

                    Login

                </button>


            </form>



            <!-- =================================
                 REGISTER LINK
            ================================== -->

            <p
                class="
                    text-center
                    mt-5
                ">

                Don't have an account?

                <a
                    href="register.php"
                    class="text-[var(--secondary)]">

                    Create Account

                </a>

            </p>


        </div>


    </section>



    <!-- =====================================
         JAVASCRIPT
    ====================================== -->

    <script src="js/script.js"></script>


</body>

</html>