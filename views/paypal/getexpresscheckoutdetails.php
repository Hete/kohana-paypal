<?php defined('SYSPATH') or die('No direct script access.'); ?>

<table class="table table-condensed table-bordered">

    <?php foreach($getexpresscheckoutdetails['PAYMENTREQUEST'] as $payment_index => $payment): ?>

        <tr><th colspan="5"><?php echo $paymentrequest['DESC'] ?></th></tr>

        <tr>
            <th>Nom</th>
            <th>Description</th>
            <th>Quantité</th>
            <th>Prix unitaire</th>
            <th>Sous-total</th>
        </tr>

        <?php $item_index = 0 ?>

        <?php foreach($getexpresscheckoutdetails['L_PAYMENTREQUEST'][$payment_index] as $item): ?>
            <?php if($item === "NAME$item_index"): ?>
                <tr>
                    <td><?php echo $item["NAME$item_index"] ?></td>         
                    <td><?php echo $item["DESC$item_index"] ?></td>
                    <td><?php echo Num::format($item["AMT$item_index"], 2, TRUE) ?> <?php echo $item["CURRENCYCODE$item_index"] ?></td>
                    <td><?php echo $item["QTY$item_index") ?></td>
                    <td><?php echo $item["QTY$item_index") * $item["AMT$item_index"] ?> <?php echo $item["CURRENCYCODE$item_index"] ?></td>
                </tr>
            <?php endif; ?>
            <?php $item_index++ ?>
        <?php endforeach ?>

        <tr>
            <th class="text-right" colspan="4">Sous-total</th>
            <td><?php echo Num::format($payment['ITEMAMT'], 2, TRUE) ?> <?php echo $payment['CURRENCYCODE'] ?></td>
        </tr>

        <?php if($payment['HANDLINGAMT']): ?>
            <tr>
                <th class="text-right" colspan="4">Frais de manutention</th>
                <td><?php echo Num::format($payment['HANDLINGAMT'], 2, TRUE) ?> <?php echo $payment['CURRENCYCODE'] ?></td>
            </tr>
        <?php endif; ?>

        <?php if($payment['SHIPPINGAMT']): ?>
            <tr>
                <th class="text-right" colspan="4">Frais de livraison</th>
                <td><?php echo Num::format($payment['SHIPPINGAMT'], 2, TRUE) ?> <?php echo $payment['CURRENCYCODE'] ?></td>
            </tr>
        <?php endif; ?>

        <?php if($payment['TAXAMT']): ?>
            <tr>
                <th class="text-right" colspan="4">Taxes</th>
                <td><?php echo Num::format($payment['TAXAMT'], 2, TRUE) ?> <?php echo $payment['CURRENCYCODE'] ?></td>
            </tr>
        <?php endif; ?>

        <tr>
            <th class="text-right" colspan="4">Total</th>
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
                        <h3>Facturation</h3>
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
                        <h3>Livraison</h3>
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
