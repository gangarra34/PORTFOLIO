<?php require __DIR__.'/includes/functions.php';$errors=[];if(post()){verify_csrf();$name=trim($_POST['name']??'');$email=trim($_POST['email']??'');$phone=trim($_POST['phone']??'');$message=trim($_POST['message']??'');if(mb_strlen($name)<2||mb_strlen($name)>120)$errors[]='Name must be 2–120 characters.';if(!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($email)>190)$errors[]='Enter a valid email.';if(!preg_match('/^[0-9+() .-]{7,25}$/',$phone))$errors[]='Enter a valid phone number.';if(mb_strlen($message)<10||mb_strlen($message)>3000)$errors[]='Message must be 10–3000 characters.';if(!$errors){$q=$pdo->prepare('INSERT INTO contact_us(name,email,phone,message) VALUES(?,?,?,?)');$q->execute([$name,$email,$phone,$message]);redirect('contact_us.php','Your message has been received. Thank you!');}}$title='Contact us';require __DIR__.'/includes/header.php';?>
<section class="section">
    <div class="container" style="max-width:760px">
        <div class="form-panel">
            <p class="eyebrow">
                Get in touch
            </p>
            <h1>
                Contact Little Blossoms
            </h1>
            <p>
                Questions about our programmes? Send us a message.
            </p>
            <?php foreach($errors as $err):?>
                <div class="alert alert-danger">
                    <?=e($err)?>
                </div>
            <?php endforeach;?>
            <form method="post">
                <?=csrf_field()?>
                <label class="form-label" for="name">
                    Name
                </label>
                <input class="form-control mb-3" id="name" name="name" maxlength="120" required value="<?=e($_POST['name']??'')?>
                ">
                <label class="form-label" for="email">
                    Email
                </label>
                <input class="form-control mb-3" type="email" id="email" name="email" required value="<?=e($_POST['email']??'')?>
                ">
                <label class="form-label" for="phone">
                    Phone number
                </label>
                <input class="form-control mb-3" id="phone" name="phone" required value="<?=e($_POST['phone']??'')?>
                ">
                <label class="form-label" for="message">
                    Message
                </label>
                <textarea class="form-control mb-3" id="message" name="message" rows="6" minlength="10" maxlength="3000" required>
                    <?=e($_POST['message']??'')?>
                </textarea>
                <button class="btn btn-success">
                    Send message
                </button>
            </form>
        </div>
    </div>
</section>
<?php require __DIR__.'/includes/footer.php';?>
