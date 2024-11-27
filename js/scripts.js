
document.addEventListener('DOMContentLoaded', () => {
    console.log('JavaScript is loaded and ready!');
});


// FUNCTION TO DISPLAY THE TOAST MESSAGE
function showToast(isSuccess, message, duration = 5000) {
    const toastElement = document.getElementById("toastNotification");
    const toastHeading = document.getElementById("toastHeading");
    const toastMessage = document.getElementById("toastMessage");
    const toastTime = document.getElementById("toastTime");
    const toastIcon = document.getElementById("toastIcon");
    const progressBar = document.querySelector("#toastProgress .progress-bar");

    // Get current time
    const now = new Date();
    const formattedTime = now.toLocaleTimeString([], {
        hour: "2-digit",
        minute: "2-digit",
    });
    toastTime.textContent = formattedTime;

    // Set Toast content and icon
    if (isSuccess) {
        toastHeading.textContent = "Success";
        toastHeading.classList.remove("text-danger");
        toastHeading.classList.add("text-success");
        toastMessage.textContent = message;

        // Green tick icon for success
        toastIcon.innerHTML = `<i class="bi bi-check-circle-fill text-success" style="font-size: 1.2rem;"></i>`;
        progressBar.classList.replace("bg-danger", "bg-success");
    } else {
        toastHeading.textContent = "Error";
        toastHeading.classList.remove("text-success");
        toastHeading.classList.add("text-danger");
        toastMessage.textContent = message;

        // Red X icon for error
        toastIcon.innerHTML = `<i class="bi bi-x-circle-fill text-danger" style="font-size: 1.2rem;"></i>`;
        progressBar.classList.replace("bg-success", "bg-danger");
    }

    // Reset and animate progress bar
    progressBar.style.width = "100%";
    progressBar.style.transition = `width ${duration}ms linear`;
    setTimeout(() => {
        progressBar.style.width = "0%";
    }, 0);

    // Show the Toast
    const toast = new bootstrap.Toast(toastElement, {
        delay: duration,
    });
    toast.show();
}

// Trigger Toast if URL contains success or error messages
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.has("success")) {
    showToast(true, urlParams.get("success"), 5000);
} else if (urlParams.has("error")) {
    showToast(false, urlParams.get("error"), 5000);
}


