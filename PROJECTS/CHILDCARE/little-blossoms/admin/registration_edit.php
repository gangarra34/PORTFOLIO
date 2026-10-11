<?php require __DIR__.'/../includes/functions.php';require_role('admin');$error='';if(post()){verify_csrf();$id=(int)($_POST['id']??0);$price=filter_var($_POST['weekly_price']??'',FILTER_VALIDATE_FLOAT);if($price===false||$price<0||$price>10000)$error='Enter a valid weekly fee between 0 and 10000.';else{$q=$pdo->prepare('UPDATE fee SET weekly_price=? WHERE id=?');$q->execute([$price,$id]);redirect('registration_edit.php','Fee updated.');}}$fees=$pdo->query('SELECT * FROM fee ORDER BY category,days_per_week,care_type')->fetchAll();$title='Edit fees';require __DIR__.'/../includes/header.php';?>
<section class="section">
    <div class="container">
        <h1>
            Manage childcare fees
        </h1>
        <?php if($error):?>
            <div class="alert alert-danger">
                <?=e($error)?>
            </div>
        <?php endif;?>
        <div class="table-wrap">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>
                            Category
                        </th>
                        <th>
                            Care
                        </th>
                        <th>
                            Days/week
                        </th>
                        <th>
                            Weekly fee (€)
                        </th>
                        <th>
                            Action
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
                                <?=$f['days_per_week']?>
                            </td>
                            <td colspan="2">
                                <form method="post" class="d-flex gap-2">
                                    <?=csrf_field()?>
                                    <input type="hidden" name="id" value="<?=$f['id']?>
                                    ">
                                    <input aria-label="Weekly fee" class="form-control" style="max-width:180px" name="weekly_price" type="number" min="0" max="10000" step="0.01" value="<?=e($f['weekly_price'])?>
                                    " required>
                                    <button class="btn btn-success">
                                        Save
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php require __DIR__.'/../includes/footer.php';?>
