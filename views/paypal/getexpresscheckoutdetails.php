
<table class="table table-condensed table-bordered">

    <tr>
        <th>Nom</th>
        <th>Description</th>
        <th>Prix unitaire</th>
        <th>Quantité</th>
        <th>Total</th>
    </tr>

    <?php $payment_index = 0 ?>

    <?php while ($payment = Arr::get($getexpresscheckoutdetails, "PAYMENTREQUEST_" . $payment_index . "_AMT")): ?>

        <?php $payment_details = PayPal_PaymentsPro_GetExpressCheckoutDetails::paymentrequest($getexpresscheckoutdetails, $payment_index) ?>    

        <tr>
            <th colspan="5"><?php echo Arr::get($payment_details, "DESC", "Détails du paiement") ?></th>
        </tr>

        <?php $product_index = 0 ?>

        <?php while ($product = Arr::get($getexpresscheckoutdetails, "L_PAYMENTREQUEST_" . $payment_index . "_AMT$product_index")): ?>

            <?php $item_details = PayPal_PaymentsPro_GetExpressCheckoutDetails::item($getexpresscheckoutdetails, $payment_index, $product_index) ?>    

            <tr>
                <td><?php echo Arr::get($item_details, "NAME") ?></td>         
                <td><?php echo Arr::get($item_details, "DESC") ?></td>
                <td><?php echo Num::format(Arr::get($item_details, "AMT"), 2, TRUE) ?> <?php echo Arr::get($payment_details, "CURRENCYCODE") ?></td>
                <td><?php echo Arr::get($item_details, "QTY") ?></td>
                <td><?php echo Arr::get($item_details, "QTY") * Arr::get($item_details, "AMT") ?> <?php echo Arr::get($payment_details, "CURRENCYCODE") ?></td>

            </tr>

            <?php $product_index++ ?>

        <?php endwhile; ?>

        <?php $payment_index++ ?>          

        <tr class="text-right">
            <th colspan="4">Sous-total</th>
            <td><?php echo Num::format(Arr::get($payment_details, "ITEMAMT"), 2, TRUE) ?> <?php echo Arr::get($payment_details, "CURRENCYCODE") ?></td>
        </tr>

        <tr class="text-right">
            <th colspan="4">Total des taxes</th>
            <td><?php echo Num::format(Arr::get($payment_details, "TAXAMT"), 2, TRUE) ?> <?php echo Arr::get($payment_details, "CURRENCYCODE") ?></td>
        </tr>

        <tr class="text-right">
            <th colspan="4">Total</th>
            <td><?php echo Num::format(Arr::get($payment_details, "AMT"), 2, TRUE) ?> <?php echo Arr::get($payment_details, "CURRENCYCODE") ?></td>
        </tr>

    <?php endwhile; ?>
</table>
