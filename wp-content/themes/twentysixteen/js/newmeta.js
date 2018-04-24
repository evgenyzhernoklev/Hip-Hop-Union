jQuery(function(jQuery) {

    jQuery('.custom_upload_file_button').click(function() {
        formfield = jQuery(this).siblings('.custom_upload_file');
        preview = jQuery(this).siblings('.custom_file_prev');
        tb_show('', 'media-upload.php?type=file&TB_iframe=true');
        window.send_to_editor = function(html) {
            fileUrl = jQuery(html).attr('href');
            fileName = jQuery(html).text();
            formfield.val(fileUrl);
            preview.attr('href', fileUrl);
            preview.text(fileName);
            tb_remove();
        }

        return false;
    });

    jQuery('.custom_clear_file_button').click(function() {
        var defaultImage = jQuery(this).parent().siblings('.custom_default_file').text();
        jQuery(this).parent().siblings('.custom_upload_file').val('');
        jQuery(this).parent().siblings('.custom_file_prev').attr('href', "");
        jQuery(this).parent().siblings('.custom_file_prev').text("");
        return false;
    });

});
