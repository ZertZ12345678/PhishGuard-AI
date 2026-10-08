<?php

session_start();


// =====================================================
// GET TOKEN
// =====================================================

$token =
    $_GET["token"] ?? "";


// =====================================================
// GET ERROR
// =====================================================

$errorMessage =
    $_GET["error"] ?? "";


// =====================================================
// CHECK TOKEN
// =====================================================

$validToken = false;


if (
    $token !== ""
    &&
    isset(
        $_SESSION["password_reset"]
    )
) {

    $resetData =
        $_SESSION["password_reset"];


    if (
        isset($resetData["token"])
        &&
        isset($resetData["expires"])
        &&
        hash_equals(
            $resetData["token"],
            $token
        )
        &&
        time() < $resetData["expires"]
    ) {

        $validToken = true;
    }
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
        Reset Password - PhishGuard AI
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
                href="login.php"
                class="nav-link">

                Login

            </a>


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
         RESET PASSWORD
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

                Reset Password

            </h1>



            <?php if (!$validToken): ?>


                <div
                    class="
                        p-4
                        rounded-lg
                        bg-red-500/10
                        border
                        border-red-500/20
                        text-red-400
                        text-sm
                        mb-6
                    ">

                    ⚠️

                    This password reset link is
                    invalid or has expired.

                </div>


                <a
                    href="forgot_password.php"
                    class="
                        block
                        w-full
                        text-center
                        py-3
                        rounded-lg
                        bg-[var(--primary)]
                        text-white
                        no-underline
                    ">

                    Try Again

                </a>


            <?php else: ?>


                <p
                    class="
                        text-center
                        text-[var(--muted)]
                        mb-8
                    ">

                    Create a new password for your
                    PhishGuard AI account.

                </p>



                <!-- ERROR -->

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
                    action="../backend/php/reset_password_process.php"
                    method="POST"
                    id="reset-password-form">


                    <!-- TOKEN -->

                    <input
                        type="hidden"
                        name="token"
                        value="<?php

                                echo htmlspecialchars(
                                    $token,
                                    ENT_QUOTES,
                                    "UTF-8"
                                );

                                ?>">



                    <!-- =================================
                         NEW PASSWORD
                    ================================== -->

                    <div class="mb-5">

                        <label
                            for="new-password"
                            class="block mb-2">

                            New Password

                        </label>


                        <div class="relative">

                            <input
                                type="password"
                                name="password"
                                id="new-password"
                                required
                                minlength="8"
                                placeholder="Enter new password"
                                autocomplete="new-password"
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


                            <button
                                type="button"
                                id="toggle-new-password"
                                class="
                                    absolute
                                    right-3
                                    top-1/2
                                    -translate-y-1/2
                                    text-xl
                                    bg-transparent
                                    border-none
                                    cursor-pointer
                                    z-10
                                ">

                                👁

                            </button>

                        </div>


                        <p
                            class="
                                text-sm
                                text-[var(--muted)]
                                mt-2
                            ">

                            At least 8 characters, including
                            uppercase, lowercase, number and
                            special character.

                        </p>

                    </div>



                    <!-- =================================
                         CONFIRM PASSWORD
                    ================================== -->

                    <div class="mb-5">

                        <label
                            for="confirm-password"
                            class="block mb-2">

                            Confirm New Password

                        </label>


                        <div class="relative">

                            <input
                                type="password"
                                name="confirm_password"
                                id="confirm-password"
                                required
                                minlength="8"
                                placeholder="Confirm new password"
                                autocomplete="new-password"
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


                            <button
                                type="button"
                                id="toggle-confirm-password"
                                class="
                                    absolute
                                    right-3
                                    top-1/2
                                    -translate-y-1/2
                                    text-xl
                                    bg-transparent
                                    border-none
                                    cursor-pointer
                                    z-10
                                ">

                                👁

                            </button>

                        </div>

                    </div>



                    <!-- MESSAGE -->

                    <p
                        id="password-message"
                        class="
                            text-red-500
                            text-sm
                            mb-4
                        ">
                    </p>



                    <!-- BUTTON -->

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

                        Reset Password

                    </button>


                </form>


            <?php endif; ?>



            <!-- LOGIN -->

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
                    ">

                    Back to Login

                </a>

            </p>


        </div>

    </section>



    <script src="js/script.js"></script>


    <!-- =====================================
         PASSWORD JAVASCRIPT
    ====================================== -->

    <script>
        document.addEventListener(
            "DOMContentLoaded",
            function() {


                // =================================
                // NEW PASSWORD SHOW / HIDE
                // =================================

                const newPassword =
                    document.getElementById(
                        "new-password"
                    );


                const toggleNewPassword =
                    document.getElementById(
                        "toggle-new-password"
                    );


                if (
                    newPassword &&
                    toggleNewPassword
                ) {

                    toggleNewPassword.addEventListener(
                        "click",
                        function() {

                            if (
                                newPassword.type ===
                                "password"
                            ) {

                                newPassword.type =
                                    "text";

                                toggleNewPassword.textContent =
                                    "🙈";

                            } else {

                                newPassword.type =
                                    "password";

                                toggleNewPassword.textContent =
                                    "👁";

                            }

                        }
                    );

                }



                // =================================
                // CONFIRM PASSWORD SHOW / HIDE
                // =================================

                const confirmPassword =
                    document.getElementById(
                        "confirm-password"
                    );


                const toggleConfirmPassword =
                    document.getElementById(
                        "toggle-confirm-password"
                    );


                if (
                    confirmPassword &&
                    toggleConfirmPassword
                ) {

                    toggleConfirmPassword.addEventListener(
                        "click",
                        function() {

                            if (
                                confirmPassword.type ===
                                "password"
                            ) {

                                confirmPassword.type =
                                    "text";

                                toggleConfirmPassword.textContent =
                                    "🙈";

                            } else {

                                confirmPassword.type =
                                    "password";

                                toggleConfirmPassword.textContent =
                                    "👁";

                            }

                        }
                    );

                }



                // =================================
                // PASSWORD VALIDATION
                // =================================

                const resetForm =
                    document.getElementById(
                        "reset-password-form"
                    );


                if (resetForm) {

                    resetForm.addEventListener(
                        "submit",
                        function(event) {

                            const password =
                                newPassword.value;


                            const confirmPasswordValue =
                                confirmPassword.value;


                            const message =
                                document.getElementById(
                                    "password-message"
                                );



                            // -------------------------
                            // LENGTH
                            // -------------------------

                            if (
                                password.length < 8
                            ) {

                                event.preventDefault();

                                message.textContent =
                                    "Password must be at least 8 characters.";

                                return;

                            }



                            // -------------------------
                            // UPPERCASE
                            // -------------------------

                            if (
                                !/[A-Z]/.test(password)
                            ) {

                                event.preventDefault();

                                message.textContent =
                                    "Password must contain at least one uppercase letter.";

                                return;

                            }



                            // -------------------------
                            // LOWERCASE
                            // -------------------------

                            if (
                                !/[a-z]/.test(password)
                            ) {

                                event.preventDefault();

                                message.textContent =
                                    "Password must contain at least one lowercase letter.";

                                return;

                            }



                            // -------------------------
                            // NUMBER
                            // -------------------------

                            if (
                                !/[0-9]/.test(password)
                            ) {

                                event.preventDefault();

                                message.textContent =
                                    "Password must contain at least one number.";

                                return;

                            }



                            // -------------------------
                            // SPECIAL CHARACTER
                            // -------------------------

                            if (
                                !/[^A-Za-z0-9]/.test(password)
                            ) {

                                event.preventDefault();

                                message.textContent =
                                    "Password must contain at least one special character.";

                                return;

                            }



                            // -------------------------
                            // MATCH
                            // -------------------------

                            if (
                                password !==
                                confirmPasswordValue
                            ) {

                                event.preventDefault();

                                message.textContent =
                                    "Passwords do not match.";

                                return;

                            }


                            message.textContent = "";

                        }
                    );

                }

            }

        );
    </script>


</body>

</html>