// ==========================================
// Lost and Found System - Form Validation
// (Client-side. PHP re-checks everything server-side too.)
// ==========================================

// ---- Registration Form Validation (public register page) ----
function validateRegisterForm() {
    let isValid = true;

    let fullName = document.getElementById("full_name").value.trim();
    let email = document.getElementById("email").value.trim();
    let phone = document.getElementById("phone").value.trim();
    let password = document.getElementById("password").value.trim();
    let confirmPassword = document.getElementById("confirm_password").value.trim();
    let userType = document.getElementById("user_type").value;

    clearErrors(["full_name", "email", "phone", "password", "confirm_password", "user_type"]);

    if (fullName === "") {
        showError("full_name", "Full name is required.");
        isValid = false;
    }

    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        showError("email", "Enter a valid email address.");
        isValid = false;
    }

    let phonePattern = /^[0-9]{10}$/;
    if (!phonePattern.test(phone)) {
        showError("phone", "Phone number must be exactly 10 digits.");
        isValid = false;
    }

    if (userType === "") {
        showError("user_type", "Please select whether you are a student or staff.");
        isValid = false;
    }

    if (password.length < 6) {
        showError("password", "Password must be at least 6 characters.");
        isValid = false;
    }

    if (confirmPassword !== password) {
        showError("confirm_password", "Passwords do not match.");
        isValid = false;
    }

    return isValid;
}

// ---- Admin: Add/Edit User Form Validation ----
// isEdit = true means the password field is OPTIONAL (blank = keep existing password)
function validateRegisterFormAdmin(isEdit) {
    let isValid = true;

    let fullName = document.getElementById("full_name").value.trim();
    let email = document.getElementById("email").value.trim();
    let phone = document.getElementById("phone").value.trim();
    let password = document.getElementById("password").value.trim();
    let userType = document.getElementById("user_type").value;

    clearErrors(["full_name", "email", "phone", "password", "user_type"]);

    if (fullName === "") {
        showError("full_name", "Full name is required.");
        isValid = false;
    }

    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        showError("email", "Enter a valid email address.");
        isValid = false;
    }

    let phonePattern = /^[0-9]{10}$/;
    if (!phonePattern.test(phone)) {
        showError("phone", "Phone number must be exactly 10 digits.");
        isValid = false;
    }

    if (userType === "") {
        showError("user_type", "Please select a role.");
        isValid = false;
    }

    // On "add", password is required. On "edit", blank means "keep unchanged".
    if (!isEdit && password.length < 6) {
        showError("password", "Password must be at least 6 characters.");
        isValid = false;
    } else if (isEdit && password.length > 0 && password.length < 6) {
        showError("password", "New password must be at least 6 characters.");
        isValid = false;
    }

    return isValid;
}

// ---- Login Form Validation ----
function validateLoginForm() {
    let isValid = true;

    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value.trim();

    clearErrors(["email", "password"]);

    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        showError("email", "Enter a valid email address.");
        isValid = false;
    }

    if (password === "") {
        showError("password", "Password is required.");
        isValid = false;
    }

    return isValid;
}

// ---- Post Item Form Validation (used for both lost-item and found-item forms) ----
function validateItemForm() {
    let isValid = true;

    let itemName = document.getElementById("item_name").value.trim();
    let category = document.getElementById("category").value;
    let location = document.getElementById("location").value.trim();
    let itemDate = document.getElementById("item_date").value.trim();
    let contactDetails = document.getElementById("contact_details").value.trim();

    clearErrors(["item_name", "category", "location", "item_date", "contact_details", "image"]);

    if (itemName === "") {
        showError("item_name", "Item name is required.");
        isValid = false;
    }

    if (category === "") {
        showError("category", "Please select a category.");
        isValid = false;
    }

    if (location === "") {
        showError("location", "Location is required.");
        isValid = false;
    }

    if (itemDate === "") {
        showError("item_date", "Date is required.");
        isValid = false;
    }

    if (contactDetails === "") {
        showError("contact_details", "Please provide a phone number or email so people can reach you.");
        isValid = false;
    }

    let imageInput = document.getElementById("image");
    if (imageInput && imageInput.files.length > 0) {
        let file = imageInput.files[0];
        let allowedTypes = ["image/jpeg", "image/png", "image/jpg"];
        let maxSizeMB = 2;

        if (!allowedTypes.includes(file.type)) {
            showError("image", "Only JPG or PNG images are allowed.");
            isValid = false;
        } else if (file.size > maxSizeMB * 1024 * 1024) {
            showError("image", "Image size must be under 2MB.");
            isValid = false;
        }
    }

    return isValid;
}

// ---- Helper: show an error message under a field ----
function showError(fieldId, message) {
    let field = document.getElementById(fieldId);
    let errorSpan = document.getElementById(fieldId + "_error");

    if (!errorSpan) {
        errorSpan = document.createElement("span");
        errorSpan.className = "error-text";
        errorSpan.id = fieldId + "_error";
        field.parentNode.appendChild(errorSpan);
    }
    errorSpan.textContent = message;
}

// ---- Helper: clear previously shown error messages ----
function clearErrors(fieldIds) {
    fieldIds.forEach(function (id) {
        let errorSpan = document.getElementById(id + "_error");
        if (errorSpan) {
            errorSpan.textContent = "";
        }
    });
}

// ---- Live search filter (kept for reference / optional use) ----
function liveSearch() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    let cards = document.getElementsByClassName("item-card");

    for (let i = 0; i < cards.length; i++) {
        let title = cards[i].getElementsByTagName("h3")[0].textContent.toLowerCase();
        if (title.includes(input)) {
            cards[i].style.display = "";
        } else {
            cards[i].style.display = "none";
        }
    }
}
