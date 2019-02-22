// head {
var __nodeId__ = "ss_cart_ui__main_form";
var __nodeNs__ = "ss_cart_ui";
// }

(function (__nodeNs__, __nodeId__) {
    $.widget(__nodeNs__ + "." + __nodeId__, {
        options: {},

        _create: function () {
            this.bind();
        },

        _destroy: function () {

        },

        _setOption: function (key, value) {
            $.Widget.prototype._setOption.apply(this, arguments);
        },

        bind: function () {
            var widget = this;

            widget.bindSubmitButton();
        },

        bindSubmitButton: function () {
            var widget = this;

            $(".submit_button", widget.element).rebind("click", function () {
                var fields = {};

                $(".form_control", widget.element).each(function () {
                    var controlWidget = $(this).getWidget();

                    fields[controlWidget.options.field] = controlWidget.getValue();
                });

                request(widget.options.paths.submit, {fields: fields});
            });
        },

        necessaryFieldsError: function (data) {
            var widget = this;

            $(".control_container[field]", widget.element).removeClass("necessary_error_highlight");

            for (var i in data.fields) {
                $(".control_container[field='" + data.fields[i] + "']", widget.element).addClass("necessary_error_highlight");
            }
        },

        removeErrorHighlighting: function (data) {
            var widget = this;

            $(".control_container[field='" + data.field + "']", widget.element).removeClass("necessary_error_highlight");
        }
    });
})(__nodeNs__, __nodeId__);
