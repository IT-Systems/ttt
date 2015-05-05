
<div class="kp_div">
    <div id="varaukset-container">
        <?php
        if (!$paivanakyma) {
            include_once('varauskirja_pt_viikkonakyma.tpl.php');
        } else {
            include_once('varauskirja_pt_paivanakyma.tpl.php');
        }
        ?>
    </div>
    <?php include('varauskirja_paikallaoloilmoitukset_taulukko.tpl.php'); ?>
</div>
<?php //$lento = new lentoVaraus();  ?>

<div class="hidden">
    <div id="uusi-konevaraus">
        <div id="konevaraus-accordion">
            <h3><a href="#">Uusi lentovaraus</a></h3>
            <div id="uusi-lentovaraus">
                <?php include_once('varauskirja_uusi_lentovaraus.tpl.php'); ?>
            </div>
            <h3><a href="#">Uusi huoltovaraus</a></h3>
            <div id="uusi-huoltovaraus">
                <?php include_once('varauskirja_uusi_huoltovaraus.tpl.php'); ?>
            </div>
        </div>
    </div>
</div>
<div class="hidden">
    <div id="uusi-tilavaraus">
        <?php include_once('varauskirja_uusi_tilavaraus.tpl.php'); ?>
    </div>
</div>

<div class="hidden">
    <div id="muokkaa-varausta-form">

    </div>
</div>
