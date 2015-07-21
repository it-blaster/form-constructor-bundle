$(function() {
    $('body')
        .on('fc.beforeSend', 'form', function() {
            $(this).hide();
            $('div.fc_tab_loader').removeClass('hidden');
        })
        .on('fc.success', 'form', function() {
            Admin.setup_select2($(this));
            Admin.setup_icheck($(this));

            $(this).show();
            $('div.fc_tab_loader').addClass('hidden');
        })
        .on('fc.error', 'form', function() {
            $(this).show();
            $('div.fc_tab_loader').addClass('hidden');

            alert('Unrecognized error!');
        })
    ;
});