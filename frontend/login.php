<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PhishGuard AI</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            max-width: 100%;
            overflow-x: hidden;
        }

        #login-menu-button {
            display: none;
        }

        @media (max-width: 1023px) {
            .login-header {
                padding: 16px !important;
                position: relative;
                z-index: 30;
                gap: 12px;
            }

            .login-logo {
                font-size: 1.15rem !important;
                white-space: nowrap;
            }

            #login-menu-button {
                display: inline-flex !important;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
                flex-shrink: 0;
                border-radius: 8px;
                background: var(--primary, #3b82f6);
                color: #fff;
                border: 0;
                cursor: pointer;
                font-size: 22px;
            }

            #login-nav {
                display: none !important;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                width: 100%;
                background: var(--nav);
                padding: 16px 20px 20px;
                box-shadow: 0 10px 20px rgba(0, 0, 0, .2);
                flex-direction: column;
                align-items: flex-start !important;
                justify-content: flex-start !important;
                text-align: left !important;
                gap: 16px;
                z-index: 35;
            }

            #login-nav.is-open {
                display: flex !important;
            }

            #login-nav a {
                display: block;
                width: auto;
                align-self: flex-start;
                text-align: left !important;
            }

            #login-nav #theme-toggle {
                align-self: flex-start;
                margin: 0;
                padding: 0;
            }

            .login-section {
                padding: 40px 16px !important;
                align-items: flex-start;
            }

            .login-card {
                padding: 26px 20px !important;
            }

            .login-card h1 {
                font-size: 1.65rem !important;
            }
        }

        @media (max-width: 360px) {
            .login-logo {
                font-size: 1rem !important;
            }
        }
    </style>
</head>

<body class="bg-[var(--bg)] text-[var(--text)] transition duration-300">
    <header class="login-header flex justify-between items-center px-10 py-5 bg-[var(--nav)]">
        <a href="index.php" class="login-logo text-2xl font-bold text-[var(--secondary)] no-underline">🛡 PhishGuard AI</a>
        <button id="login-menu-button" type="button" class="login-menu-button" aria-label="Open navigation menu" aria-controls="login-nav" aria-expanded="false">☰</button>
        <nav id="login-nav" class="flex items-center gap-6" aria-label="Main navigation">
            <a href="index.php" class="nav-link">Home</a>
            <a href="register.php" class="nav-link">Register</a>
            <button id="theme-toggle" type="button" class="text-2xl bg-transparent border-none cursor-pointer">☀</button>
        </nav>
    </header>
    <section class="login-section flex justify-center items-center px-5 py-20">
        <div class="login-card bg-[var(--card)] p-10 rounded-xl shadow-lg w-full max-w-md">
            <h1 class="text-3xl font-bold text-center text-[var(--secondary)] mb-8">Login</h1>
            <form action="../backend/php/login_process.php" method="POST">
                <div class="mb-5">
                    <label for="email" class="block mb-2">Email</label>
                    <input type="email" name="email" id="email" required placeholder="example@gmail.com" autocomplete="email" class="w-full px-4 py-3 rounded-lg bg-[var(--bg)] text-[var(--text)] outline-none">
                </div>
                <div class="mb-2">
                    <label for="login-password" class="block mb-2">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="login-password" required placeholder="Enter password" autocomplete="current-password" class="w-full px-4 py-3 rounded-lg bg-[var(--bg)] text-[var(--text)] outline-none pr-12">
                        <button type="button" id="toggle-login-password" class="absolute right-3 top-3 text-xl bg-transparent border-none cursor-pointer" aria-label="Show password">👁</button>
                    </div>
                </div>
                <div class="text-right mb-5">
                    <a href="forgot_password.php" class="text-sm text-[var(--secondary)] no-underline hover:underline">Forgot Password?</a>
                </div>
                <p id="login-message" class="text-red-500 text-sm mb-4"></p>
                <button type="submit" class="w-full py-3 rounded-lg bg-[var(--primary)] text-white hover:bg-[var(--secondary)] transition">Login</button>
            </form>
            <p class="text-center mt-5">Don't have an account? <a href="register.php" class="text-[var(--secondary)]">Create Account</a></p>
        </div>
    </section>
    <script src="js/script.js"></script>
    <script>
        (function() {
            const button = document.getElementById('login-menu-button');
            const nav = document.getElementById('login-nav');
            if (!button || !nav) return;

            function setOpen(open) {
                nav.classList.toggle('is-open', open);
                button.textContent = open ? '×' : '☰';
                button.setAttribute('aria-expanded', String(open));
                button.setAttribute('aria-label', open ? 'Close navigation menu' : 'Open navigation menu');
            }
            button.addEventListener('click', () => setOpen(!nav.classList.contains('is-open')));
            nav.querySelectorAll('a').forEach(link => link.addEventListener('click', () => setOpen(false)));
            document.addEventListener('click', event => {
                if (!nav.contains(event.target) && !button.contains(event.target)) setOpen(false);
            });
            document.addEventListener('keydown', event => {
                if (event.key === 'Escape') setOpen(false);
            });
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) setOpen(false);
            });
        })();
    </script>
</body>

</html>