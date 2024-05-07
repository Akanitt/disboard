<?php
    require '../config/database.php';
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
    <head>
        <script src="../assets/js/color-modes.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .bi {
                vertical-align: -.125em;
                fill: currentColor;
            }
        </style>
        <link href="../assets/css/headers.css" rel="stylesheet">
    </head>
    <body>
        <header class="p-3 mb-3 border-bottom" data-bs-toggle="auto">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                    <a href="index.php" class="d-flex align-items-center mb-2 mb-lg-0 link-body-emphasis text-decoration-none">
                        <img class="mb-2" src="../assets/images/AIIH.svg" alt="">
                    </a>
                    <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    </ul>
                    
                    
                    
                    <div class="dropdown text-end">
                        <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="../uploads/<?=$_SESSION['user']['avatar']?>" alt="" width="32" height="32" class="rounded-circle">
                        </a>
                        <ul class="dropdown-menu text-small">
                            <li><a class="dropdown-item" href="add_post.php">Add Post</a></li>
                            <li></li><a class="dropdown-item" href="profile.php?id=<?=$_SESSION['user']['id']?>">Profile</a></li>
                            <li><a class="dropdown-item" href="users_list.php">Users</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                            <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>
    </body>
    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</html>
