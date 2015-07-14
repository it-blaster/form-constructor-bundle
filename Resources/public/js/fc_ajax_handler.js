$(function() {
    $('body').on('submit', 'form[data-async="1"]', function(e) {
        e.preventDefault();

        var $form = $(this);

        $.ajax({
            url:    $form.attr('action'),
            method: $form.attr('method'),
            data:   $form.serialize(),

            beforeSend: function(jqXHR, settings) {
                $form.trigger('fc.beforeSend', [jqXHR, settings]);
            },

            success: function(data, textStatus, jqXHR) {
                $form
                    .trigger('fc.success', [data, textStatus, jqXHR])
                    .replaceWith(data.form)
                ;
            },

            error: function(jqXHR, textStatus, errorThrown) {
                $form.trigger('fc.error', [jqXHR, textStatus, errorThrown]);
            }
        });
    });
});