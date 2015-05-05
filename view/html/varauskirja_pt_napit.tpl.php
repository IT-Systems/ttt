<?php
setlocale(LC_ALL, array('fi_FI.UTF-8','fi_FI@euro','fi_FI','finnish'));  

$suunnittelu_url = ($suunnittelutila) ? '?ID=56&amp;' : '?';


if (($_POST['nakyma']) || ($_GET['nakyma'])) {
    if (($_POST['nakyma'] == 'paiva') || ($_GET['nakyma'] == 'paiva')) {
        $paivanakyma = true;
    }
    else {
        $paivanakyma = false;
    }
}
else {
    $paivanakyma = false;
}


if ($_GET['w']) {
    $viikko = htmlentities($_GET['w'], ENT_COMPAT);
    $vuosi = ($_GET['y']) ? htmlentities($_GET['y'], ENT_COMPAT) : date('Y');
    $viikon_paiva = strtotime($vuosi . 'W' . $viikko . '1');
}
elseif ($_GET['d']) {
    $paiva = htmlentities($_GET['d'], ENT_COMPAT);
    $viikon_paiva = strtotime($paiva);
    $vuosi = date('Y', $viikon_paiva);
    $viikko = date('W', $viikon_paiva);
}
else {
    $viikon_paiva = strtotime(date('Y\WWN'));
    $vuosi = date('Y');
    $viikko = date('W');
}


$nakyma_action = ($_GET['d']) ? 'd=' . $paiva : 'y=' . $vuosi . '&amp;w=' . $viikko;

?>

<form id="nakyma-form" action="<?php echo $suunnittelu_url . $nakyma_action; ?>" method="POST">
<?php if ($paivanakyma) { ?>
    <input type="hidden" name="nakyma" value="viikko">
<?php } else { ?>
    <input type="hidden" name="nakyma" value="paiva">
<?php } ?>
</form>

<form id="suunnittelutila-form" action="<?php echo (!$suunnittelutila) ? '?ID=56&amp;' : '?'; echo $nakyma_action; ?>" method="POST">
    <?php if ($paivanakyma) { ?>
    <input type="hidden" name="nakyma" value="paiva">
<?php } else { ?>
    <input type="hidden" name="nakyma" value="viikko">
<?php } ?>
</form>

<div id="ylanapit" class="valikko alavalikko">
    <ul class="topnav">
        <li class="first-li"><a href="varauskirja.php?ID=73"> Paikallaoloilmoitus</a></li>
        <?php if ($APP->ID == 73) { ?>
        <li><a href="varauskirja.php">Takaisin varauskirjaan</a></li>
        <?php } else { ?>
        <li><a id="nakyma-btn"><?php echo ($paivanakyma) ? 'Viikkonäkymä' : 'Päivänäkymä'; ?></a></li>
        <li><a id="siirry-paivaan-btn">Siirry päivään</a></li>
        <li><a id="suunnittelutila-btn"><?php echo (!$suunnittelutila) ? 'Siirry suunnittelutilaan' : 'Siirry perustilaan'; ?></a></li>
        <?php } ?>
    </ul>
    </div>
<div id="siirry-paivaan-container">
        
        <form method="GET">
            <label for="siirry_paivaan">Päivä:</label>&nbsp;<input type="text" class="pvm" name="d" id="siirry_paivaan"> <button type="submit" class="nuoli">&rarr;</button>
            <input type="hidden" name="nakyma" value="<?php echo ($paivanakyma) ? 'paiva' : 'viikko'; ?>">
            <?php if ($suunnittelutila) { ?> <input type="hidden" name="ID" value="56"> <?php } ?>
        </form>
    </div>

<!-- https://github.com/perifer/timePicker -->
<script type="text/javascript" src="<?php print $APP->BASEURL;?>/view/js/jquery.timePicker.min.js"></script>
<script type="text/javascript" src="<?php print $APP->BASEURL;?>/view/js/jquery.ui.datepicker-fi.js"></script>
<script type="text/javascript" src="<?php print $APP->BASEURL;?>/view/js/jquery.tablehover.min.js"></script>
<script type="text/javascript" src="<?php print $APP->BASEURL;?>/view/js/its.tarkistaAjat.js"></script>
<script type="text/javascript">
    $(function() {
        $.datepicker.setDefaults($.datepicker.regional["fi"]);
        $(".klo").timePicker({
            startTime: "00.00", 
            endTime: "23.45", 
            separator: '.', // tämä pitää olla määriteltynä, jos on asetettu startTime tai endTime
            step: 15
        }); 
        $(".pvm").datepicker();
        $(".aika_wrapper .aika").change(function() { tarkistaAjat($(this), false); });
        $('#siirry-paivaan-btn').click(function() {
            $("#siirry-paivaan-container").toggle('blind', {}, 200);
        });    
        $('#nakyma-btn').click(function() {
            $('#nakyma-form').submit();
        });
        $('#suunnittelutila-btn').click(function() {
            $('#suunnittelutila-form').submit();
        });
        $('#varaukset').tableHover({colClass: 'tablehover', rowClass: 'tablehover', clickClass: 'tableclick', cellClass: 'cellhover'});
        $('.lisaa-varaus').fancybox({
            onClosed: function(f) {
                $('form').find('select, input[type!="hidden"], textarea').val('')
            }
        });
        $('#konevaraus-accordion').accordion({
            animated: false,
            collapsible: true,
            active: false,
            autoHeight: false,
            change: function (event, ui) {
                $.fancybox.center();
            }
        });
        $('.lisaa-varaus').click(function() {
            var paiva = $(this).attr('paiva');
            var aika = $(this).attr('aika');
            var rivi = $(this).attr('vaaditaan');
            $('.varaus-pvm').each(function() {
                $(this).val(paiva);
            });
            $('.varaus-aika').each(function() {
               $(this).val(aika); 
            });
            $('.vaaditaan').each(function() {
                $(this).val(rivi);
            })
        });
        
        $('.muokkaa-varausta').click(function() {
            var tyyppi = $(this).attr('varaus_tyyppi');
            var varausId = $(this).attr('varaus_id');
            $.post('varauskirja.php?ID=63', { varaus_id: varausId, tyyppi: tyyppi }, function(data){
                $.fancybox(data);
            });
        });
        
        $('.nayta-varaus').click(function() {
           var tyyppi = $(this).attr('varaus_tyyppi');
           var varausId = $(this).attr('varaus_id');
           $.post('varauskirja.php?ID=75', { varaus_id: varausId, tyyppi: tyyppi }, function(data) {
              $.fancybox(data); 
           });
        });

       $('form').on('change', '.aika-error', function(event) {
            $(this).removeClass('aika-error'); 
       });
       
       
    });
    function tallennusFancyboxHandler(code, element) {
        /**
         * Koodit ks. VarausException
         */
           switch (code) {
                case '302':
                   // joku aika puuttuu
                   element.find('.aika').addClass('aika-error');
                break;
                case '301':
                   // vaadittu kenttä puuttuu
                   element.find('.vaaditaan').addClass('aika-error');
                break;
                case '203':
                case '202':
                // tietokantavirhe
                element.append($('<div class="ui-state-error ui-corner-all" style="padding: 1em; margin: 1em 0;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: 2em"></span>Tietokantavirhe ('+code+'). Yritä myöhemmin uudelleen.</div>'));
                break;
                case '201':
                    // päällekkäinen aika
                    element.find('.aika').addClass('aika-error');
                    element.find('.vaaditaan').addClass('aika-error');
                    element.append($('<div class="ui-state-error ui-corner-all" style="padding: 1em; margin: 1em 0;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: 2em"></span> Ajalle on jo päällekkäinen samantyyppinen varaus!</div>'));
                break;
                case '777':
                    // HELT OKEJ, ei varsinainen virhekoodi
                    $.fancybox.close();
                    window.location = window.location.href.replace('#!','') + '&nakyma=<?php echo ($paivanakyma) ? 'paiva' : 'viikko'; ?>';
                break;
           }
       }
</script>
<link rel="stylesheet" type="text/css" href="<?php print $APP->BASEURL;?>/view/css/timePicker.css" />

