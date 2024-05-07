<?php
    include 'header.php';
    $query = "SELECT * FROM users WHERE id = :id";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':id', $_SESSION['user']['id']);
    $stmt->execute();
    $user = $stmt->fetch();
?>
<link href="../assets/css/sign-in.css" rel="stylesheet">

    <main class="form-signin w-100 m-auto">
        <form action="../controllers/settings.php" method="POST">
            <div class="d-flex justify-content-center align-items-center">
                <img class="mb-4" src="../assets/images/AIIH.svg" alt="">
            </div>
            <h1 class="h3 mb-3 fw-normal text-center">Settings</h1>
            <?php if(isset($_SESSION['signup-success'])): ?>
            <div class="alert alert-success text-center" role="alert">
                <?= $_SESSION['signup-success'];
                unset($_SESSION['signup-success']);
                ?>
            </div>
            <?php endif ?>
            <div class="form-floating mt-2">
                <input type="text" class="form-control" id="floatingInput" name="email" value='<?=$user['email']?>' placeholder="Email">
                <label for="floatingInput">Email</label>
                <?php if(isset($_SESSION['email-error'])): ?>
                <div class="invalid-feedback d-block" role="alert">  
                    <?=$_SESSION['email-error'];
                    unset($_SESSION['email-error']);
                    ?>
                </div>
                <?php endif ?>
            <div class="form-floating mt-2">
                <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password">
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
                <input type="password" class="form-control" id="floatingPassword" name="confirm-password" placeholder="Confirm Password">
                <label for="floatingPassword">Confirm Password</label>
                <?php if(isset($_SESSION['confirm-password-error'])): ?>
                <div class="invalid-feedback d-block" role="alert">  
                    <?=$_SESSION['confirm-password-error'];
                    unset($_SESSION['confirm-password-error']);
                    ?>
                </div>
                <?php endif ?>
            <div class="form-floating mt-2">
                <button class="btn btn-primary w-100 py-2" type="submit" name="submit">Change</button>
            </div>
        </form>
    </main>

