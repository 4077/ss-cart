// head {
var __nodeId__ = "ss_cart_ui__main_table";
var __nodeNs__ = "ss_cart_ui";
// }

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
                var $item = $(".item[product_id='" + data.productId + "']", $w);

                p(".item[product_id='" + data.productId + "']");

                if ($item.length) {
                    w.mr('reload');
                }
            };

            w.e('ss/cart/update_product', itemChangeHandler);

            w.e('ss/cart/add_product', function () {
                w.mr('reload');
            });

            w.e('ss/cart/delete_product', function () {
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

            $(".item[product_id]", $w).click(function () {
                if (carouselEnabled) {
                    var productId = $(this).attr("product_id");

                    window.history.replaceState(null, null, '/товары/' + productId + '/');

                    w.w('carousel').show($(this).attr("n"));
                }
            });

            render();

            $(window).resize(function () {
                render();
            });
        }
    });
})(__nodeNs__, __nodeId__);
