// If this file exists in theme directory, it will be loaded in <head> section

var imgLoading = new Image();
imgLoading.src = 'themes/its/img/loading.gif';
$(function() {
    $('.file').live('click', function() {
        if (navigator.userAgent.match(/(iPhone|iPod|iPad|Android|BlackBerry|Nokia)/)) {
            var html = '<form id="downloadForm" method="post" target="_blank" action="' + browser.baseGetData('download') + '">' +
            '<input type="hidden" name="dir" />' +
            '<input type="hidden" name="file" />' +
            '</form>';
            $('#dialog').html(html);
            $('#downloadForm input').get(0).value = browser.dir;
            $('#downloadForm input').get(1).value = $(this).data('name');
            $('#downloadForm').submit();
            $(this).stopImmediatePropagation();
            return false;
        }
    });
});
