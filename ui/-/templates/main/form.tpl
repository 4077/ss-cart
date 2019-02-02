<div class="{__NODE_ID__}" instance="{__INSTANCE__}">

    <div class="header">Оформление заказа</div>

    <div class="form">
        <div class="fields">
            <!-- field -->
            <div class="field">

                <div class="label">{LABEL}<!-- field/necessary --><span class="necessary">*</span><!-- / --></div>
                <div class="control_container" field="{ALIAS}">{CONTROL}</div>

                <div class="cb"></div>
            </div>
            <!-- / -->

            <div class="field">
                <div class="label"></div>
                <div class="control_container">
                    {DELIVERY_TOGGLE_BUTTON}
                </div>
            </div>

            <!-- delivery -->
            <div class="field">

                <div class="label">Адрес доставки</div>
                <div class="control_container" field="address">{CONTROL}</div>

                <div class="cb"></div>
            </div>
            <!-- / -->
        </div>
    </div>

    <div class="submit">
        <div class="submit_button" hover="hover">
            <div class="spinner_container">
                <div class="spinner">
                    <div class="bounce1"></div>
                    <div class="bounce2"></div>
                    <div class="bounce3"></div>
                </div>
            </div>
            <div class="label">{SUBMIT_BUTTON_LABEL}</div>
        </div>
    </div>

</div>
