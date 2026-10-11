<?php
$errorMessage = $_GET["error"] ?? "";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - PhishGuard AI</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .forgot-header {
            position: relative;
        }

        .forgot-menu-button {
            display: none;
            background: #3b82f6;
            color: #fff;
            border: 0;
            border-radius: 8px;
            width: 40px;
            height: 40px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 21px;
        }

        .forgot-nav {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        @media (max-width: 1023px) {
            .forgot-header {
                padding: 16px 20px !important;
                flex-wrap: wrap;
                gap: 12px;
            }

            .forgot-brand {
                font-size: 1.2rem !important;
                min-width: 0;
            }

            .forgot-menu-button {
                display: inline-flex;
                margin-left: auto;
            }

            .forgot-nav {
                display: none !important;
                width: 100%;
                flex-basis: 100%;
                flex-direction: column;
                align-items: flex-start !important;
                text-align: left !important;
                gap: 0;
                padding: 10px 0 4px;
                border-top: 1px solid rgba(128, 128, 128, .2);
            }

            .forgot-nav.is-open {
                display: flex !important;
            }

            .forgot-nav a,
            .forgot-nav button {
                align-self: flex-start !important;
                text-align: left !important;
                padding: 12px 8px;
                width: 100%;
            }

            .forgot-nav button {
                width: auto;
            }

            .forgot-section {
                padding: 48px 16px !important;
            }

            .forgot-card {
                padding: 28px 22px !important;
            }
        }

        @media (max-width: 380px) {
            .forgot-brand {
                font-size: 1rem !important;
            }

            .forgot-card {
                padding: 24px 16px !important;
            }

            .forgot-card h1 {
                font-size: 1.55rem !important;
            }
        }
    </style>
</head>

<body class="bg-[var(--bg)] text-[var(--text)] transition duration-300">
    <header class="forgot-header flex justify-between items-center px-10 py-5 bg-[var(--nav)]">
        <a href="index.php" class="forgot-brand text-2xl font-bold text-[var(--secondary)] no-underline">🛡 PhishGuard AI</a>
        <button type="button" id="forgot-menu-button" class="forgot-menu-button" aria-label="Open navigation menu" aria-expanded="false" aria-controls="forgot-nav">☰</button>
        <nav id="forgot-nav" class="forgot-nav">
            <a href="index.php" class="nav-link">Home</a>
            <a href="register.php" class="nav-link">Register</a>
            <button id="theme-toggle" type="button" class="text-2xl bg-transparent border-none cursor-pointer" aria-label="Toggle theme">☀</button>
        </nav>
    </header>
    <section class="forgot-section flex justify-center items-center px-5 py-20">
        <div class="forgot-card bg-[var(--card)] p-10 rounded-xl shadow-lg w-full max-w-md">
            <h1 class="text-3xl font-bold text-center text-[var(--secondary)] mb-4">Forgot Password?</h1>
            <p class="text-center text-[var(--muted)] mb-8">Enter your registered email address to reset your password.</p>
            <?php if ($errorMessage !== ""): ?>
                <div class="mb-6 p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-sm">⚠️ <?php echo htmlspecialchars($errorMessage, ENT_QUOTES, "UTF-8"); ?></div>
            <?php endif; ?>
            <form action="../backend/php/forgot_password_process.php" method="POST">
                <div class="mb-6">
                    <label for="email" class="block mb-2">Email</label>
                    <input type="email" name="email" id="email" required placeholder="example@gmail.com" autocomplete="email" class="w-full px-4 py-3 rounded-lg bg-[var(--bg)] text-[var(--text)] outline-none">
                </div>
                <button type="submit" class="w-full py-3 rounded-lg bg-[var(--primary)] text-white hover:bg-[var(--secondary)] transition">Send Reset Link</button>
            </form>
            <p class="text-center mt-6">Remember your password? <a href="login.php" class="text-[var(--secondary)] no-underline hover:underline">Back to Login</a></p>
        </div>
    </section>
    <script src="js/script.js"></script>
    <script>
        (() => {
            const button = document.getElementById('forgot-menu-button');
            const nav = document.getElementById('forgot-nav');
            const setOpen = (open) => {
                nav.classList.toggle('is-open', open);
                button.setAttribute('aria-expanded', String(open));
                button.setAttribute('aria-label', open ? 'Close navigation menu' : 'Open navigation menu');
                button.textContent = open ? '×' : '☰';
            };
            button.addEventListener('click', () => setOpen(!nav.classList.contains('is-open')));
            nav.querySelectorAll('a').forEach(link => link.addEventListener('click', () => setOpen(false)));
            document.addEventListener('click', (event) => {
                if (!nav.contains(event.target) && !button.contains(event.target)) setOpen(false);
            });
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') setOpen(false);
            });
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) setOpen(false);
            });
        })();
    </script>
</body>

</html>