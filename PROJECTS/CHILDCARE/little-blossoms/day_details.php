<?php
require __DIR__.'/includes/functions.php';require_role('parent','admin');
$childId=(int)($_GET['child_id']??0);$date=$_GET['date']??date('Y-m-d');if(!valid_date($date))$date=date('Y-m-d');
if(role()==='admin'){$children=$pdo->query('SELECT id,name FROM child ORDER BY name')->fetchAll();}else{$q=$pdo->prepare('SELECT id,name FROM child WHERE parent_id=? ORDER BY name');$q->execute([user()['id']]);$children=$q->fetchAll();}
if(!$childId && $children)$childId=(int)$children[0]['id'];
if($childId && !own_child($pdo,$childId)){http_response_code(403);exit('Access denied.');}
$q=$pdo->prepare('SELECT d.*,c.name FROM day d JOIN child c ON c.id=d.child_id WHERE d.child_id=? AND d.day_date=?');$q->execute([$childId,$date]);$entry=$q->fetch();
$title='Daily updates';require __DIR__.'/includes/header.php';?>
<section class="section">
    <div class="container">
        <h1>
            Daily child updates
        </h1>
        <p>
            View breakfast, lunch, temperature and activities for a selected day.
        </p>
        <form method="get" class="form-panel row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label" for="child_id">
                    Child
                </label>
                <select class="form-select" name="child_id" id="child_id">
                    <?php foreach($children as $c):?>
                        <option value="<?=$c['id']?>
                            "
                            <?=$childId===$c['id']?'selected':''?>
                            >
                            <?=e($c['name'])?>
                        </option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="date">
                    Date
                </label>
                <input class="form-control" type="date" id="date" name="date" value="<?=e($date)?>
                ">
            </div>
            <div class="col-md-3">
                <button class="btn btn-success w-100">
                    View details
                </button>
            </div>
        </form>
        <?php if($entry):?>
            <div class="form-panel mt-4">
                <h2 class="h4">
                    <?=e($entry['name'])?>
                    —
                    <?=e($entry['day_date'])?>
                </h2>
                <div class="table-wrap">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="row">
                                    Temperature
                                </th>
                                <td>
                                    <?=e($entry['temperature']??'Not recorded')?>
                                    °C
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    Breakfast
                                </th>
                                <td>
                                    <?=e($entry['breakfast'])?>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    Lunch
                                </th>
                                <td>
                                    <?=e($entry['lunch'])?>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    Activities
                                </th>
                                <td>
                                    <?=nl2br(e($entry['activities']))?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php else:?>
            <div class="alert alert-info mt-4">
                No daily record found for this date.
            </div>
        <?php endif;?>
    </div>
</section>
<?php require __DIR__.'/includes/footer.php';?>
