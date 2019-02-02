<table style="border-collapse: separate !important; border-spacing: 1px !important;">
    <tr>
        <td style="padding: 5px 15px; background-color: #404040; color: #FFFFFF; font-size: 13px; font-weight: bold" colspan="2">Наименование</td>
        <td style="padding: 5px 15px; background-color: #404040; color: #FFFFFF; font-size: 13px; font-weight: bold">Цена,&nbsp;руб.</td>
        <td style="padding: 5px 15px; background-color: #404040; color: #FFFFFF; font-size: 13px; font-weight: bold" nowrap>Кол-во</td>
        <td style="padding: 5px 15px; background-color: #404040; color: #FFFFFF; font-size: 13px; font-weight: bold">Сумма,&nbsp;руб.</td>
    </tr>
    <!-- item -->
    <tr>
        <td style="padding: 5px; background-color: #F0F0F0; font-size: 13px">
            <!-- if item/image -->
            <div style="width: 100px">
                <!-- item/image -->
                <a href="{SRC}" target="_blank"><img src="{THUMB_SRC}"></a>
                <!-- / -->
            </div>
            <!-- / -->
        </td>
        <td style="padding: 5px 15px; background-color: #F0F0F0; font-size: 13px" valign="top">
            <div style="font-weight: bold">{NAME}</div>
            <div style="color: #8E703E">{DESCRIPTION}</div>
        </td>
        <td style="padding: 5px 15px; background-color: #F0F0F0; color: #606060; font-size: 13px" align="center">{PRICE}</td>
        <td style="padding: 5px 15px; background-color: #F0F0F0; color: #606060; font-size: 13px" align="center">{QUANTITY}</td>
        <td style="padding: 5px 15px; background-color: #F0F0F0; color: #ff0000; font-size: 13px" align="center">{COST}</td>
    </tr>
    <!-- / -->
    <!-- delivery -->
    <tr>
        <td style="padding: 5px 15px; background-color: #E0E0E0; color: #000000; font-size: 13px; font-weight: bold" align="right" colspan="4">Сумма заказа</td>
        <td style="padding: 5px 15px; background-color: #E0E0E0; color: #ff0000; font-size: 13px; font-weight: bold" align="center">{ORDER_TOTAL_COST}</td>
    </tr>
    <tr>
        <td style="padding: 5px 15px; background-color: #E0E0E0; color: #000000; font-size: 13px; font-weight: bold" align="right" colspan="4">Стоимость доставки<!-- if delivery/option --><br>
            <div style="padding: 5px 0;">
                <!-- delivery/option -->
                <div style="float: right; color: #606060; margin: 2px;">
                    <span style="margin-right: 5px; font-weight: bold;">{NAME}</span>
                    <span style="margin-right: 10px;">{DESCRIPTION}</span>
                    <span style="background-color: #{VALUE_BG_COLOR}; color: #{VALUE_COLOR}; padding: 0 3px;">{OPERATOR}{VALUE}</span>
                </div>
                <div style="clear: both"></div>
                <!-- / -->
            </div>
            <!-- / -->
        </td>
        <td style="padding: 5px 15px; background-color: #E0E0E0; color: #ff0000; font-size: 13px; font-weight: bold" align="center">{COST}</td>
    </tr>
    <!-- / -->
    <tr>
        <td style="padding: 5px 15px; background-color: #404040; color: #FFFFFF; font-size: 13px; font-weight: bold" align="right" colspan="4">{TOTAL_COST_LABEL}</td>
        <td style="padding: 5px 15px; background-color: #DC3232; color: #FFFFFF; font-size: 13px; font-weight: bold" align="center">{TOTAL_COST}</td>
    </tr>
    <!-- global_discount -->
    <tr>
        <td style="padding: 5px 15px; background-color: #404040; color: #FFFFFF; font-size: 13px; font-weight: bold" align="right" colspan="4">{TOTAL_COST_LABEL}</td>
        <td style="padding: 5px 15px; background-color: #DC3232; color: #FFFFFF; font-size: 13px; font-weight: bold" align="center">{TOTAL_COST}</td>
    </tr>
    <!-- / -->
</table>

