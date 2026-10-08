<!DOCTYPE html>
<html class="transition duration-300">

<head>

    <title>
        PhishGuard AI
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
">





    <!-- ===============================
        NAVBAR
================================ -->


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
                class="
text-[var(--primary)]
font-semibold
no-underline
">

                Home

            </a>


            <a
                href="login.php"
                class="nav-link">

                Detection

            </a>


            <a
                href="awareness.php?source=public"
                class="nav-link">

                Awareness

            </a>






            <a
                href="login.php"
                class="nav-link">

                Quiz

            </a>







            <a
                href="register.php"
                class="nav-link">

                Register

            </a>







            <a
                href="login.php"
                class="nav-link">

                Login

            </a>







            <button
                id="theme-toggle"
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









    <!-- ===============================
        HERO SECTION
================================ -->


    <section
        class="
bg-[var(--hero)]
text-center
px-10
py-28
">



        <h1
            class="
text-5xl
font-bold
text-[var(--text)]
">

            Detect Phishing Before It Detects You

        </h1>






        <p
            class="
max-w-4xl
mx-auto
mt-6
text-lg
text-[var(--muted)]
">


            PhishGuard AI is an AI-powered phishing URL detection
            and cybersecurity awareness platform that helps users
            identify suspicious links and stay safe online.


        </p>







        <div
            class="
flex
justify-center
mt-10
">


            <input

                type="text"

                placeholder="Enter suspicious URL to analyze"

                class="
w-96
px-5
py-4
rounded-l-lg
bg-[var(--card)]
text-[var(--text)]
outline-none
">




            <a
                href="register.php">


                <button

                    class="
px-8
py-4
bg-[var(--primary)]
text-white
rounded-r-lg
hover:bg-[var(--secondary)]
transition
">

                    Analyze URL

                </button>


            </a>



        </div>








        <div
            class="
flex
justify-center
gap-5
mt-8
">


            <a
                href="register.php">


                <button

                    class="
px-8
py-3
bg-[var(--primary)]
text-white
rounded-lg
hover:bg-[var(--secondary)]
transition
">

                    Create Account

                </button>


            </a>







            <a
                href="login.php">


                <button

                    class="
px-8
py-3
bg-slate-600
text-white
rounded-lg
hover:bg-slate-500
transition
">

                    Login

                </button>


            </a>






        </div>




    </section>

    <!-- =====================================================
     SMART SECURITY FEATURES
====================================================== -->


    <section
        class="
px-10
py-16
">


        <h2
            class="
text-center
text-3xl
font-bold
text-[var(--secondary)]
mb-10
">

            Protect Yourself With Smart Security

        </h2>





        <div
            class="
grid
md:grid-cols-3
gap-8
">





            <!-- AI Detection -->


            <div
                class="
bg-[var(--card)]
p-8
rounded-xl
shadow
hover:scale-105
transition
">



                <h3
                    class="
text-xl
font-bold
text-[var(--secondary)]
">

                    🔍 AI URL Detection

                </h3>



                <p
                    class="
mt-4
text-[var(--muted)]
">

                    Analyze suspicious URLs using machine learning
                    algorithms and classify them as safe or phishing.

                </p>




            </div>








            <!-- Awareness -->


            <div
                class="
bg-[var(--card)]
p-8
rounded-xl
shadow
hover:scale-105
transition
">



                <h3
                    class="
text-xl
font-bold
text-[var(--secondary)]
">

                    🛡 Cybersecurity Awareness

                </h3>



                <p
                    class="
mt-4
text-[var(--muted)]
">

                    Learn phishing techniques, online threats,
                    and effective prevention methods.

                </p>



            </div>








            <!-- Quiz -->


            <div
                class="
bg-[var(--card)]
p-8
rounded-xl
shadow
hover:scale-105
transition
">



                <h3
                    class="
text-xl
font-bold
text-[var(--secondary)]
">

                    📝 Security Quiz

                </h3>



                <p
                    class="
mt-4
text-[var(--muted)]
">

                    Test your cybersecurity knowledge and improve
                    your security awareness.

                </p>



            </div>







        </div>


    </section>









    <!-- =====================================================
     HOW IT WORKS
====================================================== -->


    <section
        class="
px-10
py-20
bg-[var(--nav)]
">



        <h2
            class="
text-center
text-3xl
font-bold
text-[var(--secondary)]
mb-12
">

            How PhishGuard AI Works

        </h2>







        <div
            class="
grid
md:grid-cols-3
gap-8
max-w-6xl
mx-auto
">





            <div
                class="
bg-[var(--card)]
p-8
rounded-xl
text-center
">


                <h3
                    class="
text-xl
font-bold
text-[var(--secondary)]
">

                    1. Enter URL

                </h3>



                <p
                    class="
mt-4
text-[var(--muted)]
">

                    Users submit suspicious website URLs
                    for security analysis.

                </p>


            </div>







            <div
                class="
bg-[var(--card)]
p-8
rounded-xl
text-center
">


                <h3
                    class="
text-xl
font-bold
text-[var(--secondary)]
">

                    2. AI Analysis

                </h3>



                <p
                    class="
mt-4
text-[var(--muted)]
">

                    The machine learning model analyzes URL
                    features and detects phishing risks.

                </p>


            </div>








            <div
                class="
bg-[var(--card)]
p-8
rounded-xl
text-center
">


                <h3
                    class="
text-xl
font-bold
text-[var(--secondary)]
">

                    3. Get Protection

                </h3>



                <p
                    class="
mt-4
text-[var(--muted)]
">

                    Users receive results and improve their
                    online safety knowledge.

                </p>


            </div>





        </div>




    </section>









    <!-- =====================================================
     WHY CHOOSE PHISHGUARD AI
====================================================== -->


    <section
        class="
px-10
py-20
">



        <h2
            class="
text-center
text-3xl
font-bold
text-[var(--secondary)]
mb-12
">

            Why Choose PhishGuard AI?

        </h2>







        <div
            class="
grid
md:grid-cols-3
gap-8
max-w-6xl
mx-auto
">






            <div
                class="
bg-[var(--card)]
p-8
rounded-xl
shadow
">


                <h3
                    class="
text-xl
font-bold
">

                    🤖 AI-Powered Security

                </h3>



                <p
                    class="
mt-4
text-[var(--muted)]
">

                    Uses machine learning technology to identify
                    potential phishing threats.

                </p>


            </div>









            <div
                class="
bg-[var(--card)]
p-8
rounded-xl
shadow
">


                <h3
                    class="
text-xl
font-bold
">

                    🔒 Safer Browsing

                </h3>



                <p
                    class="
mt-4
text-[var(--muted)]
">

                    Helps users recognize dangerous websites
                    before sharing personal information.

                </p>


            </div>









            <div
                class="
bg-[var(--card)]
p-8
rounded-xl
shadow
">


                <h3
                    class="
text-xl
font-bold
">

                    📚 Cyber Education

                </h3>



                <p
                    class="
mt-4
text-[var(--muted)]
">

                    Provides awareness materials and quizzes
                    to improve cybersecurity skills.

                </p>


            </div>






        </div>



    </section>

    <!-- =====================================================
     CYBERSECURITY TIPS
====================================================== -->


    <section
        class="
px-10
py-20
bg-[var(--nav)]
">



        <h2
            class="
text-center
text-3xl
font-bold
text-[var(--secondary)]
mb-12
">

            Stay Safe Online

        </h2>







        <div
            class="
grid
md:grid-cols-2
gap-8
max-w-6xl
mx-auto
">







            <div
                class="
bg-[var(--card)]
p-8
rounded-xl
shadow
">


                <h3
                    class="
text-xl
font-bold
text-[var(--secondary)]
">

                    📧 Avoid Suspicious Emails

                </h3>



                <p
                    class="
mt-4
text-[var(--muted)]
">

                    Do not click unknown links or download
                    attachments from untrusted sources.

                </p>



            </div>









            <div
                class="
bg-[var(--card)]
p-8
rounded-xl
shadow
">


                <h3
                    class="
text-xl
font-bold
text-[var(--secondary)]
">

                    🌐 Verify Website URLs

                </h3>



                <p
                    class="
mt-4
text-[var(--muted)]
">

                    Always check website addresses before
                    entering passwords or personal information.

                </p>



            </div>









            <div
                class="
bg-[var(--card)]
p-8
rounded-xl
shadow
">


                <h3
                    class="
text-xl
font-bold
text-[var(--secondary)]
">

                    🔑 Use Strong Passwords

                </h3>



                <p
                    class="
mt-4
text-[var(--muted)]
">

                    Create strong passwords and avoid
                    reusing the same password everywhere.

                </p>



            </div>









            <div
                class="
bg-[var(--card)]
p-8
rounded-xl
shadow
">


                <h3
                    class="
text-xl
font-bold
text-[var(--secondary)]
">

                    🔄 Keep Software Updated

                </h3>



                <p
                    class="
mt-4
text-[var(--muted)]
">

                    Regular updates help protect your devices
                    from security vulnerabilities.

                </p>



            </div>







        </div>




    </section>









    <!-- =====================================================
     USER BENEFITS
====================================================== -->


    <section
        class="
px-10
py-20
">



        <h2
            class="
text-center
text-3xl
font-bold
text-[var(--secondary)]
mb-12
">

            Benefits of Using PhishGuard AI

        </h2>







        <div
            class="
grid
md:grid-cols-3
gap-8
max-w-6xl
mx-auto
">





            <div
                class="
bg-[var(--card)]
p-8
rounded-xl
text-center
">


                <h3
                    class="
text-xl
font-bold
">

                    ⚡ Fast Detection

                </h3>



                <p
                    class="
mt-4
text-[var(--muted)]
">

                    Quickly identify suspicious URLs
                    before they become threats.

                </p>


            </div>









            <div
                class="
bg-[var(--card)]
p-8
rounded-xl
text-center
">


                <h3
                    class="
text-xl
font-bold
">

                    🎯 Accurate Analysis

                </h3>



                <p
                    class="
mt-4
text-[var(--muted)]
">

                    Machine learning helps classify URLs
                    as safe or phishing.

                </p>


            </div>









            <div
                class="
bg-[var(--card)]
p-8
rounded-xl
text-center
">


                <h3
                    class="
text-xl
font-bold
">

                    🛡 Better Awareness

                </h3>



                <p
                    class="
mt-4
text-[var(--muted)]
">

                    Improve cybersecurity knowledge through
                    learning resources and quizzes.

                </p>


            </div>







        </div>




    </section>









    <!-- =====================================================
     CALL TO ACTION
====================================================== -->


    <section
        class="
px-10
py-20
text-center
bg-[var(--hero)]
">



        <h2
            class="
text-4xl
font-bold
text-[var(--secondary)]
">

            Ready to Protect Yourself Online?

        </h2>





        <p
            class="
mt-5
text-lg
text-[var(--muted)]
">

            Create an account and start learning
            about cybersecurity protection today.

        </p>







        <div
            class="
flex
justify-center
gap-5
mt-8
">





            <a
                href="register.php">


                <button
                    class="
px-8
py-3
bg-[var(--primary)]
text-white
rounded-lg
hover:bg-[var(--secondary)]
transition
">

                    Get Started

                </button>


            </a>







            <a
                href="awareness.php?source=public">


                <button
                    class="
px-8
py-3
bg-slate-600
text-white
rounded-lg
hover:bg-slate-500
transition
">

                    Learn More

                </button>


            </a>






        </div>





    </section>

    <!-- =====================================================
     FOOTER
====================================================== -->


    <footer
        class="
bg-[var(--nav)]
mt-20
px-6
py-12
">



        <div
            class="
max-w-7xl
mx-auto
grid
md:grid-cols-4
gap-10
">





            <!-- BRAND -->

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









            <!-- QUICK LINKS -->


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
                            href="index.php"
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









            <!-- SECURITY -->


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

                        <a
                            href="awareness.php?source=public"
                            class="nav-link no-underline">

                            Awareness

                        </a>

                    </li>






                    <li>

                        <a
                            href="register.php"
                            class="nav-link no-underline">

                            Detection

                        </a>

                    </li>






                    <li>

                        <a
                            href="login.php"
                            class="nav-link no-underline">

                            Security Quiz

                        </a>

                    </li>





                </ul>



            </div>









            <!-- ABOUT SYSTEM -->


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









        <!-- COPYRIGHT -->


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









    <!-- =====================================================
     BACK TO TOP BUTTON
====================================================== -->


    <button

        id="backToTop"

        class="
fixed
bottom-6
right-6
hidden
bg-blue-600
text-white
w-12
h-12
rounded-full
shadow-lg
text-2xl
font-bold
hover:bg-blue-700
transition
z-50
">

        ↑

    </button>









    <script src="js/script.js"></script>





</body>

</html>