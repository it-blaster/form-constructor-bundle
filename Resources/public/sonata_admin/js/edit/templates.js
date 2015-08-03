$(function() {
    $('#tab_templates').on('click', '.set_state_link', function(e) {
        e.preventDefault();

        fcModal.initTabLoading();

        $.get($(this).data('url'), function() {
            fcModal.reloadActiveTab();
        });
    });
});