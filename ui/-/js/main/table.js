(function (__nodeNs__, __nodeId__) {
    $.widget(__nodeNs__ + "." + __nodeId__, $.ewma.node, {
        options: {},

        __create: function () {
            var w = this;
            var o = w.options;
            var $w = w.element;

            w.bind();
            w.bindEvents();
        },

        bindEvents: function () {
            var w = this;
            var o = w.options;
            var $w = w.element;

            ///

            var itemChangeHandler = function (data) {
                var $item = $(".item[key='" + data.itemKey + "']", $w);

                if ($item.length) {
                    w.mr('reload');
                }
            };

            w.e('ss/cart/stage/update_item', itemChangeHandler);
            w.e('ss/cart/delete_item' , itemChangeHandler);
            w.e('ss/cart/update_item', itemChangeHandler);

            w.e('ss/cart/add_item', function () {
                w.mr('reload');
            });

            ///
        },

        bind: function () {
            var w = this;
            var o = w.options;
            var $w = w.element;

            var carouselEnabled = false;

            var render = function () {
                carouselEnabled = $(window).width() >= 919;
            };

            if (!o.noContainer) {
                $(".item[product_id]", $w).click(function () {
                    if (carouselEnabled) {
                        var productId = $(this).attr("product_id");

                        window.history.replaceState(null, null, '/товары/' + productId + '/');

                        w.w('carousel').show($(this).attr("n"));
                    }
                });
            }

            render();

            $(window).resize(function () {
                render();
            });
        }
    });
})(__nodeNs__, __nodeId__);
