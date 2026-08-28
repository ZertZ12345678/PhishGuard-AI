const themeButton = document.querySelector(".theme-btn");

function applyTheme() {

    const theme = localStorage.getItem("theme");

    if (theme === "light") {
        document.body.classList.add("light-mode");
        themeButton.innerHTML = "☾";
    } else {
        document.body.classList.remove("light-mode");
        themeButton.innerHTML = "☀";
    }
}

applyTheme();


themeButton.addEventListener("click", function () {

    document.body.classList.toggle("light-mode");

    if (document.body.classList.contains("light-mode")) {

        localStorage.setItem("theme", "light");
        themeButton.innerHTML = "☾";

    } else {

        localStorage.setItem("theme", "dark");
        themeButton.innerHTML = "☀";

    }

});