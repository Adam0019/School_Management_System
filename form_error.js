document.addEventListener("DOMContentLoaded", () => {
  const forms = document.querySelectorAll("form");

  forms.forEach((form) => {
    form.addEventListener("submit", (e) => {
      const isValid = validateFrom(form);
      if (!isValid) {
        e.preventDefault();
      }
    });

    form.querySelectorAll("input").forEach((input) => {
      input.addEventListener("input", () => {
        validateField(input);
      });
    });
  });
});

function validateFrom(form) {
  let valid = true;
  form.querySelectorAll("input").forEach((input) => {
    const fieldValid = validateField(input);
    if (!fieldValid) valid = false;
  });
  return valid;
}

function validateField(input) {
  const errorElement = input
    .closest(".input-group")
    ?.querySelector(".input-error");

  if (!errorElement) return true;

  let errorMessage = "";

  if (input.hasAttribute("required") && !input.value.trim()) {
    errorMessage = "This field is required.";
  } else if (input.type === "email" && !isValidEmail(input.value.trim())) {
    errorMessage = "Enter a valid email address.";
  } else if (input.name === "password" || input.id === "password") {
    if (input.value.length < 8) {
      errorMessage = "Password must be at least 8 characters.";
    } else if (!/[A-Za-z]/.test(input.value) || !/[0-9]/.test(input.value)) {
      errorMessage = "Password must contain letters and numbers.";
    }
  } else if (input.name === "confirm_password") {
    const password = document.getElementById("password");
    if (password && input.value !== password.value) {
      errorMessage = "Password do not match.";
    }
  }

  errorElement.textContent = errorMessage;
  return errorMessage === "";
}

function isValidEmail(email) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}
