<?php require __DIR__.'/includes/functions.php';require_role('parent','admin');$services=$pdo->query('SELECT id,name FROM service ORDER BY name')->fetchAll();$error='';if(post()){verify_csrf();$service=(int)($_POST['service_id']??0);$comment=trim($_POST['comment']??'');$q=$pdo->prepare('SELECT id FROM service WHERE id=?');$q->execute([$service]);if(!$q->fetch()||mb_strlen($comment)<10||mb_strlen($comment)>1500)$error='Choose a service and enter 10–1500 characters.';else{$q=$pdo->prepare('INSERT INTO testimonial(parent_id,service_id,comment,approved) VALUES(?,?,?,0)');$q->execute([user()['id'],$service,$comment]);redirect('testimonial.php','Thank you! Your testimonial is awaiting approval.');}}$title='Add testimonial';require __DIR__.'/includes/header.php';?>
<section class="section">
    <div class="container" style="max-width:700px">
        <div class="form-panel">
            <h1>
                Share your experience
            </h1>
            <?php if($error):?>
                <div class="alert alert-danger">
                    <?=e($error)?>
                </div>
            <?php endif;?>
            <form method="post">
                <?=csrf_field()?>
                <label for="service" class="form-label">
                    Service
                </label>
                <select id="service" name="service_id" class="form-select mb-3" required>
                    <?php foreach($services as $s):?>
                        <option value="<?=$s['id']?>
                            ">
                            <?=e($s['name'])?>
                        </option>
                    <?php endforeach;?>
                </select>
                <label for="comment" class="form-label">
                    Your comment
                </label>
                <textarea id="comment" class="form-control" name="comment" rows="6" minlength="10" maxlength="1500" required>
                </textarea>
                <button class="btn btn-success mt-3">
                    Submit for review
                </button>
            </form>
        </div>
    </div>
</section>
<?php require __DIR__.'/includes/footer.php';?>
