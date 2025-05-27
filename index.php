<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kuliner Papua - Makanan Khas Papua</title>
    <link rel="stylesheet" href="./css/styles.css" />
</head>

<body>
    <header>
        <div class="container header-content">
            <div class="logo">
                <div class="logo-img">
                    <img src="./img/logo.png" alt="Logo Kuliner Papua" />
                </div>
                <div class="logo-text">Makanan Khas Papua</div>
            </div>
            <nav>
                <ul>
                    <li><a href="#home" class="active nav-link" data-section="home">Home</a></li>
                    <li><a href="#about" class="nav-link" data-section="about">About</a></li>
                    <li><a href="#food" class="nav-link" data-section="food">Food</a></li>
                    <li><a href="#contact" class="nav-link" data-section="contact">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Home Section -->
    <section id="home" class="section">
        <div class="hero">
            <div class="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="./img/papeda.jpg" alt="Kelezatan Kuliner Papua" />
                        <div class="carousel-overlay"></div>
                        <div class="carousel-caption">
                            <h1>Kelezatan Makanan Khas Papua</h1>
                            <p>Jelajahi kekayaan cita rasa Papua melalui makanan tradisional yang autentik dan lezat</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="./img/ikan.jpeg" alt="Papeda - Makanan Pokok Papua" />
                        <div class="carousel-overlay"></div>
                        <div class="carousel-caption">
                            <h1>Papeda - Makanan Pokok Papua</h1>
                            <p>Nikmati kelezatan papeda dengan kuah kuning dan ikan segar khas Papua</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="./img/sagu.jpg" alt="Cita Rasa Asli Papua" />
                        <div class="carousel-overlay"></div>
                        <div class="carousel-caption">
                            <h1>Cita Rasa Asli Papua</h1>
                            <p>Dibuat dengan bahan-bahan lokal dan resep yang diwariskan turun-temurun</p>
                        </div>
                    </div>
                </div>
                <div class="carousel-controls">
                    <button class="carousel-btn prev">&#10094;</button>
                    <button class="carousel-btn next">&#10095;</button>
                </div>
                <div class="carousel-indicators">
                    <div class="indicator active" data-slide="0"></div>
                    <div class="indicator" data-slide="1"></div>
                    <div class="indicator" data-slide="2"></div>
                </div>
            </div>
        </div>

        <div class="container">
            <h2 class="section-title">Makanan Khas Papua Terfavorit</h2>
            <div class="featured-foods">
                <div class="food-card">
                    <div class="food-img" style="background-image: url('./img/papeda.jpg')"></div>
                    <div class="food-info">
                        <h3>Papeda</h3>
                        <p>Makanan pokok sagu khas Papua yang disajikan dengan kuah kuning dan ikan</p>
                    </div>
                </div>
                <div class="food-card">
                    <div class="food-img" style="background-image: url('./img/sagu.jpg')"></div>
                    <div class="food-info">
                        <h3>Sagu Bakar</h3>
                        <p>Olahan sagu yang dibungkus daun sagu dan dibakar dengan aroma khas</p>
                    </div>
                </div>
                <div class="food-card">
                    <div class="food-img" style="background-image: url('./img/udang.jpg')"></div>
                    <div class="food-info">
                        <h3>Udang Selingkuh</h3>
                        <p>Olahan udang khas Papua yang dimasak dengan bumbu dan rempah lokal</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about section">
        <div class="container">
            <h2 class="section-title">Makanan Khas Papua</h2>
            <div class="about-content">
                <div class="about-img" style="background-image: url('./img/papeda.jpg')"></div>
                <div class="about-info">
                    <!-- <h2>Cerita Makanan Khas Papua</h2> -->
                    <p>
                        Makanan Khas Papua adalah salah satu warisan budaya Indonesia yang kaya akan cita rasa dan
                        keunikan.
                        Makanan tradisional Papua banyak menggunakan bahan-bahan alami seperti sagu, ikan, dan
                        rempah-rempah lokal
                        yang memberikan aroma dan cita rasa khas. Sagu merupakan bahan pokok utama dalam Makanan Khas
                        Papua. Sagu
                        diolah menjadi berbagai macam makanan, mulai dari papeda (bubur sagu), sagu bakar, hingga
                        kue-kue
                        tradisional. Selain sagu, ikan dan hasil laut juga menjadi bahan utama dalam masakan Papua.
                        Makanan Khas
                        Papua juga mencerminkan kehidupan masyarakat Papua yang dekat dengan alam. Cara memasak
                        tradisional yang
                        masih menggunakan batu dan api langsung memberikan cita rasa yang tidak bisa ditemukan di tempat
                        lain.
                        Kami di Makanan Khas Papua berdedikasi untuk melestarikan dan memperkenalkan kekayaan Makanan
                        Khas Papua
                        kepada masyarakat luas. Semua makanan yang kami sajikan dibuat dengan resep turun-temurun dan
                        bahan-bahan
                        berkualitas.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- food Section -->
    <section id="food" class="food section">
        <div class="container">
            <h2 class="section-title">Makanan Khas Papua</h2>
            <div class="search-container">
                <input type="text" id="search-input" placeholder="Cari Makanan Khas Papua..." class="search-input" />
                <button id="search-btn" class="search-btn">🔍</button>
            </div>

            <div id="foodContainer" class="products">
                <?php
                require 'koneksi.php';

                $foods = [];
                $result = $conn->query("SELECT * FROM foods ORDER BY id DESC");
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $foods[] = $row;
                    }
                }
                ?>
                <div class="featured-foods">
                    <?php if (empty($foods)): ?>
                        <p>Tidak ada makanan tersedia.</p>
                    <?php endif; ?>
                    <?php foreach ($foods as $food): ?>
                        <div class="food-card">
                            <div class="food-img" style="background-image: url('<?= htmlspecialchars($food['image']) ?>')"></div>
                            <div class="food-info">
                                <h3><?= htmlspecialchars($food['name']) ?></h3>
                                <p><?= htmlspecialchars($food['description']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>

            <div id="no-results" class="no-results" style="display: none">
                Tidak ada Makanan Khas Papua yang sesuai dengan pencarian Anda.
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact section">
        <div class="container">
            <h2 class="section-title">Hubungi Kami</h2>
            <div class="contact-content">
                <div class="contact-info">
                    <h2>Informasi Kontak</h2>
                    <div class="contact-details">
                        <div>
                            <i>📍</i>
                            <p>Jl. Yos Sudarso No. 123, Jayapura, Papua</p>
                        </div>
                        <div>
                            <i>📱</i>
                            <p>+62 812-3456-7890</p>
                        </div>
                        <div>
                            <i>✉️</i>
                            <p>info@kulinerpapua.com</p>
                        </div>
                    </div>
                    <div class="business-hours">
                        <h3>Jam Operasional</h3>
                        <p>Senin - Jumat: 10:00 - 22:00</p>
                        <p>Sabtu - Minggu: 08:00 - 23:00</p>
                    </div>
                </div>
                <div class="contact-form">
                    <h2>Kirim Pesan</h2>
                    <form id="contactForm" action="proses.php?action=contact" method="POST">
                        <input type="hidden" name="contact_id" />
                        <input type="text" name="name" placeholder="Nama Lengkap" required />
                        <input type="email" name="email" placeholder="Email" required />
                        <input type="tel" name="telpon" placeholder="Nomor Telepon" />
                        <textarea rows="5" name="pesan" placeholder="Pesan" required></textarea>
                        <button type="submit" class="btn">Kirim Pesan</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Makanan Khas Papua</h3>
                    <p>Melestarikan dan memperkenalkan kekayaan</p>
                    <p>Makanan Khas Papua kepada masyarakat luas.</p>
                </div>
                <!-- <div class="footer-section">
            <h3>Links</h3>
            <ul>
              <li><a href="#home" class="nav-link" data-section="home">Home</a></li>
              <li><a href="#about" class="nav-link" data-section="about">About</a></li>
              <li><a href="#food" class="nav-link" data-section="food">Food</a></li>
              <li><a href="#contact" class="nav-link" data-section="contact">Contact</a></li>
            </ul>
          </div> -->
                <div class="footer-section">
                    <h3>Kontak</h3>
                    <p>Jl. Yos Sudarso No. 123, Jayapura, Papua</p>
                    <p>+62 812-3456-7890</p>
                    <p>info@kulinerpapua.com</p>
                </div>
            </div>
            <div class="social-links">
                <a href="#"><span>FB</span></a>
                <a href="#"><span>IG</span></a>
                <a href="#"><span>TW</span></a>
                <a href="#"><span>YT</span></a>
            </div>
            <div class="copyright">
                <p>&copy; 2025 Makanan Khas Papua</p>
            </div>
        </div>
    </footer>
</body>
<script type="application/javascript" src="./js/styles.js"></script>

</html>