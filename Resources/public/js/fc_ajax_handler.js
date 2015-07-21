$(function() {
    $('body').on('submit', 'form[data-async="1"]', function(e) {
        e.preventDefault();

        var $form = $(this);

        $form.ajaxSubmit({
            dataType: 'json',

            beforeSend: function(jqXHR, settings) {
                $form.trigger('fc.beforeSend', [jqXHR, settings]);
            },

            success: function(data, textStatus, jqXHR) {
                var $new_form = $(data.form);

                $form.replaceWith($new_form);

                $new_form.trigger('fc.success', [data, textStatus, jqXHR]);
            },

            error: function(jqXHR, textStatus, errorThrown) {
                $form.trigger('fc.error', [jqXHR, textStatus, errorThrown]);
            }
        });
    });
});