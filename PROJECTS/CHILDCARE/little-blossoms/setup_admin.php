<?php
// One-time setup only. Delete this file immediately after creating the admin.
require __DIR__.'/includes/functions.php';
$count=(int)$pdo->query("SELECT COUNT(*) FROM user WHERE role='admin'")->fetchColumn();
if($count>0){http_response_code(403);exit('Admin already exists. Delete setup_admin.php.');}
$error='';if(post()){verify_csrf();$email=trim($_POST['email']??'');$password=$_POST['password']??'';if(!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($password)<12)$error='Valid email and password of at least 12 characters required.';else{$q=$pdo->prepare("INSERT INTO user(first_name,last_name,email,phone,password_hash,role) VALUES('Site','Administrator',?,'N/A',?,'admin')");$q->execute([$email,password_hash($password,PASSWORD_DEFAULT)]);redirect('login.php','Admin created. Delete setup_admin.php now.');}}
$title='Initial admin setup';require __DIR__.'/includes/header.php';?>
<section class="section">
    <div class="container" style="max-width:600px">
        <div class="form-panel">
            <h1>
                Create the first admin
            </h1>
            <p class="text-danger">
                Delete this setup file immediately after use, before publishing the site.
            </p>
            <?php if($error):?>
                <div class="alert alert-danger">
                    <?=e($error)?>
                </div>
            <?php endif;?>
            <form method="post">
                <?=csrf_field()?>
                <label class="form-label">
                    Admin email
                </label>
                <input class="form-control mb-3" type="email" name="email" required>
                <label class="form-label">
                    Admin password (12+ characters)
                </label>
                <input class="form-control mb-3" type="password" name="password" minlength="12" required>
                <button class="btn btn-success">
                    Create admin
                </button>
            </form>
        </div>
    </div>
</section>
<?php require __DIR__.'/includes/footer.php';?>
