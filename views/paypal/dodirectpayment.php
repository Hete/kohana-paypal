<?php defined('SYSPATH') or die('No direct script access.'); ?>

<div class="row-fluid">

    <div class="span4">

        <h5><?php echo __("paypal.paymentspro.dodirectpayment.basicinfo") ?></h5>

        <div class="control-group">
            <?php echo Form::label("FIRSTNAME", __("paypal.paymentspro.dodirectpayment.FIRSTNAME")) ?>
            <?php echo Form::input("dodirectpayment[FIRSTNAME]", $dodirectpayment->data("FIRSTNAME"), array("id" => "FIRSTNAME", "class" => "span12")) ?>
        </div>
        <div class="control-group">
            <?php echo Form::label("LASTNAME", __("paypal.paymentspro.dodirectpayment.LASTNAME")) ?>
            <?php echo Form::input("dodirectpayment[LASTNAME]", $dodirectpayment->data("LASTNAME"), array("id" => "LASTNAME", "class" => "span12")) ?>
        </div>
        <div class="control-group">
            <?php echo Form::label("EMAIL", __("paypal.paymentspro.dodirectpayment.EMAIL")) ?>
            <div class="control-input input-prepend row-fluid">
                <div class="add-on">@</div>
                <?php echo Form::input("dodirectpayment[EMAIL]", $dodirectpayment->data("EMAIL"), array("id" => "EMAIL", "class" => "span11")) ?>
            </div>
        </div>

    </div>


    <div class="span8">        

        <h5><?php echo __("paypal.paymentspro.dodirectpayment.address") ?></h5>

        <div class="row-fluid">

            <div class="span6">

                <div class="control-group">
                    <?php echo Form::label("STREET", __("paypal.paymentspro.dodirectpayment.STREET")) ?>
                    <?php echo Form::input("dodirectpayment[STREET]", $dodirectpayment->data("STREET"), array("id" => "STREET", "class" => "span12")) ?>
                </div>

                <div class="control-group">
                    <?php echo Form::label("CITY", __("paypal.paymentspro.dodirectpayment.CITY")) ?>
                    <?php echo Form::input("dodirectpayment[CITY]", $dodirectpayment->data("CITY"), array("id" => "CITY", "class" => "span12")) ?>
                </div>

                <div class="control-group">
                    <?php echo Form::label("dodirectpayment[ZIP]", __("paypal.paymentspro.dodirectpayment.ZIP")) ?>
                    <?php echo Form::input("dodirectpayment[ZIP]", $dodirectpayment->data("ZIP"), array("id" => "ZIP", "class" => "span12")) ?>
                </div>

            </div>

            <div class="span6">

                <div class="control-group">
                    <?php echo Form::label("STREET2", __("paypal.paymentspro.dodirectpayment.STREET2")) ?>
                    <?php echo Form::input("dodirectpayment[STREET2]", $dodirectpayment->data("STREET2"), array("id" => "STREET2", "class" => "span12")) ?>
                </div>

                <div class="control-group">
                    <?php echo Form::label("dodirectpayment[STATE]", __("paypal.paymentspro.dodirectpayment.STATE"), array("id" => "STATE")) ?>
                    <?php echo Form::input("dodirectpayment[STATE]", $dodirectpayment->data("STATE"), array("class" => "span12")) ?>
                </div>

                <div class="control-group">
                    <?php echo Form::label("dodirectpayment[COUNTRYCODE]", __("paypal.paymentspro.dodirectpayment.COUNTRYCODE")) ?>
                    <?php echo Form::select("dodirectpayment[COUNTRYCODE]", array("CA" => "Canada", "US" => "Unites States of America"), $dodirectpayment->data("COUNTRYCODE"), array("id" => "COUNTRYCODE", "class" => "span12")) ?>
                </div>


            </div>
        </div>
    </div>
</div>

<div class="row-fluid">

    <div class="span8">

        <p><strong><?php echo __("paypal.paymentspro.dodirectpayment.required") ?></strong></p>

        <p><?php echo __("paypal.paymentspro.dodirectpayment.details", array(":name" => __("paypal.paymentspro.dodirectpayment.websitename"), ":link" => HTML::anchor("https://www.paypal.com/", __("paypal.paymentspro.dodirectpayment.modedetails")))) ?></p>

    </div>

    <div class="span4">
        <div class="control-group">
            <?php echo Form::label("SHIPTOPHONENUM", __("paypal.paymentspro.dodirectpayment.SHIPTOPHONENUM")) ?>
            <?php echo Form::input("dodirectpayment[SHIPTOPHONENUM]", $dodirectpayment->data("SHIPTOPHONENUM"), array("id" => "SHIPTOPHONENUM", "class" => "span12")) ?>
        </div>
    </div>


</div>

<h5><?php echo __("paypal.paymentspro.dodirectpayment.cardinfo") ?></h5>


<div class="row-fluid">

    <div class="span4">
        <?php
        $credit_types = array();

        foreach (PayPal_DoDirectPayment::$CREDIT_CARD_TYPES as $type) {
            $credit_types[$type] = $type . " &copy;";
        }
        ?>

        <div class="control-group">
            <?php echo Form::label("CREDITCARDTYPE", __("paypal.paymentspro.dodirectpayment.CREDITCARDTYPE")) ?>
            <?php echo Form::select("dodirectpayment[CREDITCARDTYPE]", $credit_types, $dodirectpayment->param("CREDITCARDTYPE"), array("id" => "CREDITCARDTYPE", "class" => "span12")) ?>
        </div>

    </div>

    <div class="span4">

        <div class="control-group">            
            <?php echo Form::label("ACCT", __("paypal.paymentspro.dodirectpayment.ACCT")) ?>
            <div class="control-input controls-row">
                <?php echo Form::input("dodirectpayment[ACCT]", $dodirectpayment->param("ACCT"), array("id" => "ACCT", "class" => "span9")) ?>
                <?php echo Form::input("dodirectpayment[CVV2]", $dodirectpayment->param("CVV2"), array("id" => "CVV2", "class" => "span3")) ?>
            </div>
        </div>
    </div>

    <div class="span4">
        <div class="control-group">
            <?php echo Form::label("EXPDATE", __("paypal.paymentspro.dodirectpayment.EXPDATE")) ?>
            <div class="controls-row dodirecypayment-expdate">

                <?php
                $this_year = (int) Date::formatted_time("now", "Y");

                $months = array();
                $years = array();

                foreach (range(0, 12)as $month) {
                    $months[str_pad($month, 2, '0', STR_PAD_LEFT)] = ucfirst(__("paypal.month." . PayPal::$MONTHS_OF_YEAR[$month]));
                }

                foreach (range($this_year, $this_year + 20) as $year) {
                    $years[$year] = $year;
                }
                ?>

                <?php echo Form::select('dodirectpayment[EXPMONTH]', $months, substr($dodirectpayment->data("EXPDATE"), 0, 2), array("class" => "span8", 'onchange' => 'DoDirectPayment.onDateChange(this)')) ?>
                <?php echo Form::select('dodirectpayment[EXPYEAR]', $years, substr($dodirectpayment->data("EXPDATE"), 2), array("class" => "span4", 'onchange' => 'DoDirectPayment.onDateChange(this)')) ?>
                <?php echo Form::hidden('dodirectpayment[EXPDATE]', $dodirectpayment->data("EXPDATE"), array("class" => "span12")) ?>
            </div>
        </div>
    </div>

</div>

<script src="//code.jquery.com/jquery-1.10.1.min.js" type="text/javascript"></script>

<script type="text/javascript">

    /**
     * 
     * @type type
     */
    var DoDirectPayment = {
        /**
         * 
         * @returns {undefined}
         */
        onDateChange: function() {
            var month = $("[name='dodirectpayment[EXPMONTH]']").val();
            var year = $("[name='dodirectpayment[EXPYEAR]']").val();
            $("[name='dodirectpayment[EXPDATE]']").val(month + year);
        }
    };
    DoDirectPayment.onDateChange();
</script>
