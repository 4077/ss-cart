<html>
<body style="font-family: arial, sans-serif;">
<table>
    <tr>
        <td style="font-size: 13px;">
            Вы сделали заказ в магазине <b>&laquo;<a href="{STORE_URL}">{STORE_NAME}</a>&raquo;</b>
            <br>
            Номер вашего заказа: <b>{ORDER_ID}</b>
            <br>
            <br>
            <div style="margin-bottom: 5px; color: #404040; font-size: 17px; font-weight: bold">Вы отправили магазину следующие данные</div>
            {CLIENT_DATA}
            <br>
            <div style="margin-bottom: 5px; color: #404040; font-size: 17px; font-weight: bold">Содержание заказа</div>
            {ORDER_DATA}
            <br>
            <br>
        </td>
    </tr>
</table>
</body>
</html>