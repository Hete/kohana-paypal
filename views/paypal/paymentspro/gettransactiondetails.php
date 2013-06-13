
<table class='table table-condensed table-bordered'>

    <tr>
        <th>Nom</th>
        <th>Description</th>
        <th>Prix unitaire</th>
        <th>Quantité</th>
        <th>Total</th>
    </tr>

    <?php foreach (PayPal_PaymentsPro_GetExpressCheckoutDetails::payment_requests($getexpresscheckoutdetails) as $payment_index => $payment_details): ?>

        <tr>
            <th colspan='5'><?php echo Arr::get($payment_details, 'DESC', 'Détails du paiement') ?></th>
        </tr>

        <?php foreach (PayPal_PaymentsPro_GetExpressCheckoutDetails::items($getexpresscheckoutdetails, $payment_index) as $item_details): ?>

            <tr>
                <td><?php echo Arr::get($item_details, 'NAME') ?></td>         
                <td><?php echo Arr::get($item_details, 'DESC') ?></td>
                <td><?php echo Num::format(Arr::get($item_details, 'AMT'), 2, TRUE) ?> <?php echo Arr::get($payment_details, 'CURRENCYCODE') ?></td>
                <td><?php echo Arr::get($item_details, 'QTY') ?></td>
                <td><?php echo Arr::get($item_details, 'QTY') * Arr::get($item_details, 'AMT') ?> <?php echo Arr::get($payment_details, 'CURRENCYCODE') ?></td>

            </tr>


        <?php endforeach; ?>

        <tr class='text-right'>
            <th colspan='4'>Sous-total</th>
            <td><?php echo Num::format(Arr::get($payment_details, 'ITEMAMT'), 2, TRUE) ?> <?php echo Arr::get($payment_details, 'CURRENCYCODE') ?></td>
        </tr>

        <?php if (Arr::get($payment_details, 'SHIPPINGAMT')): ?>
            <tr class='text-right'>
                <th colspan='4'>Frais de livraison</th>
                <td><?php echo Num::format(Arr::get($payment_details, 'SHIPPINGAMT'), 2, TRUE) ?> <?php echo Arr::get($payment_details, 'CURRENCYCODE') ?></td>
            </tr>
        <?php endif; ?>

        <?php if (Arr::get($payment_details, 'HANDLINGAMT')): ?>
            <tr class='text-right'>
                <th colspan='4'>Frais de manutention</th>
                <td><?php echo Num::format(Arr::get($payment_details, 'HANDLINGAMT'), 2, TRUE) ?> <?php echo Arr::get($payment_details, 'CURRENCYCODE') ?></td>
            </tr>
        <?php endif; ?>

        <?php if (Arr::get($payment_details, 'TAXAMT')): ?>
            <tr class='text-right'>
                <th colspan='4'>Total des taxes</th>
                <td><?php echo Num::format(Arr::get($payment_details, 'TAXAMT'), 2, TRUE) ?> <?php echo Arr::get($payment_details, 'CURRENCYCODE') ?></td>
            </tr>
        <?php endif; ?>

        <tr class='text-right'>
            <th colspan='4'>Total</th>
            <td><?php echo Num::format(Arr::get($payment_details, 'AMT'), 2, TRUE) ?> <?php echo Arr::get($payment_details, 'CURRENCYCODE') ?></td>
        </tr>

    <?php endforeach; ?>
</table>
