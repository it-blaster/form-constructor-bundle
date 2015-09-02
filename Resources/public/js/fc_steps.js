$(function() {
    $('body')
        .on('click', 'form .change-step', function(e) {
            e.preventDefault();

            var $form = $(this).closest('form');
            var step  = $(this).data('step');

            if (!$form.find('div.step-wrapper[data-step="'+ step +'"]').hasClass('current-step')) {
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
            var $steps = $(this).find('div.step-wrapper');
            var $items = $(this).find('.steps-list li');

            $steps.filter('.current-step').removeClass('current-step').addClass('hidden');
            $steps.filter('[data-step="'+ step +'"]').addClass('current-step').removeClass('hidden');

            $items.removeClass('active')
                .filter('[data-step="'+ step +'"]').addClass('active')
            ;
        })
    ;
});