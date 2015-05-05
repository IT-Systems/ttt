<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php print $APP->BASEURL;?>/view/css/index.php" rel="stylesheet" type="text/css" />
        <style type="text/css">
            body {
                font-family: Arial,Helvetica,sans-serif;
                width: 100%;
                height: 100%;
                margin: 0% 0% 0% 0%;
                filter: progid:DXImageTransform.Microsoft.BasicImage(Rotation=3);
            }
            h3 {
                font-size: 14px;
            }
            table {
                border-top:1px black solid;
                border-left:1px black solid;
            }
            th {
               font-size: 12px;
            }
            td, th {
                margin: 0 !important;
                padding: 3px;
                vertical-align: top;
                text-align: left;
                border-bottom:1px black solid;
                border-right:1px black solid;
            }
            td {
                font-size: 11px;
            }
            @media print {
                .noPrint {
                    display:none;
                }
            }
        </style>

    </head>
    <body>
        <a href="javascript:void(0);" onclick="javascript:history.go(-1);" style="float:left;margin:0px 4px 4px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all noprint"><span class="ui-icon ui-icon-arrowthick-1-e"></span>Palaa</a>
        <a href="javascript:void(0);" onclick="window.print();"style="float:left;margin:0px 0px 4px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all noprint"><span class="ui-icon ui-icon-plus"></span>Tulosta</a>

        <div style="clear:both;"/>
<?php
$ak = new aikakalu();
$pvm = $ak->dbDateToFiDate($_GET["date"]);
?>
        <h3><?php print $pvm; ?></h3>
        <div>
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <th>Päivä</th>
                    <th>Ilma-alus</th>
                    <th>PIC</th>
                    <th>COP</th>
                    <th>Muu</th>
                    <th>Matkustajia</th>
                    <th>Lähtöpaikka</th>
                    <th>Laskupaikka</th>
                    <th>Offblock</th>
                    <th>Lähtöaika</th>
                    <th>Laskuaika</th>
                    <th>Onblock</th>
                    <th>Ilma-aika</th>
                    <th>Block-aika</th>
                    <th>Laskuja</th>
                    <th>Päivällä</th>
                    <th>Yöllä</th>
                    <th>Laatu</th>
                    <th>Simulaattori</th>
                    <th>Huomautus</th>
                </tr>
<?php
if (sizeof($lennot) > 0) {
$ak = new aikakalu();
foreach($lennot as $lento) {
    $kone = $len->haeIlmaAlus($lento["kone_id"]);
    if (!$kone) $kone["nimi"] = "Poistunut";
    $laatu = $len->haeLennonToiminnanLaadut($lento["toim_tyyppi_id"]);
    if (!$laatu) $laatu["nimi"] = "Poistunut";

    $paallikko = $len->haeHenkilot($lento["paallikko_user_id"]);
    if (!$paallikko) {
        $paallikko["nimi"] = "Poistunut";
    }
    else {
        $paallikko["nimi"] = $paallikko["firstname"] . " " . $paallikko["lastname"];
    }

    $peramies = $len->haeHenkilot($lento["peramies_user_id"]);
    if (!$peramies) {
        $peramies["nimi"] = "Poistunut";
    }
    else {
        $peramies["nimi"] = $peramies["firstname"] . " " . $peramies["lastname"];
    }

    $muujasen = $len->haeHenkilot($lento["muu_jasen_user_id"]);
    if (!$muujasen) {
        $muujasen["nimi"] = "Poistunut";
    }
    else {
        $muujasen["nimi"] = $muujasen["firstname"] . " " . $muujasen["lastname"];
    } ?>
                <tr>
                    <td><?php print $ak->dbDateToFiDate($lento["alkamispaiva"]); ?></td>
                    <td><?php print $kone["nimi"]; ?></td>
                    <td><?php print $paallikko["nimi"]; ?></td>
                    <td><?php print $peramies["nimi"]; ?></td>
                    <td><?php print $muujasen["nimi"]; ?></td>
                    <td><?php print $lento["matkustajia"]; ?></td>
                    <td><?php print $lento["lahtopaikka"]; ?></td>
                    <td><?php print $lento["maarapaikka"]; ?></td>
                    <td><?php print substr($lento["off_block_aika"],0,-3); ?></td>
                    <td><?php print substr($lento["lahtoaika"],0,-3); ?></td>
                    <td><?php print substr($lento["saapumisaika"],0,-3); ?></td>
                    <td><?php print substr($lento["on_block_aika"],0,-3); ?></td>
                    <td><?php print substr($lento["ilma_aika"],0,-3); ?></td>
                    <td><?php print substr($lento["lentoaika"],0,-3); ?></td>
                    <td><?php print $lento["laskeutumisia_paiva"] + $lento["laskeutumisia_yo"]; ?></td>
                    <td><?php print $lento["laskeutumisia_paiva"]; ?></td>
                    <td><?php print $lento["laskeutumisia_yo"]; ?></td>
                    <td><?php print $laatu["nimi"]; ?></td>
                    <td></td>
                    <td><?php print $lento["huomautukset"]; ?></td>
                </tr>
<?php
                            }
                        } ?>
            </table>
        </div>
    </body>
</html>