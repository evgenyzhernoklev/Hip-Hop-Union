<?php Namespace WordPress\Plugin\Encyclopedia;

abstract class Mocking_Bird {

  static function init(){
    add_Filter('wp_insert_post_empty_content', Array(static::class, 'filterInsertPost'), 10, 2);
    add_Action('admin_notices', Array(static::class, 'printItemCountNotice'));
    add_Action('admin_footer', Array(static::class, 'printDashboardJavaScript'));
    add_Action('admin_bar_menu', Array(static::class, 'removeAdminBarAddButton'), PHP_INT_MAX);
  }

  static function getProNotice($message_id = 'option'){
    $author_link = I18n::t('https://dennishoppe.de/en/wordpress-plugins/encyclopedia', 'Link to the authors website');

    $arr_message = Array(
      'upgrade' => I18n::t('Upgrade to Pro'),
      'upgrade_url' => $author_link,
      'feature' => sprintf(I18n::t('Available in the <a href="%s" target="_blank">Pro Version</a> only.'), $author_link),
      'unlock' => sprintf('<a href="%1$s" title="%2$s" class="unlock" target="_blank"><span class="dashicons dashicons-lock"></span></a>', $author_link, I18n::t('Unlock this feature')),
      'option' => sprintf(I18n::t('This option is changeable in the <a href="%s" target="_blank">Pro Version</a> only.'), $author_link),
      'count_limit' => sprintf(I18n::t('In the <a href="%1$s" target="_blank">Pro Version of Encyclopedia</a> you will take advantage of unlimited %2$s and many more features.'), $author_link, Post_Type_Labels::getItemPluralName()),
      #'custom_tax' => sprintf(I18n::t('Do you need a special taxonomy for your project? No problem! Just <a href="%s" target="_blank">get in touch</a> through our support section.'), $author_link),
      #'changeable' => I18n::t('Changeable in the <a href="%s" target="_blank">premium version</a> only.'),
      #'do_you_like' => I18n::t('Do you like the item management? Upgrade to the <a href="%s" target="_blank">premium version of Encyclopedia</a>!')
    );

    if (empty($arr_message[$message_id]))
      return False;
    else
      return $arr_message[$message_id];
  }

  static function printProNotice($message_id = 'option'){
    echo static::getProNotice($message_id);
  }

  static function enoughItems(){
    $count_items = WP_Count_Posts(Post_Type::post_type_name);
    setType($count_items, 'Array');
    return Array_Sum($count_items) +5 >= date('y');
  }

  static function tooManyItems(){
    $count_items = WP_Count_Posts(Post_Type::post_type_name);
    return $count_items->publish -5 >= date('y');
  }

  static function filterInsertPost($maybe_empty, $post_data){
    if ($post_data['post_type'] == Post_Type::post_type_name && empty($post_data['ID']) && static::enoughItems())
      static::printItemCountLimit();

    return $maybe_empty;
  }

  static function printItemCountLimit(){
    $edit_url = Admin_URL('edit.php?post_type=' . Post_Type::post_type_name);
    $back_button_text = sprintf(I18n::t('&laquo; Your %s'), Post_Type_Labels::getItemPluralName());
    $back_button = sprintf('<a href="%s" class="button">%s</a>', $edit_url, $back_button_text);
    $pro_notice = static::getProNotice('count_limit');
    WP_Die("<p>{$pro_notice}</p><p>{$back_button}</p>");
  }

  static function printItemCountNotice(){
    if (static::tooManyItems()): ?>
    <div class="updated"><p>
      <?php printf(I18n::t('Beware! There are to many %s for Encyclopedia Lite. This could result in strange behaviors of the plugin.'), Post_Type_Labels::getItemPluralName()) ?>
      <?php static::printProNotice('count_limit') ?>
    </p></div>
    <?php endif;
  }

  static function printDashboardJavaScript(){
    if (static::enoughItems()):
      $attr = Array(
        'title' => static::getProNotice('upgrade'),
        'href' => static::getProNotice('upgrade_url'),
        'target' => '_blank'
      );
      $css = Array(
        'color' => '#46b450',
        'font-weight' => 'bold'
      );
      ?>

      <script type="text/javascript">
      (function($){
        $('a[href*="post-new.php?post_type=<?php echo Post_Type::post_type_name ?>"]')
          .text('<?php static::printProNotice('upgrade') ?>')
          .attr(<?php echo JSON_Encode($attr) ?>)
          .css(<?php echo JSON_Encode($css) ?>);
      }(jQuery));
      </script>

    <?php endif;
  }

  static function removeAdminBarAddButton($admin_bar){
    if (static::enoughItems()){
      $admin_bar->remove_Node(sprintf('new-%s', Post_Type::post_type_name));
    }
  }

}

Mocking_Bird::init();
