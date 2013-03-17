<?php defined('SYSPATH') or die('No direct script access.'); ?>

<div class="row-fluid">

    <div class="span4">

        <h5><?php echo __("paypal.paymentspro.dodirectpayment.basicinfo") ?></h5>

        <div class="control-group">
            <?php echo Form::label("FIRSTNAME", __("paypal.paymentspro.dodirectpayment.FIRSTNAME")) ?>
            <?php echo Form::input("dodirectpayment[FIRSTNAME]", $dodirectpayment->param("FIRSTNAME"), array("id" => "FIRSTNAME", "class" => "span12")) ?>
        </div>
        <div class="control-group">
            <?php echo Form::label("LASTNAME", __("paypal.paymentspro.dodirectpayment.LASTNAME")) ?>
            <?php echo Form::input("dodirectpayment[LASTNAME]", $dodirectpayment->param("LASTNAME"), array("id" => "LASTNAME", "class" => "span12")) ?>
        </div>
        <div class="control-group">
            <?php echo Form::label("EMAIL", __("paypal.paymentspro.dodirectpayment.EMAIL")) ?>
            <div class="control-input input-prepend row-fluid">
                <div class="add-on">@</div>
                <?php echo Form::input("dodirectpayment[EMAIL]", $dodirectpayment->param("EMAIL"), array("id" => "EMAIL", "class" => "span11")) ?>
            </div>
        </div>


    </div>


    <div class="span8">        

        <h5><?php echo __("paypal.paymentspro.dodirectpayment.address") ?></h5>

        <div class="row-fluid">

            <div class="span6">

                <div class="control-group">
                    <?php echo Form::label("STREET", __("paypal.paymentspro.dodirectpayment.STREET")) ?>
                    <?php echo Form::input("dodirectpayment[STREET]", $dodirectpayment->param("STREET"), array("id" => "STREET", "class" => "span12")) ?>
                </div>

                <div class="control-group">
                    <?php echo Form::label("CITY", __("paypal.paymentspro.dodirectpayment.CITY")) ?>
                    <?php echo Form::input("dodirectpayment[CITY]", $dodirectpayment->param("CITY"), array("id" => "CITY", "class" => "span12")) ?>
                </div>

                <div class="control-group">
                    <?php echo Form::label("dodirectpayment[ZIP]", __("paypal.paymentspro.dodirectpayment.ZIP")) ?>
                    <?php echo Form::input("dodirectpayment[ZIP]", $dodirectpayment->param("ZIP"), array("id" => "ZIP", "class" => "span12")) ?>
                </div>

            </div>

            <div class="span6">

                <div class="control-group">
                    <?php echo Form::label("STREET2", __("paypal.paymentspro.dodirectpayment.STREET2")) ?>
                    <?php echo Form::input("dodirectpayment[STREET2]", $dodirectpayment->param("STREET2"), array("id" => "STREET2", "class" => "span12")) ?>
                </div>

                <div class="control-group">
                    <?php echo Form::label("dodirectpayment[STATE]", __("paypal.paymentspro.dodirectpayment.STATE"), array("id" => "STATE")) ?>
                    <?php echo Form::input("dodirectpayment[STATE]", $dodirectpayment->param("STATE"), array("class" => "span12")) ?>
                </div>

                <div class="control-group">
                    <?php echo Form::label("dodirectpayment[COUNTRYCODE]", __("paypal.paymentspro.dodirectpayment.COUNTRYCODE")) ?>
                    <?php echo Form::select("dodirectpayment[COUNTRYCODE]", array("CA" => "Canada", "US" => "Unites States of America"), $dodirectpayment->param("COUNTRYCODE"), array("id" => "COUNTRYCODE", "class" => "span12")) ?>
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
            <?php echo Form::input("dodirectpayment[SHIPTOPHONENUM]", $dodirectpayment->param("SHIPTOPHONENUM"), array("id" => "SHIPTOPHONENUM", "class" => "span12")) ?>
        </div>
    </div>


</div>

<h5><?php echo __("paypal.paymentspro.dodirectpayment.cardinfo") ?></h5>


<div class="row-fluid">

    <div class="span4">
        <?php
        $credit_types = array();

        foreach (PayPal_PaymentsPro_DoDirectPayment::$CREDIT_CARD_TYPES as $type) {
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

                foreach (range(0, 11) as $month) {
                    $months[$month] = ucfirst(__("paypal.month." . Request_PayPal::$MONTHS_OF_YEAR[$month + 1]));
                }

                foreach (range($this_year, $this_year + 20) as $year) {
                    $years[$year] = $year;
                }
                ?>

                <?php echo Form::select("", $months, substr($dodirectpayment->param("EXPDATE"), 0, 2), array("class" => "dodirecypayment-month span8")) ?>
                <?php echo Form::select("", $years, substr($dodirectpayment->param("EXPDATE"), 2), array("class" => "dodirecypayment-year span4")) ?>
                <?php echo Form::hidden("dodirectpayment[EXPDATE]", $dodirectpayment->param("EXPDATE"), array("id" => "EXPDATE", "class" => "span12")) ?>
            </div>
        </div>
    </div>

</div>

<script type="text/javascript">
    var DoDirectPayment = {
        onDateChange: function() {
            var date = new Date();
            date.setMonth($(".dodirecypayment-expdate").children("select.dodirecypayment-month").first().val());
            date.setYear($(".dodirecypayment-expdate").children("select.dodirecypayment-year").first().val());
            $(".dodirecypayment-expdate").children("input[name='dodirectpayment[EXPDATE]']").val(date.toString("MMyyyy"));
        }
    };
    $(".dodirecypayment-expdate select").change(DoDirectPayment.onDateChange);
    DoDirectPayment.onDateChange();
</script>
