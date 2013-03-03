<?php defined('SYSPATH') or die('No direct script access.'); ?>

<div class="row-fluid">

    <div class="span4">

        <h5>Informations de base</h5>

        <div class="control-group">
            <?php echo Form::label("FIRSTNAME", __("paypal.paymentspro.dodirectpayment.FIRSTNAME")) ?>
            <?php echo Form::input("dodirectpayment[FIRSTNAME]", $dodirectpayment->param("FIRSTNAME"), array("ID" => "FIRSTNAME")) ?>
        </div>
        <div class="control-group">
            <?php echo Form::label("LASTNAME", __("paypal.paymentspro.dodirectpayment.LASTNAME")) ?>
            <?php echo Form::input("dodirectpayment[LASTNAME]", $dodirectpayment->param("LASTNAME"), array("id" => "LASTNAME")) ?>
        </div>
        <div class="control-group">
            <?php echo Form::label("EMAIL", __("paypal.paymentspro.dodirectpayment.EMAIL")) ?>
            <?php echo Form::input("dodirectpayment[EMAIL]", $dodirectpayment->param("EMAIL"), array("id" => "EMAIL")) ?>
        </div>
    </div>


    <div class="span8">        

        <h5>Adresse</h5>

        <div class="row-fluid">

            <div class="span6">
                <div class="control-group">
                    <?php echo Form::label("STREET", __("paypal.paymentspro.dodirectpayment.STREET")) ?>
                    <?php echo Form::input("dodirectpayment[STREET]", $dodirectpayment->param("STREET"), array("id" => "STREET")) ?>
                </div>

                <div class="control-group">
                    <?php echo Form::label("CITY", __("paypal.paymentspro.dodirectpayment.CITY")) ?>
                    <?php echo Form::input("dodirectpayment[CITY]", $dodirectpayment->param("CITY"), array("id" => "CITY")) ?>
                </div>

                <div class="control-group">
                    <?php echo Form::label("dodirectpayment[ZIP]", __("paypal.paymentspro.dodirectpayment.ZIP")) ?>
                    <?php echo Form::input("dodirectpayment[ZIP]", $dodirectpayment->param("ZIP"), array("id" => "ZIP")) ?>
                </div>

            </div>

            <div class="span6">

                <div class="control-group">
                    <?php echo Form::label("STREET2", __("paypal.paymentspro.dodirectpayment.STREET2")) ?>
                    <?php echo Form::input("dodirectpayment[STREET2]", $dodirectpayment->param("STREET2"), array("id" => "STREET2")) ?>
                </div>


                <div class="control-group">

                    <?php echo Form::label("dodirectpayment[STATE]", __("paypal.paymentspro.dodirectpayment.STATE"), array("id" => "STATE")) ?>
                    <?php echo Form::input("dodirectpayment[STATE]", $dodirectpayment->param("STATE")) ?>

   
                </div>

                <div class="control-group">
                 <?php echo Form::label("dodirectpayment[COUNTRYCODE]", __("paypal.paymentspro.dodirectpayment.COUNTRYCODE"), array("id" => "COUNTRYCODE")) ?>
                    <?php echo Form::select("dodirectpayment[COUNTRYCODE]", array("CA" => "Canada", "US" => "Unites States of America"), $dodirectpayment->param("COUNTRYCODE")) ?>
                </div>

                <div class="control-group">
                    <?php echo Form::label("SHIPTOPHONENUM", __("paypal.paymentspro.dodirectpayment.SHIPTOPHONENUM")) ?>
                    <?php echo Form::input("dodirectpayment[SHIPTOPHONENUM]", $dodirectpayment->param("SHIPTOPHONENUM"), array("id" => "SHIPTOPHONENUM")) ?>
                </div>
            </div>

        </div>



    </div>



</div>

<h5>Informations sur la carte</h5>


<div class="row-fluid">




    <div class="span4">
        <?php
        $credit_types = array();

        foreach (PayPal_PaymentsPro_DoDirectPayment::$CREDIT_CARD_TYPES as $type) {

            $credit_types[$type] = $type;
        }
        ?>

        <div class="control-group">
            <?php echo Form::label("CREDITCARDTYPE", __("paypal.paymentspro.dodirectpayment.CREDITCARDTYPE")) ?>
            <?php echo Form::select("dodirectpayment[CREDITCARDTYPE]", $credit_types, $dodirectpayment->param("CREDITCARDTYPE"), array("id" => "CREDITCARDTYPE")) ?>
        </div>

    </div>

    <div class="span4">

        <div class="control-group">
            <?php echo Form::label("ACCT", __("paypal.paymentspro.dodirectpayment.ACCT")) ?>
            <?php echo Form::input("dodirectpayment[ACCT]", $dodirectpayment->param("ACCT"), array("id" => "ACCT", "class" => "span9")) ?>
            <?php echo Form::input("dodirectpayment[CVV2]", $dodirectpayment->param("CVV2"), array("id" => "CVV2", "class" => "span3")) ?>
        </div>
    </div>

    <div class="span4">
        <div class="control-group">
            <?php echo Form::label("EXPDATE", __("paypal.paymentspro.dodirectpayment.EXPDATE")) ?>
            <?php echo Form::input("dodirectpayment[EXPDATE]", $dodirectpayment->param("EXPDATE"), array("id" => "EXPDATE")) ?>
        </div>
    </div>




</div>
