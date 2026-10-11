<?php
session_start();
if (!isset($_SESSION['Role']) || $_SESSION['Role'] !== 'Admin') {
    header('Location: login.php');
    exit();
}
include '../backend/php/db_connection.php';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($search !== '') {
    $sql = 'SELECT url_history.*, users.Username FROM url_history LEFT JOIN users ON url_history.UserID = users.UserID WHERE url_history.URL LIKE ? OR url_history.PredictionResult LIKE ? OR users.Username LIKE ? ORDER BY url_history.HistoryID DESC';
    $stmt = $conn->prepare($sql);
    $keyword = '%' . $search . '%';
    $stmt->bind_param('sss', $keyword, $keyword, $keyword);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $sql = 'SELECT url_history.*, users.Username FROM url_history LEFT JOIN users ON url_history.UserID = users.UserID ORDER BY url_history.HistoryID DESC';
    $result = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detection History - PhishGuard AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Keep the desktop layout unchanged; switch to a drawer below 1024px. */
        .dh-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100dvh;
            width: 18rem;
            background: var(--nav);
            padding: 2rem 1.5rem;
            box-shadow: 0 10px 20px rgba(0, 0, 0, .12);
            z-index: 60;
            display: flex;
            flex-direction: column;
        }

        .dh-nav {
            margin-top: 2.5rem;
            display: flex;
            flex-direction: column;
            gap: .75rem;
            min-height: 0;
            overflow-y: auto;
        }

        .dh-nav a {
            display: block;
            padding: .75rem 1rem;
            border-radius: .5rem;
            text-decoration: none;
            text-align: left;
            flex-shrink: 0;
        }

        .dh-nav .dh-active {
            background: var(--primary);
            color: #fff;
        }

        .dh-bottom {
            margin-top: auto;
            padding-top: 1rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .dh-main {
            margin-left: 18rem;
            padding: 2.5rem;
            min-width: 0;
        }

        .dh-menu-button,
        .dh-close {
            display: none;
        }

        .dh-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .65);
            z-index: 50;
        }

        .dh-table-scroll {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .dh-table {
            width: 100%;
            min-width: 900px;
            text-align: left;
        }

        .dh-table th,
        .dh-table td {
            padding: 1.25rem;
        }

        .dh-url {
            max-width: 24rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        @media(max-width:1023px) {
            .dh-sidebar {
                transform: translateX(-105%);
                transition: transform .25s ease;
                width: min(18rem, 85vw);
            }

            .dh-sidebar.dh-open {
                transform: translateX(0);
            }

            .dh-overlay.dh-open {
                display: block;
            }

            .dh-main {
                margin-left: 0;
                padding: 1.5rem 1.25rem;
            }

            .dh-menu-button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                width: 2.5rem;
                height: 2.5rem;
                background: var(--primary);
                color: #fff;
                border: 0;
                border-radius: .5rem;
                cursor: pointer;
                font-size: 1.25rem;
            }

            .dh-close {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border: 0;
                background: transparent;
                color: var(--text);
                font-size: 1.4rem;
                cursor: pointer;
                margin-left: auto;
            }
        }

        @media(max-width:639px) {
            .dh-main {
                padding: 1.25rem .85rem;
            }

            .dh-title {
                font-size: 1.65rem !important;
                line-height: 1.2 !important;
            }

            .dh-search {
                flex-direction: column;
                align-items: stretch;
            }

            .dh-search input {
                width: 100% !important;
                min-width: 0;
            }

            .dh-search button,
            .dh-search a {
                width: 100%;
                text-align: center;
            }

            .dh-table th,
            .dh-table td {
                padding: .85rem;
            }
        }
    </style>
</head>

<body class="bg-[var(--bg)] text-[var(--text)] transition duration-300">
    <div id="dhOverlay" class="dh-overlay" aria-hidden="true"></div>
    <aside id="dhSidebar" class="dh-sidebar" aria-label="Admin navigation">
        <div class="flex items-start gap-2">
            <a href="admin_dashboard.php" class="text-2xl font-bold text-[var(--secondary)] no-underline leading-tight">🛡 PhishGuard AI</a>
            <button id="dhClose" class="dh-close" type="button" aria-label="Close navigation menu">×</button>
        </div>
        <nav class="dh-nav">
            <a href="admin_dashboard.php" class="nav-link">📊 Dashboard</a>
            <a href="quiz_management.php" class="nav-link">📝 Quiz Management</a>
            <a href="show_user.php" class="nav-link">👥 Users</a>
            <a href="quiz_results.php" class="nav-link">🏆 Quiz Results</a>
            <a href="add_admin.php" class="nav-link">👑 Add New Admin</a>
            <a href="detection_history.php" class="dh-active" aria-current="page">🔍 Detection History</a>
            <a href="admin_profile.php" class="nav-link">👤 Edit Profile</a>
        </nav>
        <div class="dh-bottom">
            <button id="theme-toggle" type="button" class="w-full py-3 rounded-lg bg-blue-500/10 border-none cursor-pointer text-[var(--text)]">☀ Light Mode</button>
            <a href="logout.php" class="block text-center bg-red-500/10 text-red-400 py-3 rounded-lg no-underline">🚪 Logout</a>
        </div>
    </aside>
    <main class="dh-main min-h-screen">
        <header class="flex items-center gap-3">
            <button id="dhMenu" type="button" class="dh-menu-button" aria-label="Open navigation menu" aria-controls="dhSidebar" aria-expanded="false">☰</button>
            <h1 class="dh-title text-4xl font-bold text-[var(--secondary)]">🔍 Detection History</h1>
        </header>
        <p class="mt-3 text-[var(--muted)]">View all URL detection activities.</p>
        <form method="GET" class="dh-search mt-8 flex gap-3">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Search username, URL or result" class="w-96 max-w-full px-5 py-3 rounded-lg bg-[var(--card)] text-[var(--text)] outline-none">
            <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg">Search</button>
            <?php if ($search !== ''): ?><a href="detection_history.php" class="px-6 py-3 rounded-lg bg-[var(--card)] text-[var(--text)] no-underline">Clear</a><?php endif; ?>
        </form>
        <div class="mt-10 bg-[var(--card)] rounded-xl shadow dh-table-scroll">
            <table class="dh-table">
                <thead class="bg-black/10">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>URL</th>
                        <th>Prediction</th>
                        <th>Confidence</th>
                        <th>Checked Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="border-t border-gray-500/20">
                            <td><?php echo htmlspecialchars((string)$row['HistoryID'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="font-semibold"><?php echo htmlspecialchars($row['Username'] ?? 'Unknown', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td class="dh-url" title="<?php echo htmlspecialchars($row['URL'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($row['URL'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php if ($row['PredictionResult'] === 'Safe'): ?><span class="text-green-400 font-bold">✓ Safe</span><?php else: ?><span class="text-red-400 font-bold">⚠ Phishing</span><?php endif; ?></td>
                            <td><?php echo htmlspecialchars((string)$row['ConfidenceScore'], ENT_QUOTES, 'UTF-8'); ?>%</td>
                            <td><?php echo htmlspecialchars((string)$row['CheckedDate'], ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
    <script src="js/script.js"></script>
    <script>
        (function() {
            const sidebar = document.getElementById('dhSidebar');
            const overlay = document.getElementById('dhOverlay');
            const menu = document.getElementById('dhMenu');
            const close = document.getElementById('dhClose');

            function setOpen(open) {
                sidebar.classList.toggle('dh-open', open);
                overlay.classList.toggle('dh-open', open);
                menu.setAttribute('aria-expanded', String(open));
                menu.setAttribute('aria-label', open ? 'Close navigation menu' : 'Open navigation menu');
                document.body.style.overflow = open ? 'hidden' : '';
            }
            menu.addEventListener('click', () => setOpen(!sidebar.classList.contains('dh-open')));
            close.addEventListener('click', () => setOpen(false));
            overlay.addEventListener('click', () => setOpen(false));
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') setOpen(false);
            });
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) setOpen(false);
            });
            sidebar.querySelectorAll('nav a').forEach(link => link.addEventListener('click', () => setOpen(false)));
        })();
    </script>
</body>

</html>