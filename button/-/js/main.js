// head {
var __nodeId__ = "ss_cart_button__main";
var __nodeNs__ = "ss_cart_button";
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

            var $itemsCount = $(".items_count", $w);

            var cartUpdateHandler = function (data) {
                if (o.instance === data.instance) {
                    if (data.itemsCount > 0) {
                        $itemsCount.html(data.itemsCount);

                        $w.fadeIn(200);
                    } else {
                        $w.fadeOut(200);
                    }
                }
            };

            w.e('ss/cart/delete_item' , cartUpdateHandler);
            w.e('ss/cart/add_item', cartUpdateHandler);
        },

        bind: function () {
            var w = this;
            var o = w.options;
            var $w = w.element;

            var $window = $(window);

            $w.bind("click", function () {
                w.r('openCart');
            });

            var prevScrollTop = $window.scrollTop();

            var render = function () {
                if (o.hideOnScroll) {
                    var scrollTop = $window.scrollTop();

                    if ($window.width() <= o.hideMaxWw) {
                        // to bottom
                        if (prevScrollTop < scrollTop) {
                            $w.fadeOut(200);
                        }

                        // to top
                        if (prevScrollTop > scrollTop) {
                            $w.fadeIn(200);
                        }
                    } else {
                        $w.fadeIn(200);
                    }

                    prevScrollTop = scrollTop;
                }
            };

            $window.scroll(render);
            $window.resize(render);

            // hardcode
            if ($(".ss_cats_ui_cpanel__main").length) {
                /*$(".ss_cats_ui_cpanel__main").rebind("mouseenter." + __nodeId__, function () {*/
                    $w.animate({
                        left:   27,
                        bottom: 100
                    }, 0);
                 /*});*/
            }
        }
    });
})(__nodeNs__, __nodeId__);
