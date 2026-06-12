<?php
/**
 *  Formulário
 *  @param array $e [ spacer, width, label, type, id, class, placeholder, value, mask, validate, min, max, message, options, readonly, attr ]
**/

function form($e = []) {

	$attr	 = "";
	$attr	.= ($e["type"] == "tel")		? ' pattern="\d*"'						: "";
	$attr	.= ($e["mask"])					? ' data-mask="' . $e["mask"] . '"'		: "";
	$attr	.= ($e["min"])					? ' data-min="'  . $e["min"]  . '"'		: "";
	$attr	.= ($e["max"])					? ' maxlength="' . $e["max"]  . '"'		: "";
	$attr	.= ($e["readonly"])				? " readonly"							: "";
	$attr	.= ($e["attr"])					? " " . $e["attr"]						: "";

	if ($e["type"] != "checkbox" && $e["type"] != "radio") {
		$attr .= ($e["validate"]) ? ' aria-required="true"' : "";
	}

	$class	 = "";
	$class	.= ($e["type"] == "password")	? " c-form__input--password"			: "";
	$class	.= ($e["type"] == "select")		? " c-form__input--select"				: "";
	$class	.= ($e["type"] == "textarea")	? " c-form__input--textarea"			: "";
	$class	.= ($e["validate"])				? " js-form-validate"					: "";
	$class	.= ($e["mask"])					? " js-form-mask"						: "";
	$class	.= ($e["class"])				? " " . $e["class"]						: "";

?>
<div class="c-form__espacador <?=$e["spacer"]?>"<?=$e["width"] ? ' style="width:'.$e["width"].'"' : ""?>>

	<?php if ($e["type"] == "checkbox" || $e["type"] == "radio") { ?>

	<fieldset class="c-form__recipiente"<?=$e["validate"] ? ' aria-required="true"' : "";?>>
		<legend class="c-form__etiqueta">
			<?=$e["label"]?>
			<?php if ($e["validate"]) { ?>
			<span class="c-form__etiqueta__aviso" aria-hidden="true">*</span>
			<?php } ?>
		</legend>
		<div class="c-form__check">
			<?php foreach ($e["options"] as $key => $value) { ?>
			<label class="c-form__check__item" for="<?="form-input-" . $e["id"] . "-" . $key?>">
				<input
					type="<?=$e["type"]?>"
					id="<?="form-input-" . $e["id"] . "-" . $key?>"
					name="<?=$e["name"] ?: $e["id"]?>"
					value="<?=$key?>"
					class="c-form__check__input | a-hidden<?=$class?>"
					data-nome="<?=$value?>"
					<?=$e["value"] == $key ? "checked" : ""?>
					<?=$attr?>>
				<i class="c-form__check__icone | a-mi--b" aria-hidden="true"></i>
				<span class="c-form__check__texto"><?=$value?></span>
			</label>
			<?php } ?> 
		</div>
	</fieldset>



	<?php } else if ($e["type"] == "select") { ?>

	<div class="c-form__recipiente">
		<label class="c-form__etiqueta" for="<?="form-input-" . $e["id"]?>">
			<?=$e["label"]?>
			<?php if ($e["validate"]) { ?>
			<span class="c-form__etiqueta__aviso" aria-hidden="true">*</span>
			<?php } ?>
		</label>
		<select
			id="<?="form-input-" . $e["id"]?>"
			name="<?=$e["name"] ?: $e["id"]?>"
			class="c-form__input <?=$class?>"
			<?=$attr?>>
			<?php foreach ($e["options"] as $key => $value) { ?>
			<option value="<?=$key?>"<?=$e["value"] == $key ? " selected" : ""?>><?=$value?></option>
			<?php } ?>
		</select>
	</div>



	<?php } else { ?>

	<div class="c-form__recipiente">
		<label class="c-form__etiqueta" for="<?="form-input-" . $e["id"]?>">
			<?=$e["label"]?>
			<?php if ($e["validate"]) { ?>
			<span class="c-form__etiqueta__aviso" aria-hidden="true">*</span>
			<?php } ?>
		</label>
		<?php if ($e["type"] == "textarea") { ?>
		<textarea
			id="<?="form-input-" . $e["id"]?>"
			name="<?=$e["name"] ?: $e["id"]?>"
			placeholder="<?=$e["placeholder"] ?: $e["label"]?>"
			class="c-form__input<?=$class?>"
			<?=$attr?>><?=$e["value"] ?: ""?></textarea>
		<?php } else { ?>
		<input
			type="<?=$e["type"] ?: "text"?>"
			id="<?="form-input-" . $e["id"]?>"
			name="<?=$e["name"] ?: $e["id"]?>"
			placeholder="<?=$e["placeholder"] ?: $e["label"]?>"
			value="<?=$e["value"]?>"
			class="c-form__input<?=$class?>"
			<?=$attr?>>
		<?php } ?>
	</div>

	<?php } ?>



	<?php if ($e["validate"]) { ?>
	<span class="c-form__mensagem" id="form-mensagem-<?=$e["id"]?>"><?=$e["message"] ?: "Campo obrigatório"?></span>
	<?php } ?>

	<?php if ($e["type"] == "password") { ?>
	<button type="button" class="c-form__botao | a-mi | js-form-password" aria-label="Mostrar senha">visibility</button>
	<?php } ?>

</div>
<?php } ?>