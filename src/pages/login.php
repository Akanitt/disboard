<?php
    require '../config/database.php';
    if (isset($_SESSION['user'])) {
        header('Location: index.php'); 
    }
    $username = $_SESSION['signin']['username'] ?? null;
    $password = $_SESSION['signin']['password'] ?? null;
    unset($_SESSION['signin']);
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
    <head>
        <script src="../assets/js/color-modes.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../assets/css/sign-in.css" rel="stylesheet">
        <title>Sign In - AIIH</title>
    </head>
    <body class="d-flex align-items-center py-4 bg-body-tertiary">
        <main class="form-signin w-100 m-auto">
            <form action="../controllers/login.php" method="POST">
                <div class="d-flex justify-content-center align-items-center">
                    <img class="mb-4" src="../assets/images/AIIH.svg" alt="">
                </div>
                <h1 class="h3 mb-3 fw-normal text-center">Sign In</h1>
                <?php if(isset($_SESSION['signup-success'])): ?>
                <div class="valid-feedback d-block text-center" role="alert">
                    <?= $_SESSION['signup-success'];
                    unset($_SESSION['signup-success']);
                    ?>
                </div>
                <?php endif ?>
                <div class="form-floating mt-2">
                    <input type="text" class="form-control" id="floatingInput" name="username" value='<?=$username?>' placeholder="Username or Email">
                    <label for="floatingInput">Username or Email</label>
                    <?php if(isset($_SESSION['username-error'])): ?>
                    <div class="invalid-feedback d-block" role="alert">  
                        <?=$_SESSION['username-error'];
                        unset($_SESSION['username-error']);
                        ?>
                    </div>
                    <?php endif ?>
                </div>
                <div class="form-floating mt-2">
                    <input type="password" class="form-control" id="floatingPassword" name="password" value='<?=$password?>' placeholder="Password">
                    <label for="floatingPassword">Password</label>
                    <?php if(isset($_SESSION['password-error'])): ?>
                    <div class="invalid-feedback d-block" role="alert">  
                        <?=$_SESSION['password-error'];
                        unset($_SESSION['password-error']);
                        ?>
                    </div>
                    <?php endif ?>
                </div>
                <div class="form-floating mt-2">
                    <button class="btn btn-primary w-100 py-2" type="submit" name="submit">Sign in</button>
                </div>
                <p class="mt-3 mb-3 text-body-secondary text-center">Don't have an account? <a href="signup.php">Sign up</a></p>
            </form>
        </main>
        <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    </body>
</html>