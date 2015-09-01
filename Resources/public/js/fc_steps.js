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

                $form.trigger('before-change-step', [step]);
                $form.trigger('change-step', [step]);
            }
        })
        .on('before-change-step', 'form', function() {
            $('html, body').animate({
                scrollTop: $(this).offset().top
            }, 500);
        })
        .on('change-step', 'form', function(e, step) {
            var $items = $(this).find('.steps-list li');

            $items.removeClass('active')
                .filter('[data-step="'+ step +'"]').addClass('active')
            ;
        })
    ;
});