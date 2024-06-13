<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAMER SOCIETY</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        @font-face {
            font-family: 'Bw Haas Grotesk';
            src: url('path/to/BwHaasGrotesk-Regular.woff2') format('woff2');
        }
        body {
            font-family: 'Bw Haas Grotesk', Arial, sans-serif;
            background-color: #000000;
            color: #000;
        }
        .navbar {
            background-color: #191919;
            border-bottom: 1px solid #333;
            padding: 15px 0;
        }
        .navbar-brand {
            color: #BED754 !important;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .nav-link {
            color: #BED754 !important;
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
            margin-left: 15px;
        }
        .nav-item.dropdown {
            border: 1px solid #fff;
            padding: 3px 8px;
            border-radius: 3px;
        }
        .nav-link.dropdown-toggle::after {
            margin-left: 8px;
        }
        .dropdown-menu {
            background-color: #000;
            border: 1px solid #333;
        }
        .dropdown-item {
            color: #fff;
            font-size: 14px;
        }
        .dropdown-item:hover {
            background-color: #333;
        }
        @media (max-width: 767px) {
            .navbar-brand {
                font-size: 24px;
            }
            .nav-link {
                font-size: 12px;
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="../index.php">GAMER SOCIETY</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['username'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-user mr-2"></i><?php echo $_SESSION['username']; ?>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="<?php echo $_SESSION['role'] === 'admin' ? '/gamersociety/views/admin/dashboard.php' : ($_SESSION['role'] === 'author' ? '/gamersociety/views/author/dashboard.php' : '/gamersociety/views/user/dashboard.php'); ?>">DASHBOARD</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/gamersociety/controllers/auth/logoutcontroller.php">LOGOUT</a>
                            </div>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/gamersociety/views/auth.php">LOGIN / SIGN UP</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <script src="/gamersociety/assets/js/fetchPrices.js"></script>

</body>
</html>



