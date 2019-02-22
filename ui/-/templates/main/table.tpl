<div class="{__NODE_ID__}" instance="{__INSTANCE__}">

    <!-- if product -->
    <table class="items">

        <tr class="header">
            <td colspan="2">
            </td>
            <td>
                Цена,&nbsp;руб.
            </td>
            <td>
                Количество
            </td>
            <td>
                Сумма,&nbsp;руб.
            </td>
            <td>
                Удалить
            </td>
        </tr>

        <!-- product -->
        <tr class="item" n="{NUMBER}" product_id="{ID}" hover_listen="cart_table_item_{NUMBER}" key="{KEY}">

            <td class="image" hover="hover" hover_group="cart_table_item_{NUMBER}" width="1">
                <!-- product/image -->
                <div class="image">
                    {CONTENT}
                </div>
                <!-- / -->
            </td>

            <td class="description" hover="hover" hover_group="cart_table_item_{NUMBER}">
                <div class="name">{NAME}</div>
                <!-- product/props -->
                <div class="props">{CONTENT}</div>
                <!-- / -->
            </td>

            <td class="price" hover_group="cart_table_item_{NUMBER}">
                <!-- product/price_without_discount -->
                <div class="price_without_discount">
                    <div class="value">
                        {VALUE}
                    </div>
                    <div class="tag">
                        <div class="icon fa fa-tag"></div>
                        <div class="overlay"></div>
                        <div class="label">-{DISCOUNT}%</div>
                    </div>
                </div>
                <!-- / -->
                {PRICE}
            </td>

            <td hover="hover" hover_group="cart_table_item_{NUMBER}">
                <div class="quantity">
                    <div class="units">{UNITS}</div>
                    {QUANTIFY}
                </div>
            </td>

            <td class="cost" hover_group="cart_table_item_{NUMBER}">
                {COST}
            </td>

            {DELETE_BUTTON_TD}
        </tr>
        <!-- / -->

        <tr class="summary">
            <td class="caption" colspan="7">
                <!-- if total_cost -->
                <div class="total_cost">
                    Итого: <span class="value">{TOTAL_COST} руб.</span>
                </div>
                <!-- global_discount -->
                <div class="global_discount">
                    Итого со скидкой: <span class="value">{TOTAL_COST} руб.</span>
                </div>
                <!-- / -->
                <!-- / -->

                <!-- total_cost_info -->
                <div class="total_cost_info">
                    {CONTENT}
                </div>
                <!-- / -->
            </td>
        </tr>

    </table>

    <div class="feed">
        <!-- product -->
        <div class="item" product_id="{ID}" hover_listen="cart_table_item_{NUMBER}">

            <div class="r1">
                <div class="image" hover="hover" hover_group="cart_table_item_{NUMBER}">
                    <!-- product/image -->
                    {CONTENT}
                    <!-- / -->
                </div>

                <div class="description_and_delete_button">
                    <div class="description" hover="hover" hover_group="cart_table_item_{NUMBER}">
                        <div class="name">{NAME}</div>
                        <!-- product/props -->
                        <div class="props">{CONTENT}</div>
                        <!-- / -->
                    </div>

                    {DELETE_BUTTON}
                </div>
            </div>

            <div class="r2">
                <!-- if price_display -->
                <div class="price" hover_group="cart_table_item_{NUMBER}"><!-- product/price_without_discount -->
                    <div class="price_without_discount">
                        <div class="value">
                            {VALUE} руб./{product/UNITS}
                        </div>
                        <div class="tag">
                            <div class="icon fa fa-tag"></div>
                            <div class="overlay"></div>
                            <div class="label">-{DISCOUNT}%</div>
                        </div>
                    </div>
                    <!-- / -->
                    {PRICE} руб./{UNITS}
                </div>
                <!-- / -->

                <div hover="hover" hover_group="cart_table_item_{NUMBER}">
                    <div class="quantity">
                        {QUANTIFY}
                    </div>
                </div>

                <!-- if price_display -->
                <div class="cost" hover_group="cart_table_item_{NUMBER}">
                    {COST} руб.
                </div>
                <!-- / -->
            </div>

        </div>
        <!-- / -->

        <div class="summary">
            <!-- if total_cost -->
            <div class="total_cost">
                Итого: <span class="value">{TOTAL_COST} руб.</span>
            </div>
            <!-- global_discount -->
            <div class="global_discount">
                Итого со скидкой: <span class="value">{TOTAL_COST} руб.</span>
            </div>
            <!-- / -->
            <!-- / -->

            <!-- total_cost_info -->
            <div class="total_cost_info">
                {CONTENT}
            </div>
            <!-- / -->
        </div>
    </div>
    <!-- / -->

    {CAROUSEL}

</div>