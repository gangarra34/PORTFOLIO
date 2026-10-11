<?php require __DIR__.'/../includes/functions.php';require_role('admin');if(post()){verify_csrf();$id=(int)($_POST['id']??0);$action=$_POST['action']??'';if(in_array($action,['approve','hide','delete'],true)){if($action==='delete'){$q=$pdo->prepare('DELETE FROM testimonial WHERE id=?');$q->execute([$id]);}else{$q=$pdo->prepare('UPDATE testimonial SET approved=? WHERE id=?');$q->execute([$action==='approve'?1:0,$id]);}redirect('testimonial_manage.php','Testimonial updated.');}}$rows=$pdo->query('SELECT t.*,u.first_name,s.name AS service_name FROM testimonial t JOIN user u ON u.id=t.parent_id JOIN service s ON s.id=t.service_id ORDER BY t.created_at DESC')->fetchAll();$title='Manage testimonials';require __DIR__.'/../includes/header.php';?>
<section class="section">
    <div class="container">
        <h1>
            Manage testimonials
        </h1>
        <div class="row g-3">
            <?php foreach($rows as $r):?>
                <div class="col-lg-6">
                    <div class="form-panel">
                        <h2 class="h5">
                            <?=e($r['first_name'])?>
                            ·
                            <?=e($r['service_name'])?>
                        </h2>
                        <p>
                            <?=nl2br(e($r['comment']))?>
                        </p>
                        <p class="small-note">
                            Status:
                            <?=$r['approved']?'Approved':'Pending / hidden'?>
                            ·
                            <?=e($r['created_at'])?>
                        </p>
                        <form method="post" class="d-flex flex-wrap gap-2">
                            <?=csrf_field()?>
                            <input type="hidden" name="id" value="<?=$r['id']?>
                            ">
                            <button class="btn btn-success btn-sm" name="action" value="approve">
                                Approve
                            </button>
                            <button class="btn btn-outline-secondary btn-sm" name="action" value="hide">
                                Hide
                            </button>
                            <button class="btn btn-outline-danger btn-sm" name="action" value="delete">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach;?>
        </div>
    </div>
</section>
<?php require __DIR__.'/../includes/footer.php';?>
