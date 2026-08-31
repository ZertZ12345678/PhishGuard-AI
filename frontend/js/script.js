document.addEventListener("DOMContentLoaded", function () {

    // Theme System
    const themeButton = document.querySelector("#theme-toggle");

    function updateThemeIcon() {

        if (document.body.classList.contains("light-mode")) {

            themeButton.textContent = "🌑";

        } else {

            themeButton.textContent = "☀";
            themeButton.style.color = "#fbbf24";

        }

    }


    const savedTheme = localStorage.getItem("theme");

    if (savedTheme === "light") {
        document.body.classList.add("light-mode");
    }


    if (themeButton) {

        updateThemeIcon();


        themeButton.addEventListener("click", function () {

            document.body.classList.toggle("light-mode");


            if (document.body.classList.contains("light-mode")) {

                localStorage.setItem("theme", "light");

            } else {

                localStorage.setItem("theme", "dark");

            }


            updateThemeIcon();

        });

    }



    // Register Validation

    const registerForm = document.querySelector("#register-form");


    if (registerForm) {

        registerForm.addEventListener("submit", function (e) {

            const username = document.querySelector("#username").value.trim();
            const email = document.querySelector("#email").value.trim();
            const password = document.querySelector("#password").value;
            const confirmPassword = document.querySelector("#confirm_password").value;
            const message = document.querySelector("#password-message");


            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


            const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/;



            if (username.length < 3) {

                e.preventDefault();

                message.textContent =
                    "Username must contain at least 3 characters.";

                return;

            }



            if (!emailPattern.test(email)) {

                e.preventDefault();

                message.textContent =
                    "Please enter a valid email address.";

                return;

            }



            if (!passwordPattern.test(password)) {

                e.preventDefault();

                message.textContent =
                    "Password must contain 8+ characters, uppercase, lowercase, number and special character.";

                return;

            }



            if (password !== confirmPassword) {

                e.preventDefault();

                message.textContent =
                    "Passwords do not match.";

                return;

            }



            message.textContent = "";

        });

    }

});

// Show / Hide Password

const togglePassword = document.querySelector("#toggle-password");
const passwordInput = document.querySelector("#password");

if (togglePassword) {

    togglePassword.addEventListener("click", function () {

        if (passwordInput.type === "password") {

            passwordInput.type = "text";
            togglePassword.textContent = "🙈";

        } else {

            passwordInput.type = "password";
            togglePassword.textContent = "👁";

        }

    });

}



const toggleConfirmPassword = document.querySelector("#toggle-confirm-password");
const confirmPasswordInput = document.querySelector("#confirm_password");


if (toggleConfirmPassword) {

    toggleConfirmPassword.addEventListener("click", function () {

        if (confirmPasswordInput.type === "password") {

            confirmPasswordInput.type = "text";
            toggleConfirmPassword.textContent = "🙈";

        } else {

            confirmPasswordInput.type = "password";
            toggleConfirmPassword.textContent = "👁";

        }

    });

}

// Login password show/hide

const loginToggle = document.querySelector("#toggle-login-password");
const loginPassword = document.querySelector("#login-password");

if (loginToggle && loginPassword) {

    loginToggle.addEventListener("click", function () {

        if (loginPassword.type === "password") {

            loginPassword.type = "text";
            loginToggle.textContent = "🙈";

        } else {

            loginPassword.type = "password";
            loginToggle.textContent = "👁";

        }

    });

}