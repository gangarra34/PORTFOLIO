<?php require __DIR__.'/../includes/functions.php';require_role('admin');if(post()){verify_csrf();$id=(int)($_POST['id']??0);$q=$pdo->prepare('DELETE FROM contact_us WHERE id=?');$q->execute([$id]);redirect('contact_us_manage.php','Message deleted.');}$rows=$pdo->query('SELECT * FROM contact_us ORDER BY created_at DESC')->fetchAll();$title='Contact messages';require __DIR__.'/../includes/header.php';?>
<section class="section">
    <div class="container">
        <h1>
            Contact messages
        </h1>
        <div class="row g-3">
            <?php foreach($rows as $r):?>
                <div class="col-lg-6">
                    <div class="form-panel">
                        <h2 class="h5">
                            <?=e($r['name'])?>
                        </h2>
                        <p>
                            <a href="mailto:<?=e($r['email'])?>
                                ">
                                <?=e($r['email'])?>
                            </a>
                            ·
                            <?=e($r['phone'])?>
                        </p>
                        <p>
                            <?=nl2br(e($r['message']))?>
                        </p>
                        <p class="small-note">
                            <?=e($r['created_at'])?>
                        </p>
                        <form method="post">
                            <?=csrf_field()?>
                            <input type="hidden" name="id" value="<?=$r['id']?>
                            ">
                            <button class="btn btn-outline-danger btn-sm">
                                Delete message
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach;?>
        </div>
    </div>
</section>
<?php require __DIR__.'/../includes/footer.php';?>
