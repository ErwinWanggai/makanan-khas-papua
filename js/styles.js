document.addEventListener("DOMContentLoaded", function () {
  const navLinks = document.querySelectorAll(".nav-link");
  const sections = document.querySelectorAll(".section");

  function showSection(sectionId) {
    sections.forEach((section) => {
      section.style.display = "none";
    });
    document.getElementById(sectionId).style.display = "block";
    navLinks.forEach((link) => {
      if (link.getAttribute("data-section") === sectionId) {
        link.classList.add("active");
      } else {
        link.classList.remove("active");
      }
    });
  }

  showSection("home");

  navLinks.forEach((link) => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      const sectionId = this.getAttribute("data-section");
      showSection(sectionId);
    });
  });

  const searchInput = document.getElementById("search-input");
  const searchButton = document.getElementById("search-btn");
  const noResultsMessage = document.getElementById("no-results");
  const container = document.querySelector("#food .products");
  const originalCards = document.querySelectorAll("#food .products .food-card");

  // Validasi elemen
  if (!searchInput || !searchButton || !noResultsMessage || !container) {
    console.error(
      "Elemen pencarian tidak ditemukan. Periksa ID: search-input, search-btn, no-results, atau class .products di #food"
    );
    return;
  }

  function highlightText(text, term) {
    if (!term) return text;
    const regex = new RegExp(`(${term})`, "gi");
    return text.replace(regex, '<span class="highlight">$1</span>');
  }

  function performSearch() {
    const searchTerm = searchInput.value.toLowerCase().trim();
    container.innerHTML = "";
    let resultsFound = false;

    originalCards.forEach((card) => {
      const foodName = card.querySelector("h3").textContent.toLowerCase();
      const match = foodName.includes(searchTerm); // Hanya mencocokkan nama makanan

      const clone = card.cloneNode(true);
      clone.style.display = "block";

      // Hapus highlight sebelumnya
      clone.innerHTML = clone.innerHTML.replace(/<span class="highlight">(.*?)<\/span>/g, "$1");

      if (match || searchTerm === "") {
        if (searchTerm !== "") {
          const title = clone.querySelector("h3");
          title.innerHTML = highlightText(title.textContent, searchTerm); // Hanya highlight nama
        }
        container.appendChild(clone);
        resultsFound = true;
      }
    });

    noResultsMessage.style.display = resultsFound ? "none" : "block";
  }

  searchButton.addEventListener("click", performSearch);
  searchInput.addEventListener("keyup", function (e) {
    if (e.key === "Enter") performSearch();
  });
  searchInput.addEventListener("input", function () {
    if (this.value === "") performSearch();
  });

  window.addEventListener("scroll", function () {
    const header = document.querySelector("header");
    if (window.scrollY > 50) {
      header.classList.add("scrolled");
    } else {
      header.classList.remove("scrolled");
    }
  });

  // const contactForm = document.getElementById("contactForm");
  // if (contactForm) {
  //   contactForm.addEventListener("submit", function (e) {
  //     e.preventDefault();
  //     alert("Terima kasih! Pesan Anda telah dikirim.");
  //     contactForm.reset();
  //   });
  // }

  const carousel = document.querySelector(".carousel");
  const carouselItems = document.querySelectorAll(".carousel-item");
  const prevBtn = document.querySelector(".carousel-btn.prev");
  const nextBtn = document.querySelector(".carousel-btn.next");
  const indicators = document.querySelectorAll(".indicator");
  let currentSlide = 0;
  const totalSlides = carouselItems.length;

  function showSlide(index) {
    carouselItems.forEach((item) => item.classList.remove("active"));
    indicators.forEach((indicator) => indicator.classList.remove("active"));
    carouselItems[index].classList.add("active");
    indicators[index].classList.add("active");
    currentSlide = index;
  }

  function nextSlide() {
    showSlide((currentSlide + 1) % totalSlides);
  }

  function prevSlide() {
    showSlide((currentSlide - 1 + totalSlides) % totalSlides);
  }

  if (prevBtn) prevBtn.addEventListener("click", prevSlide);
  if (nextBtn) nextBtn.addEventListener("click", nextSlide);
  indicators.forEach((indicator, index) => {
    indicator.addEventListener("click", () => showSlide(index));
  });

  let slideInterval = setInterval(nextSlide, 5000);
  if (carousel) {
    carousel.addEventListener("mouseenter", () => clearInterval(slideInterval));
    carousel.addEventListener("mouseleave", () => {
      slideInterval = setInterval(nextSlide, 5000);
    });
  }
});
