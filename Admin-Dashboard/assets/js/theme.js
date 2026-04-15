document.addEventListener("DOMContentLoaded", function () {
  // Initialize theme on page load
  const savedTheme = localStorage.getItem("theme");

  if (savedTheme) {
    document.documentElement.setAttribute("data-bs-theme", savedTheme);
  } else {
    // Default to light theme if nothing is saved
    document.documentElement.setAttribute("data-bs-theme", "light");
  }

  // Attach event listener to theme toggle button in header
  const themeToggle = document.querySelector(".theme-toggle");
  if (themeToggle) {
    themeToggle.addEventListener("click", (e) => {
      e.preventDefault();
      e.stopPropagation();
      toggleTheme();
    });
  }
});

function toggleTheme() {
  const current = document.documentElement.getAttribute("data-bs-theme");
  const newTheme = current === "dark" ? "light" : "dark";

  // Set the new theme
  document.documentElement.setAttribute("data-bs-theme", newTheme);

  // Save to localStorage
  localStorage.setItem("theme", newTheme);
}
