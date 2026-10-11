<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - PhishGuard AI</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        #register-menu-button {
            display: none;
        }

        @media (max-width: 767px) {
            .register-header {
                padding: 16px !important;
                position: relative;
                z-index: 20;
            }

            .register-logo {
                font-size: 1.15rem !important;
                white-space: nowrap;
            }

            #register-menu-button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
                flex-shrink: 0;
                border-radius: 8px;
                background: var(--primary);
                color: #fff;
                border: 0;
                cursor: pointer;
            }

            #register-nav {
                display: none !important;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: var(--nav);
                padding: 16px 20px 20px;
                box-shadow: 0 10px 20px rgba(0, 0, 0, .2);
                flex-direction: column;
                align-items: flex-start !important;
                text-align: left !important;
                gap: 16px;
                z-index: 25;
            }

            #register-nav.is-open {
                display: flex !important;
            }

            #register-nav a {
                align-self: flex-start;
                text-align: left !important;
            }

            .register-section {
                padding: 40px 16px !important;
                align-items: flex-start;
            }

            .register-card {
                padding: 26px 20px !important;
            }

            .register-title {
                font-size: 1.65rem !important;
            }
        }

        @media (max-width: 360px) {
            .register-logo {
                font-size: 1rem !important;
            }
        }
    </style>
</head>

<body class="bg-[var(--bg)] text-[var(--text)] transition duration-300">
    <header class="register-header flex justify-between items-center px-10 py-5 bg-[var(--nav)]">
        <a href="index.php" class="register-logo text-2xl font-bold text-[var(--secondary)]">🛡 PhishGuard AI</a>
        <button type="button" id="register-menu-button" aria-label="Open navigation" aria-controls="register-nav" aria-expanded="false">☰</button>
        <nav id="register-nav" class="flex items-center gap-6">
            <a href="index.php" class="nav-link">Home</a>
            <a href="login.php" class="nav-link">Login</a>
            <button id="theme-toggle" type="button" class="text-2xl bg-transparent cursor-pointer" aria-label="Toggle theme">☀</button>
        </nav>
    </header>
    <section class="register-section flex justify-center items-center px-5 py-20">
        <div class="register-card bg-[var(--card)] p-10 rounded-xl shadow-lg w-full max-w-md">
            <h1 class="register-title text-3xl font-bold text-center text-[var(--secondary)] mb-8">Create Account</h1>
            <form id="register-form" action="../backend/php/register_process.php" method="POST">
                <div class="mb-5">
                    <label for="username" class="block mb-2">Username</label>
                    <input type="text" name="username" id="username" minlength="3" required placeholder="Enter username" class="w-full px-4 py-3 rounded-lg bg-[var(--bg)] text-[var(--text)] outline-none">
                </div>
                <div class="mb-5">
                    <label for="email" class="block mb-2">Email</label>
                    <input type="email" name="email" id="email" required placeholder="example@gmail.com" class="w-full px-4 py-3 rounded-lg bg-[var(--bg)] text-[var(--text)] outline-none">
                </div>
                <div class="mb-5">
                    <label for="password" class="block mb-2">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required placeholder="Enter password" class="w-full px-4 py-3 rounded-lg bg-[var(--bg)] text-[var(--text)] outline-none pr-12">
                        <button type="button" id="toggle-password" class="absolute right-3 top-3 text-xl" aria-label="Show password">👁</button>
                    </div>
                </div>
                <div class="mb-5">
                    <label for="confirm_password" class="block mb-2">Confirm Password</label>
                    <div class="relative">
                        <input type="password" name="confirm_password" id="confirm_password" required placeholder="Confirm password" class="w-full px-4 py-3 rounded-lg bg-[var(--bg)] text-[var(--text)] outline-none pr-12">
                        <button type="button" id="toggle-confirm-password" class="absolute right-3 top-3 text-xl" aria-label="Show confirm password">👁</button>
                    </div>
                </div>
                <p id="password-message" class="text-red-500 text-sm mb-4"></p>
                <button type="submit" class="w-full py-3 rounded-lg bg-[var(--primary)] text-white hover:bg-[var(--secondary)] transition">Create Account</button>
            </form>
            <p class="text-center mt-5">Already have an account? <a href="login.php" class="text-[var(--secondary)]">Login</a></p>
        </div>
    </section>
    <script src="js/script.js"></script>
    <script>
        (function() {
            const button = document.getElementById('register-menu-button');
            const nav = document.getElementById('register-nav');

            function setOpen(open) {
                nav.classList.toggle('is-open', open);
                button.setAttribute('aria-expanded', String(open));
                button.setAttribute('aria-label', open ? 'Close navigation' : 'Open navigation');
                button.textContent = open ? '×' : '☰';
            }
            button.addEventListener('click', function() {
                setOpen(!nav.classList.contains('is-open'));
            });
            nav.querySelectorAll('a').forEach(function(link) {
                link.addEventListener('click', function() {
                    setOpen(false);
                });
            });
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') setOpen(false);
            });
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.register-header')) setOpen(false);
            });
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) setOpen(false);
            });
        })();
    </script>
</body>

</html>