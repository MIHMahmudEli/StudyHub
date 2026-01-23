document.addEventListener("DOMContentLoaded", () => {
    const subjectInput = document.getElementById("subject-input");
    const courseCodeInput = document.getElementById("course-code");
    const suggestionsBox = document.getElementById("suggestions");

    let courses = [];

    // Load courses.json using AJAX
    const coursesUrl = subjectInput.getAttribute("data-url") || "assets/data/courses.json";
    const xhr = new XMLHttpRequest();
    xhr.open("GET", coursesUrl, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                courses = JSON.parse(xhr.responseText);
            } catch (e) {
                console.error("Error parsing courses.json:", e);
            }
        }
    };
    xhr.send();

    subjectInput.addEventListener("input", function () {
        const query = this.value.toLowerCase();
        suggestionsBox.innerHTML = "";

        if (query.length < 2) {
            suggestionsBox.style.display = "none";
            return;
        }

        const matches = courses.filter(c =>
            c.name.toLowerCase().includes(query) || c.code.toLowerCase().includes(query)
        );

        if (matches.length > 0) {
            suggestionsBox.style.display = "block";
            matches.slice(0, 8).forEach(match => {
                const div = document.createElement("div");
                div.classList.add("suggestion-item");
                div.textContent = `${match.code} - ${match.name}`;
                div.addEventListener("click", () => {
                    subjectInput.value = match.name;
                    courseCodeInput.value = match.code;
                    suggestionsBox.style.display = "none";
                });
                suggestionsBox.appendChild(div);
            });
        } else {
            suggestionsBox.style.display = "none";
        }
    });

    document.addEventListener("click", (e) => {
        if (!suggestionsBox.contains(e.target) && e.target !== subjectInput) {
            suggestionsBox.style.display = "none";
        }
    });
});
