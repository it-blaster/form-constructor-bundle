var FCValidationClass = function(form) {
    this.form = form;

    this.constraints = {};
};

FCValidationClass.prototype.getForm = function() {
    return $('form[name="'+ this.form +'"]');
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
        $scope = this.getForm();
    }

    var fields = this._findFields($scope);
    var errors = this._validateFields(fields);

    this._showErrors(errors, $scope);

    return !errors.length;
};

FCValidationClass.prototype._findFields = function($scope) {
    var fields = {};

    for (var field_name in this.constraints) {
        var $field = $scope.find('[name^="'+ this.form +'['+ field_name +']"]');

        if ($field.length) {
            fields[field_name] = {
                elements:    $field,
                constraints: this.constraints[field_name],
                field_type:  this._detectFieldType($field)
            };
        }
    }

    return fields;
};

FCValidationClass.prototype._detectFieldType = function(elements) {
    var element = elements.eq(0);

    return element.data('type') || element.attr('type');
};

FCValidationClass.prototype._validateFields = function(fields) {
    var errors = [];

    for (var field in fields) {
        var field_errors = this._validateField(fields[field]);

        if (field_errors.length) {
            errors.push({
                elements: fields[field].elements,
                errors:   field_errors
            });
        }
    }

    return errors;
};

FCValidationClass.prototype._validateField = function(field) {
    var value  = this._getFieldValue(field);
    var errors = [];

    for (var i in field.constraints) {
        if (!field.constraints[i].active) {
            continue;
        }

        var check_method = '_checkConstraint_'+ field.constraints[i].type;
        if (typeof this[check_method] !== 'function') {
            continue;
        }

        if (!this[check_method](value, field.constraints[i].params, field.elements)) {
            errors.push(field.constraints[i].message);
        }
    }

    return errors;
};

FCValidationClass.prototype._showErrors = function(errors, $scope) {
    this.getForm()
        .trigger('fc.beforeShowErrors', [ $scope ])
        .trigger('fc.showErrors', [ errors, $scope ])
        .trigger('fc.afterShowErrors', [ $scope ])
    ;

    return this;
};

FCValidationClass.prototype._getFieldValue = function(field) {
    var value_method = '_getFieldValue_'+ field.field_type;
    if (typeof this[value_method] !== 'function') {
        value_method = '_getFieldValue_default';
    }

    return this[value_method](field.elements);
};


/*
    VALUE GETTERS
 */


FCValidationClass.prototype._getFieldValue_default = function($elements) {
    if ($elements.length === 1) {
        return $elements.val();
    }

    var values = [];

    $elements.each(function() {
        values.push($(this).val());
    });

    return values;
};

FCValidationClass.prototype._getFieldValue_checkbox = function($elements) {
    if ($elements.length === 1) {
        return $elements.prop('checked');
    }

    return this._getFieldValue_default($elements.filter(':checked'));
};

FCValidationClass.prototype._getFieldValue_radio = function($elements) {
    return this._getFieldValue_default($elements.filter(':checked'));
};

FCValidationClass.prototype._getFieldValue_choice = function($elements) {
    if (!$elements.eq(0).is('select')) {
        $elements = $elements.filter(':checked');
    }

    return this._getFieldValue_default($elements);
};


/*
    CONSTRAINTS CHECKERS
 */


FCValidationClass.prototype._checkConstraint_not_blank = function(value) {
    if (typeof value === 'boolean' && !value) {
        return false;
    }

    if (typeof value === 'object' && !value.length) {
        return false;
    }

    return !!String(value).length;
};

FCValidationClass.prototype._checkConstraint_length = function(value, params) {
    if (!this._checkConstraint_not_blank(value) || typeof value.length === 'undefined') {
        return true;
    }

    if (params.min !== null && value.length < parseInt(params.min)) {
        return false;
    }

    return params.max === null || value.length <= parseInt(params.max);
};

FCValidationClass.prototype._checkConstraint_count = function(value, params) {
    if (typeof value !== 'object') {
        return true;
    }

    return this._checkConstraint_length(value, params);
};

FCValidationClass.prototype._checkConstraint_regex = function(value, regex) {
    if (!this._checkConstraint_not_blank(value) || typeof value !== 'string') {
        return true;
    }

    return regex.test(value);
};

FCValidationClass.prototype._checkConstraint_email = function(value) {
    return this._checkConstraint_regex(value, new RegExp('^[^@]+@[^@]+\\.[^@]+$'));
};

FCValidationClass.prototype._checkConstraint_integer = function(value) {
    return this._checkConstraint_regex(value, new RegExp('^-?\\d+$'));
};

FCValidationClass.prototype._checkConstraint_float = function(value) {
    return this._checkConstraint_regex(value, new RegExp('^-?(\\d+\\.)?\\d+$'));
};

FCValidationClass.prototype._checkConstraint_url = function(value) {
    return this._checkConstraint_regex(value, new RegExp('^https?:\\/\\/.+'));
};

FCValidationClass.prototype._checkConstraint_characters = function(value, params) {
    if (!this._checkConstraint_not_blank(value) || typeof value !== 'string') {
        return true;
    }

    var patterns = {
        cyrillic:   'а-яё',
        latin:      'a-z',
        digit:      '\\d',
        space:      '\\s',
        dash:       '\\-',
        underscore: '_'
    };

    var pattern = (!!params.match ? '^' : '') +'[';

    for (var i in params.sets) {
        if (typeof patterns[ params.sets[i] ] !== 'undefined') {
            pattern += patterns[ params.sets[i] ];
        }
    }

    pattern += ']'+ (!!params.match ? '+$' : '');

    return this._checkConstraint_regex(value, new RegExp(pattern, 'i'));
};

FCValidationClass.prototype._checkConstraint_one_of_set = function(value, params) {
    if (this._checkConstraint_not_blank(value)) {
        return true;
    }

    for (var i in params.fields) {
        var $el = this.getForm().find('[name="'+ this.form +'['+ params.fields[i] +']"]');
        if ($el.length !== 1) {
            continue;
        }

        var field_value = this._getFieldValue({
            field_type: this._detectFieldType($el),
            elements:   $el
        });

        if (this._checkConstraint_not_blank(field_value)) {
            return true;
        }
    }

    return false;
};

FCValidationClass.prototype._checkConstraint_comparison = function(value, params) {
    if (!this._checkConstraint_not_blank(value) || typeof value !== 'string') {
        return true;
    }

    if (this._checkConstraint_float(params.value)) {
        params.value = parseFloat(params.value);
        value        = parseFloat(value);
    }

    switch (params.type) {
        case 'not_equal':        return value !== params.value;
        case 'greater':          return value >   params.value;
        case 'greater_or_equal': return value >=  params.value;
        case 'less':             return value <   params.value;
        case 'less_or_equal':    return value <=  params.value;
    }

    return params.value === value;
};

FCValidationClass.prototype._checkConstraint_datetime = function(value, params, $elements) {
    if (!this._checkConstraint_not_blank(value) || typeof value !== 'string') {
        return true;
    }

    var format = $elements.data('format');
    if (typeof format !== 'string') {
        return true;
    }

    format = format
        .replace(/(\.|\/)/g, '\\$1')
        .replace(/(d|m|H|i)/g, '\\d{2}')
        .replace('g', '\\d{1,2}')
        .replace('Y', '\\d{4}')
        .replace('A', '(P|A)M')
    ;

    return this._checkConstraint_regex(value, new RegExp('^'+ format +'$'));
};