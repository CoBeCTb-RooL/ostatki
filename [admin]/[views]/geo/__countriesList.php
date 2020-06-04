<?php
$list = $MODEL['list'];
?>




<?
$countriesCounter = 0;
?>
<?foreach ($list as $country):?>
    <?
    $countriesCounter++;
    ?>
    <div class="item itemId-<?=$country->id?> status-<?=$country->status->num?>">
        <div class="info">
            <div class="col id"><?=$country->id?></div>
            <div class="col status">
                <a href="#" class="status-btn status-btn-2" onclick="Geo.Country.switchStatus(<?=$country->id?>); return false; "><i class="fa fa-toggle-off"></i></a>
                <a href="#" class="status-btn status-btn-1" onclick="Geo.Country.switchStatus(<?=$country->id?>); return false; "><i class="fa fa-toggle-on"></i></a>
            </div>
            <div class="col title"><a href="#" onclick="Geo.Country.click(<?=$country->id?>); return false; "><?=$country->name?></a></div>
        </div>
    </div>
<?endforeach;?>



