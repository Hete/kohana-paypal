<?php defined('SYSPATH') or die('No direct script access.'); ?>

<table class="table table-condensed table-bordered">

    <?php foreach ($getexpresscheckoutdetails['PAYMENTREQUEST'] as $payment_index => $payment): ?>

        <tr><th colspan="5"><?php echo $paymentrequest['DESC'] ?></th></tr>

        <tr>
            <th><?php echo __('paypal.getexpresscheckoutdetails.name') ?></th>
            <th><?php echo __('paypal.getexpresscheckoutdetails.description') ?></th>
            <th><?php echo __('paypal.getexpresscheckoutdetails.quantity') ?></th>
            <th><?php echo __('paypal.getexpresscheckoutdetails.item_amt') ?></th>
        </tr>

        <?php foreach ($getexpresscheckoutdetails['L']['PAYMENTREQUEST'][$payment_index] as $item_index => $item): ?>
            <tr>
                <td><?php echo $item["NAME$item_index"] ?></td>         
                <td><?php echo $item["DESC$item_index"] ?></td>
                <td><?php echo Num::format($item["AMT$item_index"], 2, TRUE) ?> <?php echo $item["CURRENCYCODE$item_index"] ?></td>
                <td><?php echo $item["QTY$item_index"] ?></td>
            </tr>
        <?php endforeach ?>

        <tr>
            <th class="text-right" colspan="4"><?php echo __('paypal.getexpresscheckoutdetails.subtotal_amt') ?></th>
            <td><?php echo Num::format($payment['ITEMAMT'], 2, TRUE) ?> <?php echo $payment['CURRENCYCODE'] ?></td>
        </tr>

        <?php if ($payment['HANDLINGAMT']): ?>
            <tr>
                <th class="text-right" colspan="4"><?php echo __('paypal.getexpresscheckoutdetails.handling_amt') ?></th>
                <td><?php echo Num::format($payment['HANDLINGAMT'], 2, TRUE) ?> <?php echo $payment['CURRENCYCODE'] ?></td>
            </tr>
        <?php endif; ?>

        <?php if ($payment['SHIPPINGAMT']): ?>
            <tr>
                <th class="text-right" colspan="4"><?php echo __('paypal.getexpresscheckoutdetails.shipping_amt') ?></th>
                <td><?php echo Num::format($payment['SHIPPINGAMT'], 2, TRUE) ?> <?php echo $payment['CURRENCYCODE'] ?></td>
            </tr>
        <?php endif; ?>

        <?php if ($payment['TAXAMT']): ?>
            <tr>
                <th class="text-right" colspan="4"><?php echo __('paypal.getexpresscheckoutdetails.tax_amt') ?></th>
                <td><?php echo Num::format($payment['TAXAMT'], 2, TRUE) ?> <?php echo $payment['CURRENCYCODE'] ?></td>
            </tr>
        <?php endif; ?>

        <tr>
            <th class="text-right" colspan="4"><?php echo __('paypal.getexpresscheckoutdetails.amt') ?></th>
            <td><?php echo Num::format($payment['AMT'], 2, TRUE) ?> <?php echo $payment['CURRENCYCODE'] ?></td>
        </tr>

        <tr>
            <td colspan="5">
                <div class="row-fluid">

                    <div class="span4">
                        <?php echo $payment['NAME'] ?> <span class="label label <?php echo $payment['PAYERSTATUS'] === 'verified' ? 'success' : 'warning' ?>"><?php echo $payment['PAYERSTATUS'] ?></span></br>
                        <?php echo $payment['EMAIL'] ?></br>
                    </div>
                    <div class="span4">
                        <h3><?php echo __('paypal.getexpresscheckoutdetails.billing') ?></h3>
                        <address>
                            <strong><?php echo $payment['NAME'] ?></strong><br/>
                            <?php echo $payment['STREET'] ?><br/>
                            <?php echo $payment['STREET2'] ?><br/>
                            <?php echo $payment['CITY'] ?>, <?php echo $payment['STATE'] ?><br/>
                            <?php echo $payment['COUNTRYCODE'] ?><br/>
                            <?php echo $payment['ZIP'] ?>
                        </address>
                    </div>

                    <div class="span4">
                        <h3><?php echo __('paypal.getexpresscheckoutdetails.shipping') ?></h3>
                        <address>
                            <strong><?php echo $payment['SHIPTONAME'] ?></strong><br/>
                            <?php echo $payment['SHIPTOSTREET'] ?><br/>
                            <?php echo $payment['SHIPTOSTREET'] ?><br/>
                            <?php echo $payment['SHIPTOCITY'] ?>, <?php echo $payment['SHIPTOSTATE'] ?><br/>
                            <?php echo $payment['SHIPTOCOUNTRYCODE'] ?><br/>
                            <?php echo $payment['SHIPTOZIP'] ?>
                        </address>
                    </div>
                </div>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
