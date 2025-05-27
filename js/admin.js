// Navigasi
function showSection(sectionId, title) {
  document.querySelectorAll(".content-section").forEach((section) => {
    section.classList.remove("active");
  });
  document.getElementById(sectionId).classList.add("active");
  document.querySelectorAll(".nav-item").forEach((item) => {
    item.classList.remove("active");
  });
  document.querySelector(`.nav-item[onclick*="${sectionId}"]`).classList.add("active");
  document.getElementById("page-title").innerText = title;
  addActivity(`Berpindah ke ${title}`, `Melihat bagian ${title}`);

  if (sectionId === "food");
  if (sectionId === "contact");
  if (sectionId === "user");
  if (sectionId === "dashboard");
}

function addActivity(activity, detail) {
  const now = new Date().toLocaleString();
  activityLog.unshift({ time: now, activity: activity, detail: detail });
  renderActivityLog();
}

function renderActivityLog() {
  const tbody = document.getElementById("activity-log");
  tbody.innerHTML = "";
  activityLog.slice(0, 5).forEach((log) => {
    const row = tbody.insertRow();
    row.insertCell().innerText = log.time;
    row.insertCell().innerText = log.activity;
    row.insertCell().innerText = log.detail;
  });
}

function resetContactForm() {
  document.getElementById("contact-form").reset();
  document.getElementById("contact-id").value = "";
}

function resetUserForm() {
  document.getElementById("user-form").reset();
  document.getElementById("user-id").value = "";
}

// function viewUserDetails(id) {
//   fetch(`admin_crud.php?action=get_users`)
//     .then((response) => response.json())
//     .then((users) => {
//       const user = users.find((u) => u.id == id);
//       if (user) {
//         document.getElementById("modal-title").innerText = "Detail Pengguna";
//         document.getElementById("modal-body").innerHTML = `
//           <p><strong>ID:</strong> ${user.id}</p>
//           <p><strong>Username:</strong> ${user.username}</p>
//         `;
//         openModal();
//         addActivity("Detail Pengguna Dilihat", `Melihat detail untuk pengguna: ${user.username}`);
//       }
//     })
//     .catch((error) => console.error("Error mengambil detail pengguna:", error));
// }

// --- Fungsi Modal ---
function openModal() {
  document.getElementById("detailModal").style.display = "flex";
}

function closeModal() {
  document.getElementById("detailModal").style.display = "none";
}

window.onclick = function (event) {
  const modal = document.getElementById("detailModal");
  if (event.target == modal) {
    modal.style.display = "none";
  }
};

// --- Toggle Mode Gelap ---
function toggleDarkMode() {
  document.body.classList.toggle("dark-mode");
  const isDarkMode = document.body.classList.contains("dark-mode");
  localStorage.setItem("darkMode", isDarkMode);
  document.getElementById("darkModeToggle").checked = isDarkMode;
  addActivity("Tema Diubah", `Beralih ke ${isDarkMode ? "Mode Gelap" : "Mode Terang"}`);
}

// --- Animasi Pengetikan ---
function typeWriter(text, i, fnCallback) {
  const welcomeMessageElement = document.getElementById("welcome-message");
  if (i < text.length) {
    welcomeMessageElement.innerHTML = text.substring(0, i + 1);
    setTimeout(function () {
      typeWriter(text, i + 1, fnCallback);
    }, 50);
  } else if (typeof fnCallback == "function") {
    fnCallback();
  }
}

function startWelcomeAnimation() {
  const welcomeMessageElement = document.getElementById("welcome-message");
  const originalText = "Selamat datang di panel admin";
  welcomeMessageElement.classList.remove("typing-effect");
  welcomeMessageElement.style.animation = "none";
  welcomeMessageElement.offsetHeight;
  welcomeMessageElement.style.animation = "";
  welcomeMessageElement.innerHTML = "";
  welcomeMessageElement.classList.add("typing-effect");
  typeWriter(originalText, 0, function () {});
}

// Inisialisasi saat halaman dimuat
document.addEventListener("DOMContentLoaded", () => {
  const savedDarkMode = localStorage.getItem("darkMode");
  if (savedDarkMode === "true") {
    document.body.classList.add("dark-mode");
    document.getElementById("darkModeToggle").checked = true;
  } else {
    document.getElementById("darkModeToggle").checked = false;
  }

  showSection("dashboard", "Dashboard");
  startWelcomeAnimation();
});
