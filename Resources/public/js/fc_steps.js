$(function() {
    $('body')
        .on('click', 'form .change-step', function(e) {
            e.preventDefault();

            var $form  = $(this).closest('form');
            var $steps = $form.find('div.step-wrapper');
            var step   = $(this).data('step');
            var $new   = $steps.filter('[data-step="'+ step +'"]');

            if (!$new.hasClass('current-step')) {
                $steps.filter('.current-step').removeClass('current-step').addClass('hidden');
                $new.addClass('current-step').removeClass('hidden');

                $form.trigger('change-step', [step]);
            }
        })
        .on('change-step', 'form', function(e, step) {
            $('html, body').animate({ scrollTop: $(this).offset().top }, 500);

            var $items = $(this).find('.steps-list li');

            $items.removeClass('active')
                .filter('[data-step="'+ step +'"]').addClass('active')
            ;
        })
    ;
});