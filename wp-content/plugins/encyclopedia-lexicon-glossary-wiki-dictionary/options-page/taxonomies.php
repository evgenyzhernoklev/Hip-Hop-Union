<?php Namespace WordPress\Plugin\Encyclopedia ?>

<table class="form-table">
<tr>
  <th><label><?php echo I18n::t('Categories') ?></label></th>
  <td>
		<select <?php disabled(True) ?>>
			<option <?php disabled(True) ?> ><?php echo I18n::t('On') ?></option>
			<option <?php selected(True) ?> ><?php echo I18n::t('Off') ?></option>
		</select><?php Mocking_Bird::printProNotice('unlock') ?>
		<p class="help"><?php printf(I18n::t('Categories could help you structuring your %s.'), Post_Type_Labels::getItemPluralName()) ?></p>
	</td>
</tr>

<tr>
  <th><label for="encyclopedia_tags"><?php echo I18n::t('Tags') ?></label></th>
  <td>
		<select name="encyclopedia_tags" id="encyclopedia_tags">
			<option value="1" <?php selected(Options::get('encyclopedia_tags')) ?> ><?php echo I18n::t('On') ?></option>
			<option value="0" <?php selected(!Options::get('encyclopedia_tags')) ?> ><?php echo I18n::t('Off') ?></option>
		</select>
    <p class="help"><?php printf(I18n::t('Tags are necessary to create relations between %s automatically.'), Post_Type_Labels::getItemPluralName()) ?></p>
	</td>
</tr>
</table>
