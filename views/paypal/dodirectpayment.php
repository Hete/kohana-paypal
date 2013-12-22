<?php defined('SYSPATH') or die('No direct script access.'); ?>

<div class="row-fluid">

    <div class="span4">

        <h5><?php echo __('paypal.dodirectpayment.basicinfo') ?></h5>

        <div class="control-group">
            <?php echo Form::label('FIRSTNAME', __('paypal.dodirectpayment.FIRSTNAME')) ?>
            <?php echo Form::input('dodirectpayment[FIRSTNAME]', $dodirectpayment['FIRSTNAME'), array('id' => 'FIRSTNAME', 'class' => 'span12')) ?>
        </div>
        <div class="control-group">
            <?php echo Form::label('LASTNAME', __('paypal.dodirectpayment.LASTNAME')) ?>
            <?php echo Form::input('dodirectpayment[LASTNAME]', $dodirectpayment['LASTNAME'), array('id' => 'LASTNAME', 'class' => 'span12')) ?>
        </div>
        <div class="control-group">
            <?php echo Form::label('EMAIL', __('paypal.dodirectpayment.EMAIL')) ?>
            <div class="control-input input-prepend row-fluid">
                <div class="add-on">@</div>
                <?php echo Form::input('dodirectpayment[EMAIL]', $dodirectpayment['EMAIL'), array('id' => 'EMAIL', 'class' => 'span11')) ?>
            </div>
        </div>
    </div>

    <div class="span8">        

        <h5><?php echo __('paypal.dodirectpayment.address') ?></h5>

        <div class="row-fluid">

            <div class="span6">

                <div class="control-group">
                    <?php echo Form::label('STREET', __('paypal.dodirectpayment.STREET')) ?>
                    <?php echo Form::input('dodirectpayment[STREET]', $dodirectpayment['STREET'), array('id' => 'STREET', 'class' => 'span12')) ?>
                </div>

                <div class="control-group">
                    <?php echo Form::label('CITY', __('paypal.dodirectpayment.CITY')) ?>
                    <?php echo Form::input('dodirectpayment[CITY]', $dodirectpayment['CITY'), array('id' => 'CITY', 'class' => 'span12')) ?>
                </div>

                <div class="control-group">
                    <?php echo Form::label('dodirectpayment[ZIP]', __('paypal.dodirectpayment.ZIP')) ?>
                    <?php echo Form::input('dodirectpayment[ZIP]', $dodirectpayment['ZIP'), array('id' => 'ZIP', 'class' => 'span12')) ?>
                </div>

            </div>

            <div class="span6">

                <div class="control-group">
                    <?php echo Form::label('STREET2', __('paypal.dodirectpayment.STREET2')) ?>
                    <?php echo Form::input('dodirectpayment[STREET2]', $dodirectpayment['STREET2'), array('id' => 'STREET2', 'class' => 'span12')) ?>
                </div>

                <div class="control-group">
                    <?php echo Form::label('dodirectpayment[STATE]', __('paypal.dodirectpayment.STATE'), array('id' => 'STATE')) ?>
                    <?php echo Form::input('dodirectpayment[STATE]', $dodirectpayment['STATE'), array('class' => 'span12')) ?>
                </div>

                <div class="control-group">
                    <?php echo Form::label('dodirectpayment[COUNTRYCODE]', __('paypal.dodirectpayment.COUNTRYCODE')) ?>
                    <?php echo Form::select('dodirectpayment[COUNTRYCODE]', array('CA' => 'Canada', 'US' => 'Unites States of America'), $dodirectpayment['COUNTRYCODE'), array('id' => 'COUNTRYCODE', 'class' => 'span12')) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="span8">

        <p><strong><?php echo __('paypal.dodirectpayment.required') ?></strong></p>

        <p><?php echo __('paypal.dodirectpayment.details', array(':name' => __('paypal.dodirectpayment.websitename'), ':link' => HTML::anchor('https://www.paypal.com/', __('paypal.dodirectpayment.modedetails')))) ?></p>

    </div>

    <div class="span4">
        <div class="control-group">
            <?php echo Form::label('SHIPTOPHONENUM', __('paypal.dodirectpayment.SHIPTOPHONENUM')) ?>
            <?php echo Form::input('dodirectpayment[SHIPTOPHONENUM]', $dodirectpayment['SHIPTOPHONENUM'), array('id' => 'SHIPTOPHONENUM', 'class' => 'span12')) ?>
        </div>
    </div>


</div>

<h5><?php echo __('paypal.dodirectpayment.cardinfo') ?></h5>

<div class="row-fluid">

    <div class="span4">
        <?php
        $credit_types = array();

        foreach (PayPal_DoDirectPayment::$CREDIT_CARD_TYPES as $type) {
            $credit_types[$type] = $type . ' &copy;';
        }
        ?>

        <div class="control-group">
            <?php echo Form::label('CREDITCARDTYPE', __('paypal.dodirectpayment.CREDITCARDTYPE')) ?>
            <?php echo Form::select('dodirectpayment[CREDITCARDTYPE]', $credit_types, $dodirectpayment->param('CREDITCARDTYPE'), array('id' => 'CREDITCARDTYPE', 'class' => 'span12')) ?>
        </div>

    </div>

    <div class="span4">

        <div class="control-group">            
            <?php echo Form::label('ACCT', __('paypal.dodirectpayment.ACCT')) ?>
            <div class="control-input controls-row">
                <?php echo Form::input('dodirectpayment[ACCT]', $dodirectpayment['ACCT'], array('id' => 'ACCT', 'class' => 'span9')) ?>
                <?php echo Form::input('dodirectpayment[CVV2]', $dodirectpayment['CVV2'], array('id' => 'CVV2', 'class' => 'span3')) ?>
            </div>
        </div>
    </div>

    <div class="span4">
        <div class="control-group">
            <?php echo Form::label('EXPDATE', __('paypal.dodirectpayment.EXPDATE')) ?>
            <div class="controls-row dodirecypayment-expdate">

                <?php
                $this_year = (int) Date::formatted_time('now', 'Y');

                $months = array();
                $years = array();

                foreach (range(0, 12)as $month) {
                    $months[str_pad($month, 2, '0', STR_PAD_LEFT)] = ucfirst(__('paypal.month.' . PayPal::$MONTHS_OF_YEAR[$month]));
                }

                foreach (range($this_year, $this_year + 20) as $year) {
                    $years[$year] = $year;
                }
                ?>

                <?php echo Form::select('dodirectpayment[EXPMONTH]', $months, substr($dodirectpayment['EXPDATE'), 0, 2), array('class' => 'span8', 'onchange' => 'DoDirectPayment.onDateChange(this)')) ?>
                <?php echo Form::select('dodirectpayment[EXPYEAR]', $years, substr($dodirectpayment['EXPDATE'), 2), array('class' => 'span4', 'onchange' => 'DoDirectPayment.onDateChange(this)')) ?>
                <?php echo Form::hidden('dodirectpayment[EXPDATE]', $dodirectpayment['EXPDATE'), array('class' => 'span12')) ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    /**
     * 
     * @type type
     *
     * @package   PayPal
     * @author    Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
     * @copyright (c) 2013, Hète.ca Inc.
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
</script>
