var FCValidationClass = function(form) {
    this.form  = form;
    this.$form = $('form[name="'+ this.form +'"]');

    this.constraints = {};
};

FCValidationClass.prototype.addConstraint = function(constraint) {
    if (typeof this.constraints[constraint.field] === 'undefined') {
        this.constraints[constraint.field] = [];
    }

    this.constraints[constraint.field].push(constraint);

    return this;
};

FCValidationClass.prototype.enableConstraint = function(field, type) {
    return this._setConstraintsActive(true, field, type);
};

FCValidationClass.prototype.disableConstraint = function(field, type) {
    return this._setConstraintsActive(false, field, type);
};

FCValidationClass.prototype.enableConstraints = function(field) {
    return this._setConstraintsActive(true, field);
};

FCValidationClass.prototype.disableConstraints = function(field) {
    return this._setConstraintsActive(false, field);
};

FCValidationClass.prototype._setConstraintsActive = function(active, field, type) {
    if (typeof this.constraints[field] === 'object') {
        for (var i in this.constraints[field]) {
            if (typeof type === 'undefined' || this.constraints[field][i].type === type) {
                this.constraints[field][i].active = active;
            }
        }
    }

    return this;
};

FCValidationClass.prototype.formIsValid = function($scope) {
    if (typeof $scope !== 'object' || !$scope.length) {
        $scope = this.$form;
    }

    var fields = this._findFields($scope);
    var errors = this._validateFields(fields);

    this._showErrors(errors);

    return !errors.length;
};

FCValidationClass.prototype._findFields = function($scope) {
    var fields = {};

    for (var field_name in this.constraints) {
        var $field = $scope.find('[name^="'+ this.form +'['+ field_name +']"]');

        if ($field.length) {
            fields[field_name] = {
                elements:    $field,
                constraints: this.constraints[field_name]
            };
        }
    }

    return fields;
};

FCValidationClass.prototype._validateFields = function(fields) {
    var errors = {};

    // TODO: Получаем значение поля, валидируем его, возвращаем список ошибок и селекторов

    return errors;
};

FCValidationClass.prototype._showErrors = function(errors) {
    this.$form
        .trigger('fc.beforeShowErrors')
        .trigger('fc.showErrors', [ errors ])
        .trigger('fc.afterShowErrors')
    ;

    return this;
};