<?php require __DIR__.'/../includes/functions.php';require_role('admin');$error='';if(post()){verify_csrf();$id=(int)($_POST['id']??0);$titleText=trim($_POST['title']??'');$detail=trim($_POST['detail']??'');$image=trim($_POST['image_path']??'');$link=trim($_POST['link_path']??'');if(mb_strlen($titleText)<3||mb_strlen($titleText)>150||mb_strlen($detail)<5||mb_strlen($detail)>1200||!preg_match('~^assets/img/[a-zA-Z0-9_-]+\.svg$~',$image)||($link && !in_array($link,['services.php','registration.php','contact_us.php','testimonial.php'],true)))$error='Please provide a valid title, description, local SVG image and approved link.';else{$q=$pdo->prepare('UPDATE feature SET title=?,detail=?,image_path=?,link_path=? WHERE id=?');$q->execute([$titleText,$detail,$image,$link,$id]);redirect('index_edit.php','Homepage feature updated.');}}$features=$pdo->query('SELECT * FROM feature ORDER BY id LIMIT 2')->fetchAll();$title='Edit homepage';require __DIR__.'/../includes/header.php';?>
<section class="section">
    <div class="container">
        <h1>
            Edit homepage features
        </h1>
        <?php if($error):?>
            <div class="alert alert-danger">
                <?=e($error)?>
            </div>
        <?php endif;?>
        <div class="row g-4">
            <?php foreach($features as $f):?>
                <div class="col-lg-6">
                    <form method="post" class="form-panel">
                        <?=csrf_field()?>
                        <input type="hidden" name="id" value="<?=$f['id']?>
                        ">
                        <label class="form-label" for="title<?=$f['id']?>
                            ">Feature title
                        </label>
                        <input class="form-control mb-3" id="title<?=$f['id']?>
                        " name="title" maxlength="150" required value="
                        <?=e($f['title'])?>
                        ">
                        <label class="form-label" for="detail<?=$f['id']?>
                            ">Description
                        </label>
                        <textarea class="form-control mb-3" id="detail<?=$f['id']?>
                            " name="detail" rows="5" maxlength="1200" required>
                            <?=e($f['detail'])?>
                        </textarea>
                        <label class="form-label" for="image<?=$f['id']?>
                            ">Local SVG image path
                        </label>
                        <input class="form-control mb-3" id="image<?=$f['id']?>
                        " name="image_path" value="
                        <?=e($f['image_path'])?>
                        " required>
                        <label class="form-label" for="link<?=$f['id']?>
                            ">Link
                        </label>
                        <select class="form-select mb-3" id="link<?=$f['id']?>
                            " name="link_path">
                            <?php foreach([''=>'No link','services.php'=>'Services','registration.php'=>'Registration','contact_us.php'=>'Contact','testimonial.php'=>'Testimonials'] as $url=>$label):?>
                                <option value="<?=e($url)?>
                                    "
                                    <?=$f['link_path']===$url?'selected':''?>
                                    >
                                    <?=e($label)?>
                                </option>
                            <?php endforeach;?>
                        </select>
                        <button class="btn btn-success">
                            Save feature
                        </button>
                    </form>
                </div>
            <?php endforeach;?>
        </div>
    </div>
</section>
<?php require __DIR__.'/../includes/footer.php';?>
