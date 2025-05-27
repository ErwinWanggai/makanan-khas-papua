<?php
session_start();
include 'koneksi.php';

// Validate action
$valid_actions = ['login', 'user', 'logout', 'food', 'delete_food', 'contact', 'contact_delete', 'delete_user'];
$action = isset($_GET['action']) && in_array($_GET['action'], $valid_actions) ? $_GET['action'] : '';

$message = '';
$message_type = '';

switch ($action) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $username = isset($_POST['username']) ? $_POST['username'] : '';
                $password = isset($_POST['password']) ? $_POST['password'] : '';

                if (empty($username) || empty($password)) {
                    $message = 'Username dan kata sandi harus diisi';
                    $message_type = 'error';
                } else {
                    $sql = "SELECT id, username, password FROM users WHERE username = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('s', $username);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $user = $result->fetch_assoc();
                        if (password_verify($password, $user['password'])) {
                            session_regenerate_id(true);
                            $_SESSION['user_id'] = $user['id'];
                            $_SESSION['username'] = $user['username'];
                            $message = 'Login berhasil';
                            $message_type = 'success';
                            $_SESSION['message'] = $message;
                            $_SESSION['message_type'] = $message_type;
                            header('Location: admin.php');
                            exit;
                        } else {
                            $message = 'Kata sandi salah';
                            $message_type = 'error';
                        }
                    } else {
                        $message = 'Pengguna tidak ditemukan';
                        $message_type = 'error';
                    }
                    $stmt->close();
                }
        } else {
            $message = 'Metode permintaan tidak valid';
            $message_type = 'error';
        }
        break;

    case 'user':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user_id'])) {
                $_SESSION['message'] = 'Silakan login terlebih dahulu';
                $_SESSION['message_type'] = 'error';
                header('Location: login.php');
                exit;
            }

            $user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
            $username = isset($_POST['username']) ? trim($_POST['username']) : '';
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';

            if (empty($username)) {
                $message = 'Username harus diisi';
                $message_type = 'error';
            } elseif ($user_id === 0 && empty($password)) {
                $message = 'Password harus diisi untuk pengguna baru';
                $message_type = 'error';
            } elseif (!empty($password) && strlen($password) < 6) {
                $message = 'Kata sandi harus minimal 6 karakter';
                $message_type = 'error';
            } else {
                if ($user_id > 0) {
                    // Edit pengguna
                    if (!empty($password)) {
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        $sql = "UPDATE users SET username = ?, password = ? WHERE id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param('ssi', $username, $hashed_password, $user_id);
                    } else {
                        $sql = "UPDATE users SET username = ? WHERE id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param('si', $username, $user_id);
                    }
                    if ($stmt->execute()) {
                        $message = 'Pengguna berhasil diperbarui';
                        $message_type = 'success';
                    } else {
                        $message = 'Terjadi kesalahan saat memperbarui pengguna';
                        $message_type = 'error';
                    }
                } else {
                    // Tambah pengguna baru
                    $check_sql = "SELECT id FROM users WHERE username = ?";
                    $check_stmt = $conn->prepare($check_sql);
                    $check_stmt->bind_param('s', $username);
                    $check_stmt->execute();
                    $result = $check_stmt->get_result();
                    if ($result->num_rows > 0) {
                        $message = 'Username sudah digunakan';
                        $message_type = 'error';
                    } else {
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param('ss', $username, $hashed_password);
                        if ($stmt->execute()) {
                            $message = 'Pengguna berhasil ditambahkan';
                            $message_type = 'success';
                        } else {
                            $message = 'Terjadi kesalahan saat menambahkan pengguna';
                            $message_type = 'error';
                        }
                    }
                    $check_stmt->close();
                }
                if (isset($stmt)) $stmt->close();
            }
        } else {
            $message = 'Metode permintaan tidak valid';
            $message_type = 'error';
        }
        $_SESSION['message'] = $message;
        $_SESSION['message_type'] = $message_type;
        header('Location: admin.php');
        exit;

    case 'delete_user':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user_id'])) {
                $_SESSION['message'] = 'Silakan login terlebih dahulu';
                $_SESSION['message_type'] = 'error';
                header('Location: login.php');
                exit;
            }

            $user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;

            if ($user_id <= 0) {
                $message = 'ID pengguna tidak valid';
                $message_type = 'error';
            } else {
                $sql = "DELETE FROM users WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('i', $user_id);
                if ($stmt->execute()) {
                    $message = 'Pengguna berhasil dihapus';
                    $message_type = 'success';
                } else {
                    $message = 'Gagal menghapus pengguna';
                    $message_type = 'error';
                }
                $stmt->close();
            }
        } else {
            $message = 'Metode permintaan tidak valid';
            $message_type = 'error';
        }
        $_SESSION['message'] = $message;
        $_SESSION['message_type'] = $message_type;
        header('Location: admin.php');
        exit;


    case 'logout':
            session_unset();
            session_destroy();
            $message = 'Logout berhasil';
            $message_type = 'success';
            $_SESSION['message'] = $message;
            $_SESSION['message_type'] = $message_type;
            header('Location: login.php');
            exit;
        break;

    case 'food':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user_id'])) {
                $message = 'Silakan login terlebih dahulu';
                $message_type = 'error';
                header('Location: login.php');
                exit;
            } else {
                $food_id = isset($_POST['food_id']) ? (int)$_POST['food_id'] : 0;
                $food_name = isset($_POST['food_name']) ? trim($_POST['food_name']) : '';
                $food_description = isset($_POST['food_description']) ? trim($_POST['food_description']) : '';
                $image_path = '';

                // Validate inputs
                if (empty($food_name)) {
                    $message = 'Nama makanan harus diisi';
                    $message_type = 'error';
                } else {
                    // Jika update, ambil path gambar lama
                    $old_image_path = '';
                    if ($food_id > 0) {
                        $sql = "SELECT image FROM foods WHERE id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param('i', $food_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($row = $result->fetch_assoc()) {
                            $old_image_path = $row['image'];
                        }
                        $stmt->close();
                    }

                    // Handle file upload
                    if (isset($_FILES['food_image']) && $_FILES['food_image']['error'] !== UPLOAD_ERR_NO_FILE) {
                        $file = $_FILES['food_image'];
                        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                        $max_size = 2 * 1024 * 1024; // 2MB

                        if (!in_array($file['type'], $allowed_types)) {
                            $message = 'Format gambar tidak valid (hanya JPG, PNG, GIF)';
                            $message_type = 'error';
                        } elseif ($file['size'] > $max_size) {
                            $message = 'Ukuran gambar terlalu besar (maksimal 2MB)';
                            $message_type = 'error';
                        } else {
                            $upload_dir = 'uploads/';
                            if (!is_dir($upload_dir)) {
                                mkdir($upload_dir, 0755, true);
                            }
                            $image_name = uniqid() . '_' . basename($file['name']);
                            $image_path = $upload_dir . $image_name;

                            if (move_uploaded_file($file['tmp_name'], $image_path)) {
                                // Hapus gambar lama jika ada dan berbeda
                                if (!empty($old_image_path) && file_exists($old_image_path)) {
                                    unlink($old_image_path);
                                }
                            } else {
                                $message = 'Gagal mengunggah gambar';
                                $message_type = 'error';
                            }
                        }
                    } else {
                        // Jika tidak ada upload baru, gunakan gambar lama
                        $image_path = $old_image_path;
                    }

                    // Simpan ke database
                    if (empty($message)) {
                        if ($food_id > 0) {
                            $sql = "UPDATE foods SET name = ?, description = ?, image = ? WHERE id = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param('sssi', $food_name, $food_description, $image_path, $food_id);
                        } else {
                            $sql = "INSERT INTO foods (name, description, image) VALUES (?, ?, ?)";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param('sss', $food_name, $food_description, $image_path);
                        }

                        if ($stmt->execute()) {
                            $message = $food_id > 0 ? 'Makanan berhasil diperbarui' : 'Makanan berhasil ditambahkan';
                            $message_type = 'success';
                        } else {
                            error_log('Database error: ' . $conn->error);
                            $message = 'Terjadi kesalahan saat menyimpan makanan';
                            $message_type = 'error';
                        }
                        $stmt->close();
                    }
                }
            }
        } else {
            $message = 'Metode permintaan tidak valid';
            $message_type = 'error';
        }
        $_SESSION['message'] = $message;
        $_SESSION['message_type'] = $message_type;
        header('Location: admin.php');
        exit;


    case 'contact':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ambil dan bersihkan data input
            $full_name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['telpon'] ?? '');
            $message = trim($_POST['pesan'] ?? '');

            // Validasi input
            if (empty($full_name) || empty($email) || empty($message)) {
                $_SESSION['message'] = 'Nama, email, dan pesan wajib diisi.';
                $_SESSION['message_type'] = 'error';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['message'] = 'Format email tidak valid.';
                $_SESSION['message_type'] = 'error';
            } else {
                // Simpan ke database
                $sql = "INSERT INTO contacts (full_name, email, phone, message) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ssss', $full_name, $email, $phone, $message);

                if ($stmt->execute()) {
                    $_SESSION['message'] = 'Pesan berhasil dikirim.';
                    $_SESSION['message_type'] = 'success';
                } else {
                    error_log("Database error: " . $conn->error);
                    $_SESSION['message'] = 'Gagal mengirim pesan.';
                    $_SESSION['message_type'] = 'error';
                }

                $stmt->close();
            }
        } else {
            $_SESSION['message'] = 'Metode tidak diizinkan.';
            $_SESSION['message_type'] = 'error';
        }

        // Redirect kembali ke halaman kontak atau beranda
        header('Location: index.php');
        exit;


    case 'delete_food':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user_id'])) {
                $_SESSION['message'] = 'Silakan login terlebih dahulu';
                $_SESSION['message_type'] = 'error';
                header('Location: login.php');
                exit;
            }
                $food_id = isset($_POST['food_id']) ? (int)$_POST['food_id'] : 0;
                // Validasi food_id
                if ($food_id <= 0) {
                    $message = 'ID makanan tidak valid';
                    $message_type = 'error';
                } else {
                    // Ambil path gambar dari database
                    $sql = "SELECT image FROM foods WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('i', $food_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows === 0) {
                        $message = 'Makanan tidak ditemukan';
                        $message_type = 'error';
                    } else {
                        $food = $result->fetch_assoc();
                        $image_path = $food['image'];

                        // Hapus data dari database
                        $sql = "DELETE FROM foods WHERE id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param('i', $food_id);

                        if ($stmt->execute()) {
                            // Hapus file gambar dari server jika ada
                            if (!empty($image_path) && file_exists($image_path)) {
                                if (!unlink($image_path)) {
                                    error_log('Gagal menghapus gambar: ' . $image_path);
                                }
                            }
                            $message = 'Makanan berhasil dihapus';
                            $message_type = 'success';
                        } else {
                            error_log('Database error: ' . $conn->error);
                            $message = 'Gagal menghapus makanan';
                            $message_type = 'error';
                        }
                    }
                    $stmt->close();
                }
        } else {
            $message = 'Metode permintaan tidak valid';
            $message_type = 'error';
        }
        $_SESSION['message'] = $message;
        $_SESSION['message_type'] = $message_type;
        header('Location: admin.php');
        exit;
        break;

        case 'contact':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $full_name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['telpon'] ?? '');
            $message = trim($_POST['pesan'] ?? '');

            // Validasi input
            if (empty($full_name) || empty($email) || empty($message)) {
                $_SESSION['message'] = 'Nama, email, dan pesan harus diisi';
                $_SESSION['message_type'] = 'error';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['message'] = 'Format email tidak valid';
                $_SESSION['message_type'] = 'error';
            } else {
                // Tambah kontak
                $sql = "INSERT INTO contacts (full_name, email, phone, message) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ssss', $full_name, $email, $phone, $message);

                if ($stmt->execute()) {
                    $_SESSION['message'] = 'Pesan kontak berhasil ditambahkan';
                    $_SESSION['message_type'] = 'success';
                } else {
                    error_log('Database error: ' . $conn->error);
                    $_SESSION['message'] = 'Gagal menyimpan pesan kontak';
                    $_SESSION['message_type'] = 'error';
                }
                $stmt->close();
            }
        } else {
            $_SESSION['message'] = 'Metode permintaan tidak valid';
            $_SESSION['message_type'] = 'error';
        }
        header('Location: index.php');
        exit;
        break;


    
    
        case 'contact_delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['user_id'])) {
                $_SESSION['message'] = 'Silakan login terlebih dahulu';
                $_SESSION['message_type'] = 'error';
                header('Location: login.php');
                exit;
            }

            $contact_id = (int)($_POST['contact_id'] ?? 0);

            // Validasi contact_id
            if ($contact_id <= 0) {
                $_SESSION['message'] = 'ID kontak tidak valid';
                $_SESSION['message_type'] = 'error';
            } else {
                // Hapus dari database
                $sql = "DELETE FROM contacts WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('i', $contact_id);

                if ($stmt->execute()) {
                    $_SESSION['message'] = 'Pesan kontak berhasil dihapus';
                    $_SESSION['message_type'] = 'success';
                } else {
                    error_log('Database error: ' . $conn->error);
                    $_SESSION['message'] = 'Gagal menghapus pesan kontak';
                    $_SESSION['message_type'] = 'error';
                }
                $stmt->close();
            }
        } else {
            $_SESSION['message'] = 'Metode permintaan tidak valid';
            $_SESSION['message_type'] = 'error';
        }
        $_SESSION['message'] = $message;
        $_SESSION['message_type'] = $message_type;
        header('Location: admin.php');
        exit;
        break;

    default:
        $message = 'Aksi tidak valid';
        $message_type = 'error';
        break;
}

    if (!empty($message)) {
        $_SESSION['message'] = $message;
        $_SESSION['message_type'] = $message_type;
        header('Location: login.php');
        exit;
    }

$conn->close();
?>