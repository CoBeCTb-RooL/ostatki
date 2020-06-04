<?php
$item = $MODEL['item'];
$error = $MODEL['error'];
$title = $item ? 'Редактирование:' : 'Создание:';
?>




<?if(!$MODEL['error']):?>
	<div class="view">
		<h1><?=$title?></h1>
		<form id="form" method="" action="/<?=ADMIN_URL_SIGN?>/geo/countryEditSubmit" onsubmit="Geo.Country.editSubmit(); return false; " target="frame7">
			<input type="hidden" name="id" value="<?=$item->id?>" />
			<div class="field-wrapper">
				<span class="label">Название<span class="required">*</span>: </span>
				<span class="value"><input type="text" name="name" value="<?=htmlspecialchars($item->name)?>"></span>
			</div>
			<!--<div class="field-wrapper">
				<span class="label">Крупный? </span>
				<span class="value"><input type="checkbox" name="isLarge" <?=$item->isLarge ? ' checked="checked" ' : ''?>></span>
			</div>-->
			<p>
			<input type="submit" value="сохранить" />
		</form>
	</div>
<?else:?>
	<?=$MODEL['error']?>
<?endif; ?>




<iframe name="frame7" style="display: none; width: 98%; border: 1px dashed #ccc; background: #ececec;  height: 400px;">111</iframe>
