<?php
    include 'header.php';
    $userId = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : $_GET['id'];
    if ($_SESSION['user']['id'] != $userId) {
        header('Location: profile.php?id=' . $_SESSION['user']['id']);
        exit();
    }
    if (!isset($_SESSION['user']) || !isset($userId)) {
        header('Location: users_list.php');
        exit();
    }
    
    $query = "SELECT * FROM users WHERE id = :id";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':id', $userId);
    $stmt->execute();
    $user= $stmt->fetch();
    if (!$user) {
        header('Location: users_list.php');
        exit();
    }

    
?>
<link href="../assets/css/checkout.css" rel="stylesheet">
<script src="../assets/js/jquery.min.js"></script>
<title>Edit Profile</title>
<body class="bg-body-tertiary">
    <div class="container">
        <main>
            <div class="py-2 text-center">
                <h2 class="h3 mb-3 fw-normal text-center">Edit Profile</h2>
                <?php if(isset($_SESSION['edit-profile'])): ?>
                <div class="alert alert-danger text-center" role="alert">
                    <?= $_SESSION['edit-profile'];
                    unset($_SESSION['edit-profile']);
                    ?>
                </div>
                <?php endif ?>
            </div>
            <div class="row g-5">
                <div class="col-md-7 col-lg-12">
                    <form novalidate action="../controllers/edit_profile.php" enctype="multipart/form-data" method="POST">
                        <div class="text-center">
                            <label for="avatar">
                                <img class="d-block mx-auto mb-1 avatar-image" src="../uploads/<?=$user['avatar']?>" alt="" style="cursor: pointer;"  height="300px">
                                <small class="form-text text-muted text-center">Click the image to change</small>
                            </label>
                            <input type="file" id="avatar" name="avatar" style="display: none;">
                            <?php if(isset($_SESSION['image-error'])): ?>
                            <div class="invalid-feedback d-block" role="alert">  
                                <?=$_SESSION['image-error'];
                                unset($_SESSION['image-error']);
                                ?>
                            </div>
                            <?php endif ?>
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-2">
                                <label for="title" class="form-label">Title <span class="text-body-secondary">(Optional)</span></label>
                                <input type="text" class="form-control" id="title" name="title" value='<?=$user['title']?>' placeholder="Title">
                            </div>
                            <div class="col-sm-5">
                                <label for="firstname" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstname" name="firstname" value='<?=$user['firstname']?>' placeholder="First Name" required>
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
                                <input type="text" class="form-control" id="lastname" name="lastname" value='<?=$user['lastname']?>' placeholder="Last Name" required>
                                <?php if(isset($_SESSION['lastname-error'])): ?>
                                <div class="invalid-feedback d-block" role="alert">  
                                    <?=$_SESSION['lastname-error'];
                                    unset($_SESSION['lastname-error']);
                                    ?>
                                </div>
                                <?php endif ?>
                            </div>
                            <div class="col-sm-6">
                                <label for="department" class="form-label">Department</label>
                                <input type="text" class="form-control" id="department" name="department" value='<?=$user['department']?>' placeholder="Department" required>
                            
                            </div>
                            <div class="col-sm-6">
                                <label for="institution" class="form-label">Institution</label>
                                <input type="text" class="form-control" id="institution" name="institution" value='<?=$user['institution']?>' placeholder="Institution" required>
                                
                            </div>
                            <div class="col-sm-12">
                                <label for="education" class="form-label">Education</label>
                                <textarea class="form-control" id="education" rows="5" name="education" placeholder="Education"><?=$user['education']?></textarea>
                                
                            </div>
                            <div class="col-sm-12">
                                <label for="interests" class="form-label">Interest</label>
                                <textarea class="form-control" id="interests" rows="3" name="interests" placeholder="Interest"><?=$user['interests']?></textarea>
                                
                            </div>
                            <div class="col-sm-12">
                                <label for="url" class="form-label">URL <span class="text-body-secondary">(Optional)</span></label>
                                <input type="url" class="form-control" id="url" name="url" value='<?=$user['url']?>' placeholder="URL">
                            </div>
                            <hr class="my-4">
                            <div class="col-12 text-center">
                                <button class="btn btn-primary" type="submit" name="submit">Update</button>
                                <button class="btn btn-secondary" type="button" onclick="window.location.href='profile.php?id=<?=$_SESSION['user']['id']?>'">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>

<script>
$(document).ready(function() {
    $('#avatar').on('change', function() {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('.avatar-image').attr('src', e.target.result);
        }
        reader.readAsDataURL(this.files[0]);
    });
});
</script>