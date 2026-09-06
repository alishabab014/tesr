(function ($) {
  jQuery(window).on("elementor:init", function () {
    var ControlBaseDataView = elementor.modules.controls.BaseData;
    var rtChooseItemView = elementor.modules.controls.BaseData.extend({
        ui: function ui() {
            var ui = ControlBaseDataView.prototype.ui.apply(this, arguments);
            ui.inputs = '[type="radio"]';
            return ui;
        },
        events: function events() {
            return _.extend(ControlBaseDataView.prototype.events.apply(this, arguments), {
              'mousedown label': 'onMouseDownLabel',
              'click @ui.inputs': 'onClickInput',
              'change @ui.inputs': 'onBaseInputChange'
            });
        },
        applySavedValue: function applySavedValue() {
            var currentValue = this.getControlValue();

            if (currentValue) {
              this.ui.inputs.filter('[value="' + currentValue + '"]').prop('checked', true);
            } else {
              this.ui.inputs.filter(':checked').prop('checked', false);
            }
        },
        onMouseDownLabel: function onMouseDownLabel(event) {
            var $clickedLabel = this.$(event.currentTarget),
                $selectedInput = this.$('#' + $clickedLabel.attr('for'));
            $selectedInput.data('checked', $selectedInput.prop('checked'));
        },
        onClickInput: function onClickInput(event) {
            if (!this.model.get('toggle')) {
              return;
            }

            var $selectedInput = this.$(event.currentTarget);

            if ($selectedInput.data('checked')) {
              $selectedInput.prop('checked', false).trigger('change');
            }
        },
        onBaseInputChange: function onBaseInputChange(event) {
            clearTimeout(this.correctionTimeout);
            var input = event.currentTarget,
                value = this.getInputValue(input),
                validators = this.validators.slice(0),
                settingsValidators = this.container.settings.validators[this.model.get('name')];

            if (settingsValidators) {
              validators = validators.concat(settingsValidators);
            }

            if (validators) {
              var oldValue = this.getControlValue(input.dataset.setting);
              var isValidValue = validators.every(function (validator) {
                return validator.isValid(value, oldValue);
              });

              if (!isValidValue) {
                this.correctionTimeout = setTimeout(this.setInputValue.bind(this, input, oldValue), 1200);
                return;
              }
            }

            this.updateElementModel(value, input);
            this.triggerMethod('input:change', event);
          },
    });

    elementor.addControlView('rozer-choose', rtChooseItemView);
  });
})(jQuery);