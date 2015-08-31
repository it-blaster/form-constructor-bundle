var fcModal = {
    initiated: false,

    $modal:    null,
    $title:    null,
    $loader:   null,
    $content:  null,
    $cancel:   null,
    $save:     null,
    $ok:       null,
    $target:   null,
    $tabs:     null,

    init: function() {
        this.initiated = true;

        this.$modal    = $('#fc_modal');
        this.$title    = this.$modal.find('.modal-title');
        this.$loader   = this.$modal.find('.fc_modal_loader');
        this.$content  = this.$modal.find('.fc_modal_content');
        this.$cancel   = this.$modal.find('.modal_cancel_link');
        this.$save     = this.$modal.find('.modal_save_link');
        this.$ok       = this.$modal.find('.modal_ok_link');
        this.$tabs     = $('#fc_form_tabs div.tab-pane');

        this.bind();
    },

    bind: function() {
        var _this = this;

        this.$modal.on('show.bs.modal', function(e) {
            _this.$target = $(e.relatedTarget);

            _this.$title.text(_this.$target.data('title'));
            _this.$loader.show();
            _this.$content.empty();
            _this.$cancel.addClass('disabled');
            _this.$save.addClass('disabled hidden');
            _this.$ok.addClass('disabled hidden');

            _this.$content.load(_this.$target.data('url'), function(response, status, xhr) {
                _this.$cancel.removeClass('disabled');

                if (status != 'error') {
                    _this.updateControls();
                    _this.applyScope(_this.$target.data('scope'));
                } else {
                    _this.renderXhrError(xhr);
                }

                _this.$loader.hide();
            });
        });

        this.$modal.on('change', '.fc_type_choice', function() {
            var $form = _this.$modal.find('form');

            if (!$(this).val()) {
                return;
            }

            _this.$title.append(': '+ $(this).find('option:selected').text());
            _this.$loader.show();
            _this.$content.hide();
            _this.$cancel.addClass('disabled');

            _this.$content.load($form.attr('action'), $form.serializeArray(), function(response, status, xhr) {
                _this.$cancel.removeClass('disabled');

                if (status != 'error') {
                    _this.updateControls();

                    _this.$save.removeClass('disabled hidden');
                } else {
                    _this.renderXhrError(xhr);
                }

                _this.$content.show();
                _this.$loader.hide();
            });
        });

        this.$save.on('click', function() {
            var $form = _this.$modal.find('form');

            _this.$loader.show();
            _this.$cancel.addClass('disabled');
            _this.$save.addClass('disabled');

            if (typeof CKEDITOR !== 'undefined') {
                for (var i in CKEDITOR.instances) {
                    CKEDITOR.instances[i].updateElement();
                }
            }

            $.post($form.attr('action'), $form.serializeArray(), function(response) {
                _this.$loader.hide();
                _this.$cancel.removeClass('disabled');
                _this.$save.removeClass('disabled');

                if (response.success) {
                    _this.$modal.modal('hide');

                    _this.reloadActiveTab()
                } else {
                    _this.$content.html(response.view);

                    _this.updateControls();
                }
            });
        });

        this.$ok.on('click', function() {
            _this.$loader.show();
            _this.$content.empty();
            _this.$cancel.addClass('disabled');
            _this.$ok.addClass('disabled');

            $.get(_this.$ok.data('url'), function(response) {
                _this.$loader.hide();
                _this.$cancel.removeClass('disabled');
                _this.$ok.removeClass('disabled');

                if (response.success) {
                    _this.$modal.modal('hide');

                    _this.reloadActiveTab();
                } else {
                    _this.$content.html(response.view);
                }
            });
        });
    },

    updateControls: function() {
        Admin.setup_select2(this.$modal);
        Admin.setup_icheck(this.$modal);
    },

    applyScope: function(scope) {
        switch (scope) {
            case 'edit':
                this.$save.removeClass('hidden disabled');
                break;
            case 'delete':
                this.$ok
                    .removeClass('hidden disabled')
                    .data('url', this.$target.data('delete-url'));
                break;
        }
    },

    renderXhrError: function(xhr) {
        this.$content.text('Error '+ xhr.status +': '+ xhr.statusText);
    },

    getActiveTab: function() {
        return this.$tabs.filter('.active');
    },

    initTabLoading: function() {
        var $tab = this.getActiveTab();

        $tab.find('.fc_form_tab_content').remove();
        $tab.find('.fc_tab_loader').removeClass('hidden');
    },

    reloadActiveTab: function() {
        var $tab = this.getActiveTab();

        this.initTabLoading();

        $tab.load($tab.data('url'), function(response, status, xhr) {
            if (status == 'error') {
                $tab.text('Error '+ xhr.status +': '+ xhr.statusText);
            }
        });
    }
};

$(function() {
    fcModal.init();
});