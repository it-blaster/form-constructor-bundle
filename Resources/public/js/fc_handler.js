$(function() {
    $('body').on('submit', 'form', function(e) {
        var $form = $(this);
        var name  = $form.attr('name');
        var async = $form.is('[data-async="1"]');
        var valid = true;

        if (typeof window[name +'FCValidation'] === 'object') {
            valid = window[name +'FCValidation'].formIsValid();
        }

        if (async || !valid) {
            e.preventDefault();
        }

        if (!valid) {
            $form.trigger('fc.validationErrors');
        }

        if (async && valid) {
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
        }
    });
});