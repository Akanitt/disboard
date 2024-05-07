<?php
    require '../config/database.php';
    if (isset($_SESSION['user'])) {
        header('Location: index.php'); 
    }
    $title = $_SESSION['signup']['title'] ?? null;
    $firstname = $_SESSION['signup']['firstname'] ?? null;
    $lastname = $_SESSION['signup']['lastname'] ?? null;
    $username = $_SESSION['signup']['username'] ?? null;
    $email = $_SESSION['signup']['email'] ?? null;
    $password = $_SESSION['signup']['password'] ?? null;
    $role = $_SESSION['signup']['role'] ?? null;
    $avatar = $_SESSION['signup']['avatar'] ?? null;
    unset($_SESSION['signup']);
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
    <head>
        <script src="../assets/js/color-modes.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../assets/css/checkout.css" rel="stylesheet">
        <title>Sign Up - AIIH</title>
    </head>
    <body class="bg-body-tertiary">
        <div class="container">
            <main>
                <div class="py-5 text-center">
                    <img class="d-block mx-auto mb-4" src="../assets/images/AIIH.svg" alt="">
                    <h2 class="h3 mb-3 fw-normal text-center">Sign Up</h2>
                    <?php if(isset($_SESSION['signup-failed'])): ?>
                    <div class="alert alert-danger text-center" role="alert">
                        <?= $_SESSION['signup-failed'];
                        unset($_SESSION['signup-failed']);
                        ?>
                    </div>
                    <?php endif ?>

                </div>
                <div class="row g-5">
                    <div class="col-md-7 col-lg-12">
                        <form novalidate action="../controllers/signup.php" enctype="multipart/form-data" method="POST">
                            <div class="row g-3">
                                <div class="col-sm-2">
                                    <label for="title" class="form-label">Title <span class="text-body-secondary">(Optional)</span></label>
                                    <input type="text" class="form-control" id="title" name="title" value='<?=$title?>' placeholder="Title">
                                </div>
                                <div class="col-sm-5">
                                    <label for="firstname" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" value='<?=$firstname?>' placeholder="First Name" required>
                                    <?php if(isset($_SESSION['firstname-error'])): ?>
                                    <div class="invalid-feedback d-block" role="alert">  
                                        <?=$_SESSION['firstname-error'];
                                        unset($_SESSION['firstname-error']);
                                        ?>
                                    </div>
                                    <?php endif ?>
                                </div>
                                <div class="col-sm-5">
                                    <label for="lastname" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" value='<?=$lastname?>' placeholder="Last Name" required>
                                    <?php if(isset($_SESSION['lastname-error'])): ?>
                                    <div class="invalid-feedback d-block" role="alert">  
                                        <?=$_SESSION['lastname-error'];
                                        unset($_SESSION['lastname-error']);
                                        ?>
                                    </div>
                                    <?php endif ?>
                                </div>
                                <div class="col-6">
                                    <label for="username" class="form-label">Username</label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text">@</span>
                                        <input type="text" class="form-control" id="username" name="username" value='<?=$username?>' placeholder="Username" required>
                                        
                                    </div>
                                    <?php if(isset($_SESSION['username-error'])): ?>
                                    <div class="invalid-feedback d-block" role="alert">  
                                        <?=$_SESSION['username-error'];
                                        unset($_SESSION['username-error']);
                                        ?>
                                    </div>
                                    <?php endif ?>
                                </div>
                                <div class="col-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value='<?=$email?>' placeholder="Email" required>
                                    <?php if(isset($_SESSION['email-error'])): ?>
                                    <div class="invalid-feedback d-block" role="alert">  
                                        <?=$_SESSION['email-error'];
                                        unset($_SESSION['email-error']);
                                        ?>
                                    </div>
                                    <?php endif ?>
                                </div>
                                <div class="col-6">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" value='<?=$password?>' placeholder="Password" required>
                                    <?php if(isset($_SESSION['password-error'])): ?>
                                    <div class="invalid-feedback d-block" role="alert">  
                                        <?=$_SESSION['password-error'];
                                        unset($_SESSION['password-error']);
                                        ?>
                                    </div>
                                    <?php endif ?>
                                </div>
                                <div class="col-6">
                                    <label for="avatar" class="form-label">User Avatar</label>
                                    <input type="file" class="form-control" id="avatar" name="avatar">
                                    <?php if(isset($_SESSION['image-error'])): ?>
                                    <div class="invalid-feedback d-block" role="alert">  
                                        <?=$_SESSION['image-error'];
                                        unset($_SESSION['image-error']);
                                        ?>
                                    </div>
                                    <?php endif ?>
                                </div>
                                <hr class="my-4">
                                <div class="col-6">
                                    <button class="w-100 btn btn-primary btn-lg" type="submit" name="submit" value="doctor">Doctor</button>
                                </div>
                                <div class="col-6">
                                    <button class="w-100 btn btn-secondary btn-lg" type="submit" name="submit" value="professor">Professor</button>
                                </div>
                                <p class="mt-3 mb-3 text-body-secondary text-center">Already have an Account? <a href="login.php">Sign in</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
        <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
