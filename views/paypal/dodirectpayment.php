<?php defined('SYSPATH') or die('No direct script access.'); ?>

<div class="row-fluid">

    <div class="span4">

        <h4>Informations de base</h4>

        <div class="control-group">
            <?php echo Form::label("FIRSTNAME", "Prénom") ?>
            <?php echo Form::input("dodirectpayment[FIRSTNAME]") ?>
        </div>
        <div class="control-group">
            <?php echo Form::label("LASTNAME", "Nom") ?>
            <?php echo Form::input("dodirectpayment[LASTNAME]") ?>
        </div>
        <div class="control-group">
            <?php echo Form::label("EMAIL", "Courriel") ?>
            <?php echo Form::input("dodirectpayment[EMAIL]") ?>
        </div>
    </div>

    <div class="span4">

        <h4>Informations sur la carte</h4>

        <?php
        $credit_types = array();

        foreach (PayPal_PaymentsPro_DoDirectPayment::$CREDIT_CARD_TYPES as $type) {

            $credit_types[$type] = $type;
        }
        ?>

        <div class="control-group">
            <?php echo Form::label("CREDITCARDTYPE", "Type de carte") ?>
            <?php echo Form::select("dodirectpayment[CREDITCARDTYPE]", $credit_types, NULL) ?>
        </div>

        <div class="control-group">
            <?php echo Form::label("ACCT", "Numéro de la carte et code de vérification") ?>
            <?php echo Form::input("dodirectpayment[ACCT]", NULL, array("class" => "span9")) ?>
            <?php echo Form::input("dodirectpayment[CVV2]", NULL, array("class" => "span3")) ?>
        </div>

        <div class="control-group">
            <?php echo Form::label("EXPDATE", "Expiration") ?>
            <?php echo Form::input("dodirectpayment[EXPDATE]", NULL) ?>
        </div>
    </div>

    <div class="span4">

        <h4>Adresse</h4>

        <div class="control-group">
            <?php echo Form::label("STREET", "Adresse") ?>
            <?php echo Form::input("dodirectpayment[STREET]", NULL) ?>
        </div>

        <div class="control-group">
            <?php echo Form::label("STREET2", "Suite de l'adresse") ?>
            <?php echo Form::input("dodirectpayment[STREET]", NULL) ?>
        </div>

        <div class="control-group">
            <?php echo Form::label("CITY", "Ville") ?>
            <?php echo Form::input("dodirectpayment[STREET]", NULL) ?>
        </div>

        <div class="control-group">
            <?php echo Form::label("dodirectpayment[STATE]", "Province") ?>
            <?php echo Form::input("dodirectpayment[STREET]", NULL) ?>
        </div>

        <div class="control-group">
            <?php echo Form::label("dodirectpayment[ZIP]", "Code postal") ?>
            <?php echo Form::input("dodirectpayment[ZIP]", NULL) ?>
        </div>

        <div class="control-group">
            <?php echo Form::label("SHIPTOPHONENUM", "Numéro de téléphone pour la livraison") ?>
            <?php echo Form::input("dodirectpayment[SHIPTOPHONENUM]") ?>
        </div>
    </div>

</div>
