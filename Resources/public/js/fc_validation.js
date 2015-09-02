var FCValidationClass = function(form) {
    this.form = form;

    this.constraints = {};
};

FCValidationClass.prototype.addConstraint = function(constraint) {
    this.constraints[constraint.id] = constraint;

    return this;
};

FCValidationClass.prototype.enableConstraint = function(field, type) {
    return this._setConstraintsActive(true, { field: field, type: type });
};

FCValidationClass.prototype.disableConstraint = function(field, type) {
    return this._setConstraintsActive(false, { field: field, type: type });
};

FCValidationClass.prototype.enableConstraints = function(field) {
    return this._setConstraintsActive(true, { field: field });
};

FCValidationClass.prototype.disableConstraints = function(field) {
    return this._setConstraintsActive(false, { field: field });
};

FCValidationClass.prototype._setConstraintsActive = function(active, filter) {
    for (var i in this.constraints) {
        if (this._constraintFiltered(this.constraints[i], filter)) {
            this.constraints[i].active = active;
        }
    }

    return this;
};

FCValidationClass.prototype._constraintFiltered = function(constraint, filter) {
    if (typeof filter === 'object') {
        for (var i in filter) {
            if (constraint[i] !== filter[i]) {
                return false;
            }
        }
    }

    return true;
};

FCValidationClass.prototype.formIsValid = function($scope) {
    if (typeof $scope !== 'object' || !$scope.length) {
        $scope = $('form[name="'+ this.form +'"]');
    }

    // TODO
    return true;
};