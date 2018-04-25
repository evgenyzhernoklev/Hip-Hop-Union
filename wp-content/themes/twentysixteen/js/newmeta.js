(function($) {
  $(document).ready( function() {

    $('.custom_upload_file_button').click(function() {
      var $fieldLink = $(this).siblings('.custom_upload_file'),
          $fieldName = $(this).siblings('.custom_upload_file_name'),
          $preview = $(this).siblings('.custom_file_prev');

      tb_show('', 'media-upload.php?type=file&TB_iframe=true');
      window.send_to_editor = function(html) {
        var fileUrl = $(html).attr('href'),
            fileName = $(html).text();

        $fieldLink.val(fileUrl);
        $fieldName.val(fileName);
        $preview.attr('href', fileUrl);
        $preview.text(fileName);
        tb_remove();
      }

      return false;
    });

    $('.custom_clear_file_button').click(function() {
      var $parent = $(this).parent(),
          defaultImage = $parent.siblings('.custom_default_file').text();

      $parent.siblings('.custom_upload_file').val('');
      $parent.siblings('.custom_upload_file_name').val('');
      $parent.siblings('.custom_file_prev').attr('href', "");
      $parent.siblings('.custom_file_prev').text("");

      return false;
    });

  });
})( jQuery );
