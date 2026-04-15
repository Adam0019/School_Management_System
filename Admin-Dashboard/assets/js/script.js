// Dropdown functionality
const menuButton = document.getElementById("menuButton");
const dropdownMenu = document.getElementById("dropdownMenu");
const dropdownOverlay = document.getElementById("dropdownOverlay");

console.log("menuButton:", menuButton);
console.log("dropdownMenu:", dropdownMenu);
console.log("dropdownOverlay:", dropdownOverlay);

if (menuButton && dropdownMenu && dropdownOverlay) {
  console.log("All elements found!");

  // Toggle dropdown
  menuButton.addEventListener("click", function (e) {
    console.log("Menu button clicked!");
    e.stopPropagation();
    dropdownMenu.classList.toggle("active");
    dropdownOverlay.classList.toggle("active");
    console.log(
      "Classes toggled. Active?",
      dropdownMenu.classList.contains("active"),
    );
  });

  // Close dropdown when clicking overlay
  dropdownOverlay.addEventListener("click", function () {
    console.log("Overlay clicked!");
    dropdownMenu.classList.remove("active");
    dropdownOverlay.classList.remove("active");
  });

  // Close dropdown when clicking outside
  document.addEventListener("click", function (e) {
    if (!menuButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
      dropdownMenu.classList.remove("active");
      dropdownOverlay.classList.remove("active");
    }
  });

  // Optional: Close on item click
  document.querySelectorAll(".app-item, .view-all-link").forEach((item) => {
    item.addEventListener("click", function () {
      dropdownMenu.classList.remove("active");
      dropdownOverlay.classList.remove("active");
    });
  });
} else {
  console.error("Missing elements!");
  console.log("menuButton exists?", !!menuButton);
  console.log("dropdownMenu exists?", !!dropdownMenu);
  console.log("dropdownOverlay exists?", !!dropdownOverlay);
}
// ============ SEARCH FUNCTIONALITY ============
const searchInput = document.querySelector(".search-box input");
const searchIcon = document.querySelector(".search-box i");

if (searchInput) {
  // Real-time search as user types
  searchInput.addEventListener("input", function (e) {
    const searchTerm = e.target.value.toLowerCase().trim();

    if (searchTerm === "") {
      // Reset all items when search is cleared
      resetSearch();
      return;
    }

    performSearch(searchTerm);
  });

  // Search on Enter key
  searchInput.addEventListener("keypress", function (e) {
    if (e.key === "Enter") {
      e.preventDefault();
      const searchTerm = e.target.value.toLowerCase().trim();
      performSearch(searchTerm);
    }
  });

  // Search on icon click
  searchIcon.addEventListener("click", function () {
    const searchTerm = searchInput.value.toLowerCase().trim();
    if (searchTerm !== "") {
      performSearch(searchTerm);
    }
  });
}

function performSearch(searchTerm) {
  let foundResults = false;

  // Search in Activity Log
  const activityItems = document.querySelectorAll(".activity-item");
  activityItems.forEach((item) => {
    const name =
      item.querySelector(".activity-name")?.textContent.toLowerCase() || "";
    const details =
      item.querySelector(".activity-details")?.textContent.toLowerCase() || "";

    if (name.includes(searchTerm) || details.includes(searchTerm)) {
      item.style.display = "block";
      item.style.backgroundColor = "#b5ea89ff"; // Highlight found items
      foundResults = true;
    } else {
      item.style.display = "none";
    }
  });

  // Search in Order History
  const orderItems = document.querySelectorAll(".order-item");
  orderItems.forEach((item) => {
    const name =
      item.querySelector(".order-name")?.textContent.toLowerCase() || "";
    const status =
      item.querySelector(".order-status")?.textContent.toLowerCase() || "";
    const number = item.textContent.toLowerCase();

    if (
      name.includes(searchTerm) ||
      status.includes(searchTerm) ||
      number.includes(searchTerm)
    ) {
      item.style.display = "flex";
      item.style.backgroundColor = "#b5ea89ff"; // Highlight found items
      foundResults = true;
    } else {
      item.style.display = "none";
    }
  });

  // Search in Stock Table
  const stockRows = document.querySelectorAll(".stock-table tbody tr");
  stockRows.forEach((row) => {
    const symbol =
      row.querySelector(".stock-symbol")?.textContent.toLowerCase() || "";
    const stockName =
      row.querySelector(".stock-name")?.textContent.toLowerCase() || "";

    if (symbol.includes(searchTerm) || stockName.includes(searchTerm)) {
      row.style.display = "table-row";
      row.style.backgroundColor = "#b5ea89ff"; // Highlight found items
      foundResults = true;
    } else {
      row.style.display = "none";
    }
  });

  // Show message if no results found
  if (!foundResults) {
    showNoResultsMessage();
  } else {
    removeNoResultsMessage();
  }
}

function resetSearch() {
  // Reset Activity Log
  const activityItems = document.querySelectorAll(".activity-item");
  activityItems.forEach((item) => {
    item.style.display = "block";
    item.style.backgroundColor = "";
  });

  // Reset Order History
  const orderItems = document.querySelectorAll(".order-item");
  orderItems.forEach((item) => {
    item.style.display = "flex";
    item.style.backgroundColor = "";
  });

  // Reset Stock Table
  const stockRows = document.querySelectorAll(".stock-table tbody tr");
  stockRows.forEach((row) => {
    row.style.display = "table-row";
    row.style.backgroundColor = "";
  });

  removeNoResultsMessage();
}

function showNoResultsMessage() {
  removeNoResultsMessage(); // Remove existing message first

  const mainContent = document.querySelector(".main-content");
  const noResultsDiv = document.createElement("div");
  noResultsDiv.id = "noResultsMessage";
  noResultsDiv.style.cssText = `
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    text-align: center;
    z-index: 1000;
  `;
  noResultsDiv.innerHTML = `
    <i class="fas fa-search" style="font-size: 48px; color: #ccc; margin-bottom: 15px;"></i>
    <h3 style="margin: 10px 0; color: #333;">No Results Found</h3>
    <p style="color: #666;">Try searching with different keywords</p>
  `;

  if (mainContent) {
    mainContent.appendChild(noResultsDiv);

    // Auto-remove after 3 seconds
    setTimeout(() => {
      removeNoResultsMessage();
    }, 3000);
  }
}

function removeNoResultsMessage() {
  const existingMessage = document.getElementById("noResultsMessage");
  if (existingMessage) {
    existingMessage.remove();
  }
}

// Add this to your script.js file

// ============ SIDEBAR ACTIVE STATE ============

// Function to set active nav item based on current page
function setActiveNavItem() {
  const navItems = document.querySelectorAll(".nav-item");
  const currentPage = window.location.pathname;

  // Remove active class from all items first
  navItems.forEach((item) => {
    item.classList.remove("active");
  });

  // Check if we're on dashboard (index.php or root)
  if (currentPage.includes("index.php") || currentPage.endsWith("/")) {
    const dashboardItem = document.querySelector(
      'a[href*="index.php"] .nav-item',
    );
    if (dashboardItem) {
      dashboardItem.classList.add("active");
    }
    return;
  }

  // Check if we're on user profile (About page)
  if (currentPage.includes("user_profile.php")) {
    const aboutItem = document.querySelector(
      'a[href*="user_profile.php"] .nav-item',
    );
    if (aboutItem) {
      aboutItem.classList.add("active");
    }
    return;
  }

  // Check if we're on Subjects page
  if (currentPage.includes("subjects_page.php")) {
    const studentItem = document.querySelector(
      'a[href*="subjects_page.php"] .nav-item',
    );
    if (studentItem) {
      studentItem.classList.add("active");
    }
    return;
  }
  // Check if we're on Classes page
  if (currentPage.includes("classes_page.php")) {
    const studentItem = document.querySelector(
      'a[href*="classes_page.php"] .nav-item',
    );
    if (studentItem) {
      studentItem.classList.add("active");
    }
    return;
  }
  // Check if we're on Classes page
  if (currentPage.includes("add_article.php")) {
    const studentItem = document.querySelector(
      'a[href*="add_article.php"] .nav-item',
    );
    if (studentItem) {
      studentItem.classList.add("active");
    }
    return;
  }

  // If no match found, set Dashboard as active by default
  const dashboardItem = document.querySelector(
    'a[href*="index.php"] .nav-item',
  );
  if (dashboardItem) {
    dashboardItem.classList.add("active");
  }
}

// Add click event listeners to all nav items
document.querySelectorAll(".nav-item").forEach((item) => {
  item.addEventListener("click", function () {
    // Remove active class from all nav items
    document.querySelectorAll(".nav-item").forEach((navItem) => {
      navItem.classList.remove("active");
    });

    // Add active class to clicked item
    this.classList.add("active");
  });
});

// Set active state on page load
document.addEventListener("DOMContentLoaded", function () {
  setActiveNavItem();
});

// Also call it immediately in case DOMContentLoaded already fired
setActiveNavItem();
