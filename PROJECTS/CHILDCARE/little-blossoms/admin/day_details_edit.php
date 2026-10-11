<?php
require __DIR__.'/../includes/functions.php';require_role('admin');$error='';
if(post()){verify_csrf();$child=(int)($_POST['child_id']??0);$date=$_POST['day_date']??'';$temperature=trim($_POST['temperature']??'');$breakfast=trim($_POST['breakfast']??'');$lunch=trim($_POST['lunch']??'');$activities=trim($_POST['activities']??'');$temp=$temperature===''?null:filter_var($temperature,FILTER_VALIDATE_FLOAT);if(!own_child($pdo,$child)||!valid_date($date)||($temperature!==''&&($temp===false||$temp<30||$temp>45))||mb_strlen($breakfast)>500||mb_strlen($lunch)>500||mb_strlen($activities)>2000)$error='Check child, date, temperature (30–45°C), and text lengths.';else{$q=$pdo->prepare('INSERT INTO day(child_id,day_date,temperature,breakfast,lunch,activities) VALUES(?,?,?,?,?,?) ON DUPLICATE KEY UPDATE temperature=VALUES(temperature),breakfast=VALUES(breakfast),lunch=VALUES(lunch),activities=VALUES(activities)');$q->execute([$child,$date,$temp,$breakfast,$lunch,$activities]);redirect('day_details_edit.php?child_id='.$child.'&date='.$date,'Daily record saved.');}}
$children=$pdo->query('SELECT id,name FROM child ORDER BY name')->fetchAll();$child=(int)($_GET['child_id']??($children[0]['id']??0));$date=$_GET['date']??date('Y-m-d');if(!valid_date($date))$date=date('Y-m-d');$q=$pdo->prepare('SELECT * FROM day WHERE child_id=? AND day_date=?');$q->execute([$child,$date]);$entry=$q->fetch()?:[];$title='Edit daily details';require __DIR__.'/../includes/header.php';?>
<section class="section">
    <div class="container" style="max-width:850px">
        <h1>
            Record a child's day
        </h1>
        <p>
            One record per child per day. Saving again updates the existing record.
        </p>
        <?php if($error):?>
            <div class="alert alert-danger">
                <?=e($error)?>
            </div>
        <?php endif;?>
        <form method="get" class="row g-2 mb-4">
            <div class="col-sm-6">
                <label class="form-label" for="filter_child">
                    Select child
                </label>
                <select class="form-select" id="filter_child" name="child_id">
                    <?php foreach($children as $c):?>
                        <option value="<?=$c['id']?>
                            "
                            <?=$child===$c['id']?'selected':''?>
                            >
                            <?=e($c['name'])?>
                        </option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="col-sm-4">
                <label class="form-label" for="filter_date">
                    Date
                </label>
                <input class="form-control" id="filter_date" type="date" name="date" value="<?=e($date)?>
                ">
            </div>
            <div class="col-sm-2 align-self-end">
                <button class="btn btn-outline-success w-100">
                    Load
                </button>
            </div>
        </form>
        <form class="form-panel" method="post">
            <?=csrf_field()?>
            <input type="hidden" name="child_id" value="<?=$child?>
            ">
            <input type="hidden" name="day_date" value="<?=e($date)?>
            ">
            <h2 class="h4">
                Daily record for
                <?=e($date)?>
            </h2>
            <label class="form-label" for="temp">
                Temperature °C
            </label>
            <input class="form-control mb-3" id="temp" type="number" step="0.1" min="30" max="45" name="temperature" value="<?=e($entry['temperature']??'')?>
            ">
            <label class="form-label" for="breakfast">
                Breakfast
            </label>
            <textarea class="form-control mb-3" id="breakfast" name="breakfast" maxlength="500">
                <?=e($entry['breakfast']??'')?>
            </textarea>
            <label class="form-label" for="lunch">
                Lunch
            </label>
            <textarea class="form-control mb-3" id="lunch" name="lunch" maxlength="500">
                <?=e($entry['lunch']??'')?>
            </textarea>
            <label class="form-label" for="activities">
                Activities
            </label>
            <textarea class="form-control mb-3" id="activities" name="activities" rows="5" maxlength="2000">
                <?=e($entry['activities']??'')?>
            </textarea>
            <button class="btn btn-success" <?=$child?'':'disabled'?>
                >Save daily record
            </button>
        </form>
    </div>
</section>
<?php require __DIR__.'/../includes/footer.php';?>
