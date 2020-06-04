<?php
$list = $MODEL['list'];
?>




<?
$counter = 0;
?>
<?foreach ($list as $city):?>
    <?
    $counter++;
    ?>
    <div class=" status-<?=$city->status->num?> item itemId-<?=$city->id?>">
        <div class="info">
            <div class="col id"><?=$city->id?></div>
            <div class="col status">
                <a href="#" class="status-btn status-btn-2" onclick="Geo.City.switchStatus(<?=$city->id?>); return false; "><i class="fa fa-toggle-off"></i></a>
                <a href="#" class="status-btn status-btn-1" onclick="Geo.City.switchStatus(<?=$city->id?>); return false; "><i class="fa fa-toggle-on"></i></a>
            </div>
            <div class="col title"><a href="#" onclick="Geo.City.click(<?=$city->id?>); return false; "><?=$city->name?></a></div>
        </div>
    </div>
<?endforeach;?>



