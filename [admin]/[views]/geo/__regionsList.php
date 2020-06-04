<?php
$list = $MODEL['list'];
?>




<?
$counter = 0;
?>
<?foreach ($list as $region):?>
    <?
    $counter++;
    ?>
    <div class=" status-<?=$region->status->num?> item itemId-<?=$region->id?>">
        <div class="info">
            <div class="col id"><?=$region->id?></div>
            <div class="col status">
                <a href="#" class="status-btn status-btn-2" onclick="Geo.Region.switchStatus(<?=$region->id?>); return false; "><i class="fa fa-toggle-off"></i></a>
                <a href="#" class="status-btn status-btn-1" onclick="Geo.Region.switchStatus(<?=$region->id?>); return false; "><i class="fa fa-toggle-on"></i></a>
            </div>
            <div class="col title"><a href="#" onclick="Geo.Region.click(<?=$region->id?>); return false; "><?=$region->name?></a></div>
        </div>
    </div>
<?endforeach;?>



