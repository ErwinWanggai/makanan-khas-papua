<?php
include 'koneksi.php';
session_start(); // Start the session

// Check if the user is not logged in
if (empty($_SESSION['username'])) {
    $_SESSION['message'] = 'Silakan login terlebih dahulu';
    $_SESSION['message_type'] = 'error';
    header('Location: login.php');
    exit;
}

// Fetch food data
$sql = "SELECT id, name, description, image FROM foods";
$result = $conn->query($sql);
$foods = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $foods[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="./css/admin.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <style>
        /* Existing alert styles */
        .alert {
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            font-family: Arial, sans-serif;
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 100%;
            box-sizing: border-box;
            opacity: 1;
            transition: opacity 0.5s ease-out;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .alert-close {
            background: none;
            border: none;
            font-size: 16px;
            cursor: pointer;
            color: inherit;
            margin-left: 10px;
        }

        .alert-hidden {
            opacity: 0;
            display: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo">
                <div class="logo-img">
                    <img src="./img/logo.png" alt="Logo Kuliner Papua" />
                </div></i> Admin</h2>
            </div>
            <div class="nav-item active" onclick="showSection('dashboard', 'Dashboard')">
                <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
            </div>
            <div class="nav-item" onclick="showSection('food', 'Food Management')">
                <i class="fas fa-utensils"></i> <span>Food Management</span>
            </div>
            <div class="nav-item" onclick="showSection('contact', 'Contact Management')">
                <i class="fas fa-envelope"></i> <span>Contact Management</span>
            </div>
            <div class="nav-item" onclick="showSection('user', 'User Management')">
                <i class="fas fa-users"></i> <span>User Management</span>
            </div>
            <div class="nav-item">
                <a style="text-decoration: none; color: #FFF;" href="proses.php?action=logout"><i
                        class="fas fa-right-from-bracket"></i> <span>Logout</span></a>
            </div>
            <div class="theme-toggle" onclick="toggleDarkMode()">
                <i class="fas fa-moon"></i> <span>Dark Mode</span>
                <label class="switch">
                    <input type="checkbox" id="darkModeToggle" />
                    <span class="slider round"></span>
                </label>
            </div>
        </div>

        <div class="main-content">

            <?php
            if (isset($_SESSION['message']) && isset($_SESSION['message_type'])) {
                $message = htmlspecialchars($_SESSION['message']);
                $message_type = $_SESSION['message_type'];
                echo "<div class='alert alert-$message_type'>";
                echo $message;
                echo "<button class='alert-close' onclick='this.parentElement.classList.add(\"alert-hidden\")'>×</button>";
                echo "</div>";
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
            }
            ?>

            <div class="header">
                <h1 id="page-title">Dashboard</h1>
                <p id="welcome-message"></p>
            </div>

            <div id="dashboard" class="content-section active">
                <?php
                require 'koneksi.php';

                $total_food = 0;
                $total_contacts = 0;
                $total_users = 0;

                // Query jumlah total dari masing-masing tabel
                $food_result = $conn->query("SELECT COUNT(*) AS total FROM foods");
                if ($food_result) {
                    $total_food = $food_result->fetch_assoc()['total'];
                }

                $contact_result = $conn->query("SELECT COUNT(*) AS total FROM contacts");
                if ($contact_result) {
                    $total_contacts = $contact_result->fetch_assoc()['total'];
                }

                $user_result = $conn->query("SELECT COUNT(*) AS total FROM users");
                if ($user_result) {
                    $total_users = $user_result->fetch_assoc()['total'];
                }
                ?>
                <div class="dashboard-cards">
                    <div class="card">
                        <h3 id="total-food"><?= $total_food ?></h3>
                        <p>Total Food Items</p>
                    </div>
                    <div class="card">
                        <h3 id="total-contacts"><?= $total_contacts ?></h3>
                        <p>Total Contacts</p>
                    </div>
                    <div class="card">
                        <h3 id="total-users"><?= $total_users ?></h3>
                        <p>Total Users</p>
                    </div>
                </div>

            </div>

            <div id="food" class="content-section">
                <div class="form-container">
                    <h3>Tambah/Edit Food Item</h3>
                    <form id="food-form" action="proses.php?action=food" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="food-id" name="food_id" />
                        <div class="form-group">
                            <label for="food-name">Nama Food:</label>
                            <input type="text" id="food-name" name="food_name" required />
                        </div>
                        <div class="form-group">
                            <label for="food-description">Deskripsi:</label>
                            <textarea id="food-description" name="food_description" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="food-image-upload">Gambar:</label>
                            <input type="file" id="food-image-upload" name="food_image" accept="image/*" />
                            <img id="food-image-preview" class="food-image-preview" src="" alt="Pratinjau Gambar"
                                style="display: none" />
                        </div>
                        <button type="submit" class="btn btn-success">Simpan</button>
                        <button type="button" class="btn" onclick="resetFoodForm()">Reset</button>
                    </form>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Nama</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="food-table-body">
                            <?php
                            $i = 1;
                            if (count($foods) > 0) :
                                foreach ($foods as $food): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($i++); ?></td>
                                        <td>
                                            <?php if ($food['image']): ?>
                                                <img style="width: 100px; height: 100px;" src="<?= ($food['image']); ?>"
                                                    alt="Gambar Makanan" class="food-image" />
                                            <?php else: ?>
                                                Tidak ada gambar
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($food['name']); ?></td>
                                        <td><?= htmlspecialchars($food['description'] ?? ''); ?></td>
                                        <td>
                                            <!-- Gunakan JSON untuk data edit agar lebih aman -->
                                            <button class="btn btn-success" onclick='editFood(<?= json_encode([
                                                                                                    "id" => $food['id'],
                                                                                                    "name" => $food['name'],
                                                                                                    "description" => $food['description'] ?? '',
                                                                                                    "image" => $food['image'] ?? ''
                                                                                                ]) ?>)'>
                                                Edit
                                            </button>
                                            <form action="proses.php?action=delete_food" method="POST" style="display: inline;"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus makanan ini?');">
                                                <input type="hidden" name="food_id"
                                                    value="<?= htmlspecialchars($food['id']); ?>">
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php
                                endforeach;
                            else :
                                ?>
                                <tr>
                                    <td colspan="6" style="text-align: center;">Tidak ada data </td>
                                </tr>
                            <?php
                            endif
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="contact" class="content-section">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Pesan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="contact-table-body">
                            <?php
                            $i = 1;
                            require_once 'koneksi.php'; // Pastikan koneksi database tersedia
                            $sql = "SELECT * FROM contacts";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($contact = $result->fetch_assoc()) {
                            ?>
                                    <tr>
                                        <td><?= htmlspecialchars($i++); ?></td>
                                        <td><?= htmlspecialchars($contact['full_name']); ?></td>
                                        <td><?= htmlspecialchars($contact['email']); ?></td>
                                        <td><?= htmlspecialchars($contact['phone'] ?? ''); ?></td>
                                        <td><?= htmlspecialchars($contact['message']); ?></td>
                                        <td>
                                            <!-- <button class="btn btn-success" onclick='editContact(<?= json_encode([
                                                                                                            "id" => $contact['id'],
                                                                                                            "name" => $contact['name'],
                                                                                                            "email" => $contact['email'],
                                                                                                            "telpon" => $contact['telpon'] ?? '',
                                                                                                            "pesan" => $contact['pesan']
                                                                                                        ]); ?>)'>Edit</button> -->
                                            <form action="proses.php?action=contact_delete" method="POST"
                                                style="display: inline;"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan kontak ini?');">
                                                <input type="hidden" name="contact_id"
                                                    value="<?= htmlspecialchars($contact['id']); ?>">
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="6" style="text-align: center;">Tidak ada data kontak</td>
                                </tr>
                            <?php
                            }
                            $result->free();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="user" class="content-section">
                <div class="form-container">
                    <h3>Tambah/Edit User</h3>
                    <form id="user-form" method="POST" action="proses.php?action=user">
                        <input type="hidden" name="user_id" id="user-id" />
                        <div class="form-row">
                            <div class="form-group">
                                <label for="user-username">Username:</label>
                                <input type="text" name="username" id="user-username" required />
                            </div>
                            <div class="form-group">
                                <label for="user-password">Password:</label>
                                <input type="password" name="password" id="user-password" required />
                                <p style="color: red; display: none;" id="warning-user">Masukkan Password Baru*</p>
                            </div>
                        </div>
                        <button id="btn-user" type="submit" class="btn btn-success">Simpan</button>
                        <button type="button" class="btn" onclick="resetUserForm()">Reset</button>
                    </form>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include 'koneksi.php'; // koneksi ke database

                            $i = 1;
                            $sql = "SELECT * FROM users";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0):
                                while ($row = $result->fetch_assoc()):
                            ?>
                                    <tr>
                                        <td><?= htmlspecialchars($i++) ?></td>
                                        <td><?= htmlspecialchars($row['username']) ?></td>
                                        <td>
                                            <!-- Gunakan JSON untuk data edit user agar lebih aman -->
                                            <button class="btn btn-success" onclick='editUser(<?= json_encode([
                                                                                                    "id" => $row['id'],
                                                                                                    "username" => $row['username'],
                                                                                                ]) ?>)'>
                                                Edit
                                            </button>

                                            <form action="proses.php?action=delete_user" method="POST" style="display: inline;"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                                                <input type="hidden" name="user_id" value="<?= htmlspecialchars($row['id']); ?>">
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php
                                endwhile;
                            else:
                                ?>
                                <tr>
                                    <td colspan="4">Tidak ada data pengguna</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Makanan Khas Papua.</p>
    </footer>
</body>
<script>
    // Auto-hilangkan alert setelah 5 detik
    document.addEventListener('DOMContentLoaded', () => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.classList.add('alert-hidden');
            }, 5000);
        });
    });

    function previewImage(input) {
        const preview = document.getElementById('food-image-preview');
        if (input.files && input.files[0]) {
            // Validasi ukuran file (2MB)
            if (input.files[0].size > 2 * 1024 * 1024) {
                alert('Ukuran gambar terlalu besar (maksimal 2MB)');
                input.value = '';
                preview.style.display = 'none';
                return;
            }
            // Validasi tipe file
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(input.files[0].type)) {
                alert('Hanya file JPG, PNG, atau GIF yang diperbolehkan');
                input.value = '';
                preview.style.display = 'none';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
        }
    }

    function editUser(user) {
        document.getElementById('user-id').value = user.id;
        document.getElementById('user-username').value = user.username;
        document.getElementById('btn-user').textContent = 'Update';
        document.getElementById('warning-user').style.display = "block";
        // Jangan isi password untuk alasan keamanan
    }

    function resetFoodForm() {
        document.getElementById("food-form").reset();
        document.getElementById("food-id").value = "";
        document.getElementById("food-image-preview").style.display = "none";
    }

    function editFood(food) {
        document.getElementById('food-id').value = food.id;
        document.getElementById('food-name').value = food.name;
        document.getElementById('food-description').value = food.description;
        const preview = document.getElementById('food-image-preview');
        if (food.image) {
            preview.src = food.image;
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
        document.getElementById('food-image-upload').required = false; // Gambar tidak wajib saat edit
    }
</script>
<script type="application/javascript" src="js/admin.js"></script>


</html>