<?php Namespace WordPress\Plugin\Encyclopedia ?>

<table class="form-table">
<tr>
  <th><label for="related_items"><?php printf(I18n::t('Display related %s'), Post_Type_Labels::getItemPluralName()) ?></label></th>
  <td>
		<input type="radio" id="related_items_below" <?php checked(True) ?>> <label for="related_items_below"><?php echo I18n::t('below the content') ?></label><br>
		<input type="radio" id="related_items_above" <?php disabled(True) ?>> <label for="related_items_above"><?php echo I18n::t('above the content') ?></label><?php Mocking_Bird::printProNotice('unlock') ?><br>
		<input type="radio" id="related_items_none" <?php disabled(True) ?>> <label for="related_items_none"><?php printf(I18n::t('Do not show related %s.'), Post_Type_Labels::getItemPluralName()) ?></label><?php Mocking_Bird::printProNotice('unlock') ?>
	</td>
</tr>

<tr>
  <th><label><?php printf(I18n::t('Number of related %s'), Post_Type_Labels::getItemPluralName()) ?></label></th>
  <td>
    <input type="number" value="10" <?php disabled(True) ?>><?php Mocking_Bird::printProNotice('unlock') ?>
    <p class="help"><?php printf(I18n::t('Number of related %s which should be shown.'), Post_Type_Labels::getItemPluralName()) ?></p>
	</td>
</tr>

<tr>
  <th><label for="prefix_filter_for_singulars"><?php echo I18n::t('Prefix filter') ?></label></th>
  <td>
		<select name="prefix_filter_for_singulars" id="prefix_filter_for_singulars">
			<option value="1" <?php selected(Options::get('prefix_filter_for_singulars')) ?> ><?php echo I18n::t('On') ?></option>
			<option value="0" <?php selected(!Options::get('prefix_filter_for_singulars')) ?> ><?php echo I18n::t('Off') ?></option>
		</select>
		<p class="help"><?php echo I18n::t('Enables or disables the prefix filter above the title in the single view.') ?></p>
	</td>
</tr>

<tr>
	<th><label for="prefix_filter_singular_depth"><?php echo I18n::t('Prefix filter depth') ?></label></th>
	<td>
    <input type="number" name="prefix_filter_singular_depth" id="prefix_filter_singular_depth" value="<?php echo Options::get('prefix_filter_singular_depth') ?>" min="1" max="<?php echo PHP_INT_MAX ?>" step="1">
    <p class="help"><?php echo I18n::t('The depth of the prefix filter is usually the number of rows with prefixes which are shown.') ?></p>
  </td>
</tr>
</table>
