<?php require __DIR__.'/../includes/functions.php';require_role('admin');$error='';if(post()){verify_csrf();$action=$_POST['action']??'save';$id=(int)($_POST['id']??0);if($action==='delete'){$q=$pdo->prepare('DELETE FROM service WHERE id=?');try{$q->execute([$id]);redirect('service_manage.php','Service deleted.');}catch(PDOException $ex){$error='Cannot delete a service linked to testimonials.';}}else{$name=trim($_POST['name']??'');$summary=trim($_POST['summary']??'');$image=trim($_POST['image_path']??'');$link=$_POST['link_path']??'';if(mb_strlen($name)<3||mb_strlen($name)>120||mb_strlen($summary)<5||mb_strlen($summary)>1000||!preg_match('~^assets/img/[a-zA-Z0-9_-]+\.svg$~',$image)||!in_array($link,['day_details.php','registration.php','contact_us.php','testimonial.php'],true))$error='Enter valid service details and an approved internal link.';else{if($id){$q=$pdo->prepare('UPDATE service SET name=?,summary=?,image_path=?,link_path=? WHERE id=?');$q->execute([$name,$summary,$image,$link,$id]);}else{$q=$pdo->prepare('INSERT INTO service(name,summary,image_path,link_path) VALUES(?,?,?,?)');$q->execute([$name,$summary,$image,$link]);}redirect('service_manage.php','Service saved.');}}}$services=$pdo->query('SELECT * FROM service ORDER BY id')->fetchAll();$services[]= ['id'=>0,'name'=>'','summary'=>'','image_path'=>'assets/img/activities.svg','link_path'=>'registration.php'];$title='Manage services';require __DIR__.'/../includes/header.php';?>
<section class="section">
    <div class="container">
        <h1>
            Manage services (CRUD)
        </h1>
        <?php if($error):?>
            <div class="alert alert-danger">
                <?=e($error)?>
            </div>
        <?php endif;?>
        <div class="row g-4">
            <?php foreach($services as $s):?>
                <div class="col-lg-6">
                    <form class="form-panel" method="post">
                        <?=csrf_field()?>
                        <input type="hidden" name="id" value="<?=$s['id']?>
                        ">
                        <h2 class="h5">
                            <?=$s['id']?'Edit service #'.$s['id']:'Add new service'?>
                        </h2>
                        <label class="form-label">
                            Name
                        </label>
                        <input class="form-control mb-2" name="name" maxlength="120" required value="<?=e($s['name'])?>
                        ">
                        <label class="form-label">
                            Summary
                        </label>
                        <textarea class="form-control mb-2" name="summary" maxlength="1000" required>
                            <?=e($s['summary'])?>
                        </textarea>
                        <label class="form-label">
                            Image path (SVG in assets/img)
                        </label>
                        <input class="form-control mb-2" name="image_path" required value="<?=e($s['image_path'])?>
                        ">
                        <label class="form-label">
                            Link
                        </label>
                        <select class="form-select mb-3" name="link_path">
                            <?php foreach(['registration.php','day_details.php','contact_us.php','testimonial.php'] as $path):?>
                                <option value="<?=$path?>
                                    "
                                    <?=$s['link_path']===$path?'selected':''?>
                                    >
                                    <?=$path?>
                                </option>
                            <?php endforeach;?>
                        </select>
                        <button class="btn btn-success" name="action" value="save">
                            Save
                        </button>
                        <?php if($s['id']):?>
                            <button class="btn btn-outline-danger" name="action" value="delete" formnovalidate>
                                Delete
                            </button>
                        <?php endif;?>
                    </form>
                </div>
            <?php endforeach;?>
        </div>
    </div>
</section>
<?php require __DIR__.'/../includes/footer.php';?>
