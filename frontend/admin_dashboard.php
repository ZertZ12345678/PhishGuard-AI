<?php
session_start();
// ADMIN ACCESS CHECK
if (!isset($_SESSION['Role']) || $_SESSION['Role'] != 'Admin') {
    header('Location: login.php');
    exit();
}
include '../backend/php/db_connection.php';
// STATISTICS
$userQuery = 'SELECT COUNT(*) AS total FROM users';
$userResult = $conn->query($userQuery);
$totalUsers = $userResult->fetch_assoc()['total'];
$quizQuery = 'SELECT COUNT(*) AS total FROM quiz_questions';
$quizResult = $conn->query($quizQuery);
$totalQuiz = $quizResult->fetch_assoc()['total'];
$detectionQuery = 'SELECT COUNT(*) AS total FROM url_history';
$detectionResult = $conn->query($detectionQuery);
$totalDetection = $detectionResult->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PhishGuard AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/style.css">
    <style>
        #admin-sidebar {
            z-index: 70;
            overflow-y: auto;
        }

        #admin-menu-button {
            display: none;
        }

        #admin-overlay {
            display: none;
        }

        @media (max-width: 1023px) {
            #admin-sidebar {
                transform: translateX(-100%);
                transition: transform .25s ease;
                width: 18rem;
                max-width: 85vw;
            }

            #admin-sidebar.menu-open {
                transform: translateX(0);
            }

            #admin-menu-button {
                display: inline-flex;
            }

            #admin-overlay.menu-open {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, .55);
                z-index: 60;
            }

            #admin-main {
                margin-left: 0 !important;
                padding: 1.25rem !important;
            }
        }

        @media (max-width: 639px) {
            #admin-main {
                padding: 1rem !important;
            }

            #admin-main h1 {
                font-size: 1.75rem;
                line-height: 2.2rem;
            }

            .admin-card {
                padding: 1.5rem !important;
            }
        }

        #admin-sidebar nav a {
            text-align: left;
        }
    </style>
</head>

<body class="bg-[var(--bg)] text-[var(--text)] transition duration-300">
    <div id="admin-overlay" aria-hidden="true"></div>
    <aside id="admin-sidebar" class="fixed left-0 top-0 h-screen w-72 bg-[var(--nav)] px-6 py-8 shadow-xl flex flex-col">
        <div class="flex items-center justify-between gap-2">
            <a href="admin_dashboard.php" class="text-2xl font-bold text-[var(--secondary)] no-underline">🛡 PhishGuard AI</a>
            <button type="button" id="admin-menu-close" class="lg:hidden text-2xl px-2" aria-label="Close menu">×</button>
        </div>
        <nav id="admin-navigation" class="mt-10 space-y-3 flex-1" aria-label="Admin navigation">
            <a href="admin_dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-[var(--primary)] text-white no-underline">📊 Dashboard</a>
            <a href="quiz_management.php" class="flex items-center gap-3 px-4 py-3 rounded-lg nav-link no-underline">📝 Quiz Management</a>
            <a href="show_user.php" class="flex items-center gap-3 px-4 py-3 rounded-lg nav-link no-underline">👥 Users</a>
            <a href="quiz_results.php" class="flex items-center gap-3 px-4 py-3 rounded-lg nav-link no-underline">🏆 Quiz Results</a>
            <a href="add_admin.php" class="flex items-center gap-3 px-4 py-3 rounded-lg nav-link no-underline">👑 Add New Admin</a>
            <a href="detection_history.php" class="flex items-center gap-3 px-4 py-3 rounded-lg nav-link no-underline">🔍 Detection History</a>
            <a href="admin_profile.php" class="flex items-center gap-3 px-4 py-3 rounded-lg nav-link no-underline">👤 Edit Profile</a>
        </nav>
        <div class="mt-8 space-y-4">
            <button id="theme-toggle" type="button" class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-lg bg-blue-500/10 text-[var(--text)] hover:bg-blue-500/20 transition border-none cursor-pointer"><span class="text-xl">☀</span><span class="font-semibold">Light Mode</span></button>
            <a href="logout.php" class="block text-center bg-red-500/10 text-red-400 py-3 rounded-lg no-underline hover:bg-red-500/20 transition">🚪 Logout</a>
        </div>
    </aside>
    <main id="admin-main" class="ml-72 p-10 min-h-screen">
        <div class="flex items-center gap-3 mb-6 lg:hidden">
            <button id="admin-menu-button" type="button" aria-label="Open menu" aria-controls="admin-sidebar" aria-expanded="false" class="items-center justify-center w-11 h-11 rounded-lg bg-[var(--nav)] text-[var(--text)] text-2xl shadow cursor-pointer">☰</button>
            <span class="font-semibold text-[var(--secondary)]">Admin Menu</span>
        </div>
        <h1 class="text-4xl font-bold text-[var(--secondary)]">Welcome, Admin 👋</h1>
        <p class="mt-3 text-[var(--muted)]">Manage PhishGuard AI system functions from here.</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8 mt-12">
            <div class="admin-card bg-[var(--card)] p-8 rounded-xl shadow">
                <h2 class="text-xl font-bold">👥 Total Users</h2>
                <p class="text-5xl font-bold text-[var(--primary)] mt-5"><?php echo $totalUsers; ?></p>
            </div>
            <div class="admin-card bg-[var(--card)] p-8 rounded-xl shadow">
                <h2 class="text-xl font-bold">📝 Quiz Questions</h2>
                <p class="text-5xl font-bold text-[var(--primary)] mt-5"><?php echo $totalQuiz; ?></p>
            </div>
            <div class="admin-card bg-[var(--card)] p-8 rounded-xl shadow">
                <h2 class="text-xl font-bold">🔍 Detection Records</h2>
                <p class="text-5xl font-bold text-[var(--primary)] mt-5"><?php echo $totalDetection; ?></p>
            </div>
        </div>
        <h2 class="text-2xl font-bold text-[var(--secondary)] mt-14">Quick Management</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8 mt-8">
            <a href="quiz_management.php" class="admin-card bg-[var(--card)] p-6 rounded-xl shadow no-underline hover:-translate-y-1 transition">
                <h3 class="text-xl font-bold">📝 Manage Quiz</h3>
                <p class="mt-3 text-[var(--muted)]">Add, edit and delete quiz questions.</p>
            </a>
            <a href="user_management.php" class="admin-card bg-[var(--card)] p-6 rounded-xl shadow no-underline hover:-translate-y-1 transition">
                <h3 class="text-xl font-bold">👥 Manage Users</h3>
                <p class="mt-3 text-[var(--muted)]">View and manage registered users.</p>
            </a>
            <a href="add_admin.php" class="admin-card bg-[var(--card)] p-6 rounded-xl shadow no-underline hover:-translate-y-1 transition">
                <h3 class="text-xl font-bold">👑 Add New Admin</h3>
                <p class="mt-3 text-[var(--muted)]">Create another administrator account.</p>
            </a>
        </div>
    </main>
    <script src="js/script.js"></script>
    <script>
        (function() {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('admin-overlay');
            const openButton = document.getElementById('admin-menu-button');
            const closeButton = document.getElementById('admin-menu-close');

            function setMenu(open) {
                sidebar.classList.toggle('menu-open', open);
                overlay.classList.toggle('menu-open', open);
                openButton.setAttribute('aria-expanded', String(open));
                document.body.style.overflow = open ? 'hidden' : '';
            }
            openButton.addEventListener('click', () => setMenu(true));
            closeButton.addEventListener('click', () => setMenu(false));
            overlay.addEventListener('click', () => setMenu(false));
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') setMenu(false);
            });
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) setMenu(false);
            });
        })();
    </script>
</body>

</html>