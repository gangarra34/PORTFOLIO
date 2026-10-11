<?php
require __DIR__.'/includes/functions.php';$errors=[];
$fees=$pdo->query('SELECT * FROM fee ORDER BY category, days_per_week, care_type')->fetchAll();
if(post()){
 verify_csrf();$first=trim($_POST['first_name']??'');$last=trim($_POST['last_name']??'');$email=trim($_POST['email']??'');$phone=trim($_POST['phone']??'');$password=$_POST['password']??'';$child=trim($_POST['child_name']??'');$dob=$_POST['date_of_birth']??'';$feeId=(int)($_POST['fee_id']??0);
 if(!$first||mb_strlen($first)>80||!$last||mb_strlen($last)>80)$errors[]='Enter valid parent names (maximum 80 characters each).';
 if(!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($email)>190)$errors[]='Enter a valid email.';
 if(!preg_match('/^[0-9+() .-]{7,25}$/',$phone))$errors[]='Enter a valid phone number.';
 if(strlen($password)<12)$errors[]='Password must contain at least 12 characters.';
 if(!$child||mb_strlen($child)>120)$errors[]='Enter a valid child name.';
 if(!valid_date($dob)||$dob>date('Y-m-d'))$errors[]='Enter a valid date of birth.';
 $q=$pdo->prepare('SELECT id FROM fee WHERE id=?');$q->execute([$feeId]);if(!$q->fetch())$errors[]='Select a valid care plan.';
 $q=$pdo->prepare('SELECT id FROM user WHERE email=?');$q->execute([$email]);if($q->fetch())$errors[]='This email is already registered. Please log in.';
 if(!$errors){try{$pdo->beginTransaction();$q=$pdo->prepare("INSERT INTO user(first_name,last_name,email,phone,password_hash,role) VALUES(?,?,?,?,?,'parent')");$q->execute([$first,$last,$email,$phone,password_hash($password,PASSWORD_DEFAULT)]);$parentId=(int)$pdo->lastInsertId();$q=$pdo->prepare('INSERT INTO child(parent_id,fee_id,name,date_of_birth) VALUES(?,?,?,?)');$q->execute([$parentId,$feeId,$child,$dob]);$pdo->commit();
 // mail() requires a working SMTP/sendmail configuration on the host.
 $subject='Little Blossoms registration confirmation';$body="Hello $first,\n\nThank you for registering $child with Little Blossoms. We will contact you soon.\n";
 $sent=@mail($email,$subject,$body,'From: no-reply@example.org');
 redirect('login.php',$sent?'Registration complete. A confirmation email was sent.':'Registration complete. Email delivery is not configured on this server.');
 }catch(PDOException $ex){if($pdo->inTransaction())$pdo->rollBack();error_log($ex->getMessage());$errors[]='Could not complete registration. Please try again.';}}
}
$title='Register';require __DIR__.'/includes/header.php';?>
<section class="section">
    <div class="container">
        <p class="eyebrow">
            Join our family
        </p>
        <h1>
            Childcare registration
        </h1>
        <p>
            Choose from babies, wobblers, toddlers and preschool programmes.
        </p>
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="form-panel">
                    <h2 class="h4">
                        Available care plans
                    </h2>
                    <div class="table-wrap">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        Group
                                    </th>
                                    <th>
                                        Care
                                    </th>
                                    <th>
                                        Days
                                    </th>
                                    <th>
                                        Weekly fee
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($fees as $f):?>
                                    <tr>
                                        <td>
                                            <?=e(ucfirst($f['category']))?>
                                        </td>
                                        <td>
                                            <?=e($f['care_type'])?>
                                        </td>
                                        <td>
                                            <?=e($f['days_per_week'])?>
                                        </td>
                                        <td>
                                            €
                                            <?=e(number_format($f['weekly_price'],2))?>
                                        </td>
                                    </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="form-panel">
                    <h2 class="h4">
                        Register a parent account and child
                    </h2>
                    <?php foreach($errors as $err):?>
                        <div class="alert alert-danger">
                            <?=e($err)?>
                        </div>
                    <?php endforeach;?>
                    <form method="post">
                        <?=csrf_field()?>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label for="first" class="form-label">
                                    First name
                                </label>
                                <input id="first" class="form-control" name="first_name" maxlength="80" required value="<?=e($_POST['first_name']??'')?>
                                ">
                            </div>
                            <div class="col-sm-6">
                                <label for="last" class="form-label">
                                    Last name
                                </label>
                                <input id="last" class="form-control" name="last_name" maxlength="80" required value="<?=e($_POST['last_name']??'')?>
                                ">
                            </div>
                            <div class="col-sm-6">
                                <label for="email" class="form-label">
                                    Email
                                </label>
                                <input id="email" class="form-control" type="email" name="email" maxlength="190" required value="<?=e($_POST['email']??'')?>
                                ">
                            </div>
                            <div class="col-sm-6">
                                <label for="phone" class="form-label">
                                    Phone
                                </label>
                                <input id="phone" class="form-control" name="phone" maxlength="25" required value="<?=e($_POST['phone']??'')?>
                                ">
                            </div>
                            <div class="col-12">
                                <label for="password" class="form-label">
                                    Password (12+ characters)
                                </label>
                                <input id="password" class="form-control" type="password" name="password" minlength="12" required autocomplete="new-password">
                            </div>
                            <div class="col-sm-6">
                                <label for="child" class="form-label">
                                    Child's name
                                </label>
                                <input id="child" class="form-control" name="child_name" maxlength="120" required value="<?=e($_POST['child_name']??'')?>
                                ">
                            </div>
                            <div class="col-sm-6">
                                <label for="dob" class="form-label">
                                    Date of birth
                                </label>
                                <input id="dob" class="form-control" type="date" name="date_of_birth" max="<?=date('Y-m-d')?>
                                " required value="
                                <?=e($_POST['date_of_birth']??'')?>
                                ">
                            </div>
                            <div class="col-12">
                                <label for="plan" class="form-label">
                                    Choose programme and care plan
                                </label>
                                <select id="plan" class="form-select" name="fee_id" required>
                                    <option value="">
                                        Select a plan
                                    </option>
                                    <?php foreach($fees as $f):?>
                                        <option value="<?=$f['id']?>
                                            "
                                            <?=((int)($_POST['fee_id']??0)===$f['id'])?'selected':''?>
                                            >
                                            <?=e(ucfirst($f['category']).' · '.$f['care_type'].' · '.$f['days_per_week'].' day(s) · €'.$f['weekly_price'].'/week')?>
                                        </option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-success mt-4">
                            Submit registration
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require __DIR__.'/includes/footer.php';?>
