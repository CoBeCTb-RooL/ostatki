<?php
$artnums = $MODEL['artnums'];
$chosenArtnum = $MODEL['chosenArtnum'];
$otherOption = $MODEL['otherOption'];
$otherOptionSelected = $MODEL['otherOptionSelected'];

//vd($artnums);
?>


<!---->
<?php
//foreach($artnums as $key=>$artnum)
//{
//    vd($artnum);
////    vd($artnum->imgResized('&height=100'));
//}?>




<select name="artnumId" id="filter-artnums" >
	<option value="" onclick="$('#artNumImgWrapper').slideUp('fast')">-выберите-</option>

<?foreach($artnums as $key=>$artnum):?>
	<option
            value="<?=$artnum->id?>" <?=( $artnum->id == $chosenArtnum->id ? ' selected="selected" ' : "" )?>
            artnumImg="<?=$artnum->imgResized('&height=100')?>"
            onclick="$('#artNumImg').attr('src', $(this).attr('artnumImg')); $('#artNumImgWrapper').slideDown('fast')  "
    >
        <?=$artnum->name?>
    </option>
<?endforeach;?>


<?if($otherOption):?>
	<option value="other" <?=$otherOptionSelected ? ' selected="selected" ' : ''?> onclick="$('#artNumImgWrapper').slideUp('fast')">Другое..</option>
<?endif;?>
</select>