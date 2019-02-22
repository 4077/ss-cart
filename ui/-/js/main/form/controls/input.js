// head {
var __nodeId__ = "ss_cart_ui__main_form_controls_input";
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

            var input = $("input", widget.element);
            var form = input.closest(widget.options.formSelector);

            //var formWidget = form.getWidget();
            //
            //formWidget.test();

            var updateTimeout = 0;

            input.rebind("input." + __nodeId__ + " keypress." + __nodeId__, function () {
                if (updateTimeout) {
                    clearTimeout(updateTimeout);
                }

                updateTimeout = setTimeout(function () {
                    var updatePath = widget.options.updatePath;
                    var updateData = widget.options.updateData;

                    updateData.value = input.val();

                    request(updatePath, updateData);

                    clearTimeout(updateTimeout);
                }, 500);
            });
        },

        getValue: function () {
            var widget = this;

            return $("input", widget.element).val();
        }
    });
})(__nodeNs__, __nodeId__);
