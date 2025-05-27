<?php 
session_start();

// Check if user is logged in
if (!empty($_SESSION['username'])) {
    $_SESSION['message'] = 'Anda telah login';
    $_SESSION['message_type'] = 'success';
    header('Location: admin.php');
    exit;
} else {
    // Optional: Set a message if the user needs to log in
    if (!isset($_SESSION['message'])) {
        $_SESSION['message'] = 'Silakan login untuk mengakses dashboard';
        $_SESSION['message_type'] = 'info';
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Form</title>
    <link rel="stylesheet" href="./css/main.css" />
    <style>
    /* Gaya untuk alert */
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
    <div class="login-container">
        <div class="logo">
            <div class="logo-img">
                <img src="./img/logo.png" alt="Logo Kuliner Papua" />
            </div>
        </div>
        <h2>Silahkan Login</h2>

        <?php
        // Tampilkan pesan jika ada
        if (isset($_SESSION['message']) && isset($_SESSION['message_type'])) {
            $message = htmlspecialchars($_SESSION['message']);
            $message_type = $_SESSION['message_type'];
            echo "<div class='alert alert-$message_type'>";
            echo $message;
            echo "<button class='alert-close' onclick='this.parentElement.classList.add(\"alert-hidden\")'>×</button>";
            echo "</div>";
            // Hapus pesan dari session setelah ditampilkan
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        }
        ?>

        <form id="loginForm" action="proses.php?action=login" method="POST">
            <div class="input-group">
                <input type="text" id="username" name="username" placeholder="Username" required />
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
            </div>

            <div class="input-group">
                <input type="password" id="password" name="password" placeholder="Password" required />
                <div class="icon password-toggle" id="togglePassword">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        id="eyeIcon">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </div>
            </div>
            <button type="submit" class="btn-login">
                <span>Login</span>
                <span class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                </span>
            </button>
        </form>
    </div>

    <script src="js/main.js"></script>
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
    </script>
</body>

</html>