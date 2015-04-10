$(function() {
    $('#tab_fields').on('click', '.move_link, .set_state_link', function(e) {
        e.preventDefault();

        fcModal.initTabLoading();

        $.get($(this).data('url'), function() {
            fcModal.reloadActiveTab();
        });
    });
});