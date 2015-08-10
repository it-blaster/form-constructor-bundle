$(function() {
    $('#tab_behaviors')
        .on('click', '.set_state_link', function(e) {
            e.preventDefault();

            fcModal.initTabLoading();

            $.get($(this).data('url'), function() {
                fcModal.reloadActiveTab();
            });
        })
        .on('click', '.new-behavior-link', function(e) {
            e.preventDefault();

            fcModal.initTabLoading();

            $.get($(this).attr('href'), function(response, status, xhr) {
                if (status == 'error') {
                    fcModal.getActiveTab().text('Error '+ xhr.status +': '+ xhr.statusText);
                } else if (response.success) {
                    fcModal.reloadActiveTab();
                } else {
                    fcModal.getActiveTab().text(response.view);
                }
            });
        })
    ;
});