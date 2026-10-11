<?php
require __DIR__.'/includes/functions.php';
if(user()) redirect('index.php');
$error='';
if(post()) {
 verify_csrf(); $email=trim($_POST['email']??'');$password=$_POST['password']??'';
 $s=$pdo->prepare('SELECT id,first_name,email,password_hash,role FROM user WHERE email=?');$s->execute([$email]);$account=$s->fetch();
 if($account && password_verify($password,$account['password_hash'])) {
  session_regenerate_id(true);unset($account['password_hash']);$_SESSION['user']=$account;
  redirect($account['role']==='admin'?'admin/dashboard.php':'day_details.php','Welcome back!');
 } $error='Invalid email or password.';
}
$title='Log in';require __DIR__.'/includes/header.php';?>
<section class="section">
    <div class="container" style="max-width:560px">
        <div class="form-panel">
            <h1 class="h2 mb-4">
                Parent & Admin Login
            </h1>
            <?php if($error):?>
                <div class="alert alert-danger">
                    <?=e($error)?>
                </div>
            <?php endif;?>
            <form method="post">
                <?=csrf_field()?>
                <div class="mb-3">
                    <label class="form-label" for="email">
                        Email
                    </label>
                    <input class="form-control" id="email" type="email" name="email" required autocomplete="username">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="password">
                        Password
                    </label>
                    <input class="form-control" id="password" type="password" name="password" required autocomplete="current-password">
                </div>
                <button class="btn btn-success">
                    Sign in
                </button>
            </form>
            <p class="mt-3">
                New family?
                <a href="registration.php">
                    Register your child
                </a>
            </p>
        </div>
    </div>
</section>
<?php require __DIR__.'/includes/footer.php';?>
