<!DOCTYPE html>

<html>



<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>User Home - PhishGuard AI</title>



    <link rel="stylesheet" href="css/style.css">



    <script src="https://cdn.tailwindcss.com"></script>


    <style>
        /* Responsive additions for user_home.php. Existing colours and theme variables are unchanged. */
        html {
            box-sizing: border-box;
            overflow-x: clip;
        }

        *,
        *::before,
        *::after {
            box-sizing: inherit;
        }

        body {
            margin: 0;
            min-width: 0;
            overflow-x: clip;
        }

        .phish-header {
            position: relative;
            width: 100%;
            gap: 1rem;
        }

        .phish-header>a {
            flex-shrink: 0;
        }

        .phish-nav {
            min-width: 0;
        }

        .phish-nav a {
            white-space: nowrap;
        }

        .phish-menu-toggle {
            display: none;
            color: var(--text);
            background: transparent;
            border: 1px solid var(--muted);
            border-radius: .5rem;
            padding: .35rem .7rem;
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Guard against legacy stylesheet rules overriding the layout. */
        .phish-card-grid {
            display: grid !important;
            grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
            width: 100%;
        }

        .phish-card-grid>div {
            min-width: 0;
            width: auto !important;
            max-width: none !important;
            margin: 0 !important;
        }

        .phish-tips-grid {
            display: grid !important;
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }

        .phish-tips-grid>div {
            min-width: 0;
        }

        .phish-content {
            width: 100%;
            max-width: 1440px;
            margin-left: auto;
            margin-right: auto;
        }

        @media (max-width: 1023px) {
            .phish-header {
                padding: 1rem 1.5rem;
            }

            .phish-menu-toggle {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                margin-left: auto;
            }

            .phish-nav {
                display: none !important;
                position: absolute;
                z-index: 60;
                top: 100%;
                left: 0;
                right: 0;
                padding: 1.25rem 1.5rem;
                background: var(--nav);
                box-shadow: 0 12px 24px rgba(0, 0, 0, .2);
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
            }

            .phish-nav.is-open {
                display: flex !important;
                align-items: flex-start !important;
                text-align: left !important;
            }

            .phish-nav.is-open a {
                text-align: left !important;
                align-self: flex-start !important;
            }

            .phish-nav a {
                display: block;
                padding: .35rem 0;
            }

            .phish-nav #theme-toggle {
                align-self: flex-start;
            }

            .phish-card-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }

            .phish-footer-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }
        }

        @media (max-width: 639px) {
            .phish-header {
                padding: .9rem 1rem;
            }

            .phish-header>a {
                font-size: 1.15rem;
            }

            .phish-hero {
                padding: 3.5rem 1rem 2.5rem !important;
            }

            .phish-hero h1 {
                font-size: clamp(1.8rem, 7vw, 2.5rem);
                line-height: 1.2;
            }

            .phish-hero>p {
                font-size: 1rem;
            }

            .phish-section {
                padding: 2.5rem 1rem !important;
            }

            .phish-section h2 {
                font-size: 1.6rem;
                line-height: 1.3;
            }

            .phish-card-grid,
            .phish-tips-grid,
            .phish-footer-grid {
                grid-template-columns: minmax(0, 1fr) !important;
            }

            .phish-card-grid {
                gap: 1rem !important;
            }

            .phish-card-grid>div {
                padding: 1.5rem !important;
            }

            .phish-cta-actions {
                flex-wrap: wrap;
                gap: .75rem;
            }

            .phish-cta-actions a {
                min-width: 135px;
                text-align: center;
            }

            .phish-footer {
                padding: 2.5rem 1rem !important;
            }
        }
    </style>
</head>



<body class="bg-[var(--bg)] text-[var(--text)] transition duration-300">



    <header class="phish-header flex justify-between items-center px-10 py-5 bg-[var(--nav)]">



        <a href="user_home.php"

            class="text-2xl font-bold text-[var(--secondary)] no-underline">

            🛡 PhishGuard AI

        </a>





        <button type="button" id="mobile-menu-toggle" class="phish-menu-toggle" aria-label="Open navigation menu" aria-controls="primary-navigation" aria-expanded="false">☰</button>

        <nav id="primary-navigation" class="phish-nav flex items-center gap-6">



            <a href="user_home.php"

                class="text-[var(--primary)] font-semibold no-underline">

                Home

            </a>



            <a href="detection.php"

                class="nav-link">

                Detection

            </a>



            <a href="awareness.php?source=user"

                class="nav-link">

                Awareness

            </a>



            <a href="take_quiz.php"

                class="nav-link">

                Quiz

            </a>



            <a href="user_dashboard.php"

                class="nav-link">

                Dashboard

            </a>



            <a href="index.php"

                class="nav-link">

                Logout

            </a>





            <button id="theme-toggle"

                class="text-2xl bg-transparent border-none cursor-pointer">

                ☀

            </button>



        </nav>



    </header>





    <section class="phish-hero phish-content text-center py-24 px-10">



        <h1 class="text-5xl font-bold text-[var(--secondary)]">

            Welcome to PhishGuard AI

        </h1>





        <p class="mt-6 text-lg text-[var(--muted)]">

            Protect yourself from phishing attacks with AI-powered security tools.

        </p>





        <div class="phish-card-grid grid md:grid-cols-3 gap-8 mt-12">





            <div class="bg-[var(--card)] p-8 rounded-xl shadow">



                <h2 class="text-xl font-bold text-[var(--secondary)]">

                    🔍 URL Detection

                </h2>



                <p class="mt-4 text-[var(--text)]">

                    Analyze suspicious URLs and identify phishing threats.

                </p>



            </div>







            <div class="bg-[var(--card)] p-8 rounded-xl shadow">



                <h2 class="text-xl font-bold text-[var(--secondary)]">

                    🛡 Awareness

                </h2>



                <p class="mt-4 text-[var(--text)]">

                    Learn cybersecurity protection methods.

                </p>



            </div>







            <div class="bg-[var(--card)] p-8 rounded-xl shadow">



                <h2 class="text-xl font-bold text-[var(--secondary)]">

                    📝 Quiz

                </h2>



                <p class="mt-4 text-[var(--text)]">

                    Test your security knowledge.

                </p>



            </div>





        </div>





    </section>



    <!-- HOW IT WORKS -->





    <section

        class="phish-section phish-content 

px-10

py-16

">





        <h2

            class="

text-3xl

font-bold

text-center

text-[var(--secondary)]

mb-10

">



            How PhishGuard AI Works



        </h2>











        <div

            class="phish-card-grid grid

md:grid-cols-3

gap-8

">











            <div

                class="

bg-[var(--card)]

p-8

rounded-xl

shadow

text-center

">



                <h3

                    class="

text-xl

font-bold

text-[var(--secondary)]

">



                    1. Detect



                </h3>





                <p class="mt-4">



                    Analyze suspicious URLs using

                    AI-powered phishing detection.



                </p>





            </div>















            <div

                class="

bg-[var(--card)]

p-8

rounded-xl

shadow

text-center

">



                <h3

                    class="

text-xl

font-bold

text-[var(--secondary)]

">



                    2. Learn



                </h3>





                <p class="mt-4">



                    Improve cybersecurity knowledge

                    through awareness materials.



                </p>





            </div>















            <div

                class="

bg-[var(--card)]

p-8

rounded-xl

shadow

text-center

">



                <h3

                    class="

text-xl

font-bold

text-[var(--secondary)]

">



                    3. Test



                </h3>





                <p class="mt-4">



                    Complete quizzes and evaluate

                    your security awareness.



                </p>





            </div>









        </div>





    </section>



    <!-- SECURITY TIPS -->





    <section

        class="phish-section phish-content 

px-10

py-16

">





        <div

            class="

max-w-6xl

mx-auto

">





            <h2

                class="

text-3xl

font-bold

text-center

text-[var(--secondary)]

mb-10

">



                Cybersecurity Tips



            </h2>











            <div

                class="phish-tips-grid grid

md:grid-cols-2

gap-6

">











                <div

                    class="

bg-[var(--card)]

p-6

rounded-xl

">



                    🔐



                    <strong>

                        Use Strong Passwords

                    </strong>



                    <p class="mt-2">



                        Create unique passwords and avoid

                        sharing them with others.



                    </p>



                </div>















                <div

                    class="

bg-[var(--card)]

p-6

rounded-xl

">



                    📧



                    <strong>

                        Be Careful With Emails

                    </strong>





                    <p class="mt-2">



                        Avoid clicking suspicious links

                        from unknown senders.



                    </p>





                </div>

















                <div

                    class="

bg-[var(--card)]

p-6

rounded-xl

">



                    🌐



                    <strong>

                        Check Website URLs

                    </strong>





                    <p class="mt-2">



                        Verify website addresses before

                        entering personal information.



                    </p>





                </div>

















                <div

                    class="

bg-[var(--card)]

p-6

rounded-xl

">



                    🛡



                    <strong>

                        Keep Software Updated

                    </strong>





                    <p class="mt-2">



                        Regular updates improve security

                        and protect against threats.



                    </p>





                </div>











            </div>





        </div>





    </section>



    <!-- CTA -->





    <section

        class="phish-section phish-content 

px-10

py-20

text-center

">





        <h2

            class="

text-3xl

font-bold

text-[var(--secondary)]

">



            Stay Safe Online With PhishGuard AI



        </h2>







        <p

            class="

mt-4

">



            Detect threats, learn cybersecurity,

            and improve your online protection.



        </p>







        <div

            class="phish-cta-actions mt-8

flex

justify-center

gap-5

">





            <a

                href="detection.php"

                class="

px-8

py-3

bg-blue-600

text-white

rounded-lg

no-underline

">



                Check URL



            </a>







            <a

                href="take_quiz.php"

                class="

px-8

py-3

bg-green-600

text-white

rounded-lg

no-underline

">



                Take Quiz



            </a>







        </div>





    </section>



    <!-- =====================================================

     FOOTER

\====================================================== -->



    <footer

        class="phish-footer 

        bg-[var(--nav)]

        mt-20

        px-6

        py-12

    ">





        <div

            class="phish-footer-grid 

            max-w-7xl

            mx-auto

            grid

            md:grid-cols-4

            gap-10

        ">





            <!-- Brand -->



            <div>



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





                <p

                    class="

                    mt-4

                    text-[var(--muted)]

                    text-sm

                    leading-relaxed

                ">

                    An AI-based phishing URL detection and

                    cybersecurity awareness system that helps

                    users identify online threats and improve

                    digital security.

                </p>





            </div>







            <!-- Quick Links -->



            <div>





                <h3

                    class="

                    text-lg

                    font-bold

                    text-[var(--text)]

                    mb-4

                ">

                    Quick Links

                </h3>





                <ul

                    class="

                    space-y-3

                    text-sm

                ">



                    <li>

                        <a

                            href="user_home.php"

                            class="nav-link no-underline">

                            Home

                        </a>

                    </li>





                    <li>

                        <a

                            href="login.php"

                            class="nav-link no-underline">

                            Login

                        </a>

                    </li>





                    <li>

                        <a

                            href="register.php"

                            class="nav-link no-underline">

                            Register

                        </a>

                    </li>









                </ul>





            </div>









            <!-- Security -->



            <div>





                <h3

                    class="

                    text-lg

                    font-bold

                    text-[var(--text)]

                    mb-4

                ">

                    Security

                </h3>





                <ul

                    class="

                    space-y-3

                    text-sm

                ">



                    <li>

                        <a href="awareness.php?source=user"

                            class="nav-link">

                            Awareness

                        </a>

                    </li>





                    <li>

                        <a

                            href="detection.php"

                            class="nav-link no-underline">

                            Detection

                        </a>

                    </li>



                    <li>

                        <a

                            href="take_quiz.php"

                            class="nav-link no-underline">

                            Security Quiz

                        </a>

                    </li>





                </ul>





            </div>









            <!-- Contact -->



            <div>





                <h3

                    class="

                    text-lg

                    font-bold

                    text-[var(--text)]

                    mb-4

                ">

                    About System

                </h3>





                <p

                    class="

                    text-sm

                    text-[var(--muted)]

                    leading-relaxed

                ">



                    Powered by AI using Random Forest and CNN models for phishing URL detection and cybersecurity analysis.



                </p>





            </div>





        </div>









        <!-- Bottom Copyright -->



        <div

            class="

            max-w-7xl

            mx-auto

            mt-10

            pt-6

            border-t

            border-gray-500/20

            text-center

            text-sm

            text-[var(--muted)]

        ">



            © 2026 PhishGuard AI.

            All rights reserved.



        </div>





    </footer>



    <script src="js/script.js"></script>




    <script>
        (function() {
            const toggle = document.getElementById('mobile-menu-toggle');
            const nav = document.getElementById('primary-navigation');
            if (!toggle || !nav) return;
            toggle.addEventListener('click', function() {
                const opened = nav.classList.toggle('is-open');
                toggle.setAttribute('aria-expanded', String(opened));
                toggle.setAttribute('aria-label', opened ? 'Close navigation menu' : 'Open navigation menu');
                toggle.textContent = opened ? '✕' : '☰';
            });
            nav.querySelectorAll('a').forEach(function(link) {
                link.addEventListener('click', function() {
                    nav.classList.remove('is-open');
                    toggle.setAttribute('aria-expanded', 'false');
                    toggle.textContent = '☰';
                });
            });
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    nav.classList.remove('is-open');
                    toggle.setAttribute('aria-expanded', 'false');
                    toggle.textContent = '☰';
                }
            });
        })();
    </script>
</body>



</html>