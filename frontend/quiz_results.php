<?php

session_start();

// =====================================

// ADMIN ACCESS CHECK

// =====================================

if (

    !isset($_SESSION["Role"]) ||

    $_SESSION["Role"] != "Admin"

) {

    header("Location: login.php");

    exit();
}

include "../backend/php/db_connection.php";

// =====================================

// SEARCH BY USERNAME

// =====================================

$search = "";

if (isset($_GET["search"])) {

    $search = trim($_GET["search"]);
}

// =====================================

// GET QUIZ RESULTS

// =====================================

if ($search != "") {

    $sql = "

    SELECT

    quiz_results.*,

    users.Username

    FROM quiz_results

    LEFT JOIN users

    ON quiz_results.UserID = users.UserID

    WHERE users.Username LIKE ?

    ORDER BY quiz_results.ResultID DESC

    ";

    $stmt = $conn->prepare($sql);

    $keyword = "%" . $search . "%";

    $stmt->bind_param(

        "s",

        $keyword

    );

    $stmt->execute();

    $result = $stmt->get_result();
} else {

    $sql = "

    SELECT

    quiz_results.*,

    users.Username

    FROM quiz_results

    LEFT JOIN users

    ON quiz_results.UserID = users.UserID

    ORDER BY quiz_results.ResultID DESC

    ";

    $result = $conn->query($sql);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Results - PhishGuard AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/style.css">
    <style>
        #adminSidebar {
            z-index: 60;
        }

        #userTableWrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        #sidebarOverlay {
            display: none;
        }

        #menuButton,
        #sidebarClose {
            display: none;
        }

        @media (max-width: 1023px) {
            #adminSidebar {
                transform: translateX(-105%);
                transition: transform .25s ease;
                overflow-y: auto;
                max-width: min(18rem, 88vw);
            }

            #adminSidebar.is-open {
                transform: translateX(0);
            }

            #sidebarOverlay.is-open {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, .55);
                z-index: 55;
            }

            #menuButton {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 44px;
                height: 44px;
                flex: none;
                border-radius: .65rem;
                background: var(--primary);
                color: white;
                font-size: 1.5rem;
                cursor: pointer;
            }

            #sidebarClose {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 1.65rem;
                width: 36px;
                height: 36px;
                flex: none;
                cursor: pointer;
                color: var(--text);
            }

            #adminMain {
                margin-left: 0 !important;
                padding: 1.25rem !important;
            }

            #pageHeading {
                display: flex;
                align-items: center;
                gap: .8rem;
            }

            #pageHeading h1 {
                font-size: clamp(1.4rem, 5vw, 2.25rem);
            }

            #adminSidebar nav a {
                text-align: left !important;
            }

            #userTableWrap {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            #userTable {
                min-width: 790px;
            }

            #userTable th,
            #userTable td {
                padding: 1rem;
            }
        }

        @media (max-width: 639px) {
            #searchForm {
                flex-wrap: wrap;
            }

            #searchForm input {
                width: 100%;
                min-width: 0;
            }

            #searchForm button {
                width: 100%;
            }

            #searchForm a {
                width: 100%;
            }
        }

        @media (max-height: 680px) and (max-width: 1023px) {
            #adminSidebar .sidebar-bottom {
                position: static !important;
                margin-top: 2rem;
            }
        }
    </style>
</head>

<body class="bg-[var(--bg)] text-[var(--text)] transition duration-300">
    <div id="sidebarOverlay" aria-hidden="true"></div>
    <aside id="adminSidebar" class="fixed left-0 top-0 h-screen w-72 bg-[var(--nav)] px-6 py-8 shadow-xl" aria-label="Admin navigation">
        <div class="flex items-center justify-between gap-2">
            <a href="admin_dashboard.php" class="text-2xl font-bold text-[var(--secondary)] no-underline">🛡 PhishGuard AI</a>
            <button id="sidebarClose" type="button" aria-label="Close navigation menu">×</button>
        </div>
        <nav class="mt-10 space-y-3">
            <a href="admin_dashboard.php" class="block px-4 py-3 rounded-lg nav-link no-underline">📊 Dashboard</a>
            <a href="quiz_management.php" class="block px-4 py-3 rounded-lg nav-link no-underline">📝 Quiz Management</a>
            <a href="show_user.php" class="block px-4 py-3 rounded-lg nav-link no-underline">👥 Users</a>
            <a href="quiz_results.php" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-[var(--primary)] text-white no-underline">🏆 Quiz Results</a>
            <a href="add_admin.php" class="block px-4 py-3 rounded-lg nav-link no-underline">👑 Add New Admin</a>
            <a href="detection_history.php" class="block px-4 py-3 rounded-lg nav-link no-underline">🔍 Detection History</a>
            <a href="admin_profile.php" class="flex items-center gap-3 px-4 py-3 rounded-lg nav-link no-underline">👤 Edit Profile</a>
        </nav>
        <div class="sidebar-bottom absolute bottom-8 left-6 right-6 space-y-4">
            <button id="theme-toggle" type="button" class="w-full py-3 rounded-lg bg-blue-500/10 cursor-pointer border-none">☀ Light Mode</button>
            <a href="logout.php" class="block text-center bg-red-500/10 text-red-400 py-3 rounded-lg no-underline">🚪 Logout</a>
        </div>
    </aside>
    <main id="adminMain" class="ml-72 p-10">
        <div id="pageHeading">
            <button id="menuButton" type="button" aria-label="Open navigation menu" aria-controls="adminSidebar" aria-expanded="false">☰</button>
            <h1 class="text-4xl font-bold text-[var(--secondary)]">🏆 Quiz Results</h1>
        </div>
        <p class="mt-3 text-[var(--muted)]">View quiz results and performance of registered users.</p>
        <form id="searchForm" method="GET" class="mt-8 flex gap-3">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search username" class="px-5 py-3 rounded-lg bg-[var(--card)] outline-none w-96">
            <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg">Search</button>
            <?php if ($search != ""): ?>
                <a href="quiz_results.php" class="px-6 py-3 bg-gray-500/20 text-[var(--text)] rounded-lg no-underline flex items-center justify-center">Clear</a>
            <?php endif; ?>
        </form>
        <div id="userTableWrap" class="mt-10 bg-[var(--card)] rounded-xl shadow overflow-hidden">
            <table id="userTable" class="w-full text-left">
                <thead class="bg-black/10">
                    <tr>
                        <th class="p-5">ID</th>
                        <th class="p-5">Username</th>
                        <th class="p-5">Score</th>
                        <th class="p-5">Total Questions</th>
                        <th class="p-5">Percentage</th>
                        <th class="p-5">Attempt Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="border-t border-gray-500/20">
                                <td class="p-5"><?php echo $row["ResultID"]; ?></td>
                                <td class="p-5 font-semibold"><?php echo htmlspecialchars($row["Username"] ?? "Unknown"); ?></td>
                                <td class="p-5"><?php echo $row["Score"]; ?></td>
                                <td class="p-5"><?php echo $row["TotalQuestions"]; ?></td>
                                <td class="p-5"><?php echo number_format((float)$row["Percentage"], 2); ?>%</td>
                                <td class="p-5"><?php echo htmlspecialchars($row["AttemptDate"]); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="p-8 text-center text-[var(--muted)]">
                                <?php if ($search != ""): ?>
                                    No quiz results found for username: <?php echo htmlspecialchars($search); ?>
                                <?php else: ?>
                                    No quiz results available.
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
    <script src="js/script.js"></script>
    <script>
        (function() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const openButton = document.getElementById('menuButton');
            const closeButton = document.getElementById('sidebarClose');

            function setOpen(open) {
                sidebar.classList.toggle('is-open', open);
                overlay.classList.toggle('is-open', open);
                openButton.setAttribute('aria-expanded', String(open));
                document.body.style.overflow = open ? 'hidden' : '';
            }
            openButton.addEventListener('click', function() {
                setOpen(!sidebar.classList.contains('is-open'));
            });
            closeButton.addEventListener('click', function() {
                setOpen(false);
            });
            overlay.addEventListener('click', function() {
                setOpen(false);
            });
            sidebar.querySelectorAll('nav a').forEach(function(link) {
                link.addEventListener('click', function() {
                    setOpen(false);
                });
            });
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') setOpen(false);
            });
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) setOpen(false);
            });
        })();
    </script>
</body>

</html>