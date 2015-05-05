<div class="<?php print $aMsg[1]; ?>"> 
	<span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
	<?php print $aMsg[0]?>
</div>

<table width="100%" cellspacing="0" cellpadding="0" class="tableData">
<thead>
 <tr>
	<th>#</th>
	<th>Asiakas</th>
	<th>Summa</th>
	<th>Maksettu</th>
	<th>Viitenro</th>
	<th>Tila</th>
	<th>Luotu</th>
	<th>Laskutettu</th>
	<th>Eräpäivä</th>
	<th>Karhuttu</th>
	<th>Ilmoitettu</th>
	<th>Toiminnot</th>
 </tr>
</thead>
<tbody>
<?php 
if(count($karhut) > 0) {

    $start = 0;
    $end = 0;
    
    if ($_GET['page'] != 'all')
    {
        $perpage = 15;
        $curpage = (!isset($_GET['page'])) ? 1 : $_GET['page'];
        $productPages = $pagination->generate($karhut, $perpage);
        $start = $curpage * $perpage - ($perpage - 1);
        $end = $curpage * $perpage;
        $i = 1;
    }
    
	$summa = 0;
	$summaalv = 0;
	$kpl = 0;

	$asetukset = $tilaukset->hae_asetukset();
	
    foreach($karhut as $aRow)
	{
	    if (($i >= $start && $i <= $end) || $_GET['page'] == 'all')
	    {


	        $pvt_k = explode ("-", $aRow[karhuaika]);
            $vrtaika = mktime (0, 0, 0, $pvt_k[1], $pvt_k[2]+$asetukset[0]['huomautusaika']+$asetukset[0]['perintailmoitusaika'], $pvt_k[0]); // Eräpäivä + huomaika karhun tulostukseen.

	        $pvt_p = explode ("-", $aRow[perintailmaika]);
            $vrtaika2 = mktime (0, 0, 0, $pvt_p[1], $pvt_p[2]+$asetukset[0]['huomautusaika']+$asetukset[0]['perintailmoitusaika']+$asetukset[0]['perintaaika'], $pvt_p[0]); // Eräpäivä + huomaika karhun tulostukseen.

	?>
		<tr>
			<td><?php print $aRow[tilaus_id]; ?></td>
			<td><a rel="facebox" href="./asiakkaat.php?ID=24<?php print '&AsiakasID='.stripslashes($aRow['asiakas_id']); ?>"><?php print $tilaukset->asiakas($aRow[asiakas_id]); ?></a></td>
			<td><?php print number_format($reskontra->haeTilauksenSumma($aRow[tilaus_id]), 2, ',', ' '); ?> €</td>
			<td><?php print number_format($reskontra->haeTilauksenMaksettuSumma($aRow[tilaus_id]), 2, ',', ' '); ?> €</td>
			<td><?php print $aRow[viitenumero]; ?></td>
			<td><?php print $tilaukset->tilatoteksti($aRow[tila]); ?></td>
			<td><?php print $reskontra->dbTimeSuomiajaksi($aRow[luontipvm]); ?></td>
			<td><?php print $reskontra->dbTimeSuomiajaksi($aRow[laskutettupvm]); ?></td>
			<td><?php print $reskontra->dbTimeSuomiajaksi($aRow[erapvm]); ?></td>
			<td><?php print $reskontra->dbTimeSuomiajaksi($aRow[karhuaika]); ?></td>
			<td><?php print $reskontra->dbTimeSuomiajaksi($aRow[perintailmaika]); ?></td>
			<td class="muokkausnapit" width="250">
			<a class="ui-state-default ui-corner-all" style="float:left;margin:0 2px;" href="<?php print $_SERVER['PHP_SELF'].'?ID=84&tilausID='.stripslashes($aRow['tilaus_id']); ?>">
				<span style="float:left;padding:1px 4px;font-weight:normal;"/>Tilausrivit</span>
			</a>
			<a class="ui-state-default ui-corner-all" style="float:left;margin:0 2px;" onclick="return confirm('Haluatko kuitata tilauksen maksetuksi?')" href="<?php print 'reskontra.php?ID=83&tilausID='.stripslashes($aRow['tilaus_id']).'&FR=1'; ?>">
				<span style="float:left;padding:1px 4px;font-weight:normal;"/>Maksettu</span>
			</a>
			<a class="ui-state-default ui-corner-all" style="float:left;margin:0 2px;" href="reskontra.php?ID=119&tilausID=<?php echo $aRow['tilaus_id']; ?>&mode=copy" target="_BLANK">
				<span style="float:left;padding:1px 4px;font-weight:normal;"/>Karhukopio</span>
			</a>
			<?php
            if (time() > $vrtaika && $aRow[perintailmaika] == '0000-00-00') { ?>
			<a class="ui-state-default ui-corner-all" style="float:left;margin:0 2px;" href="reskontra.php?ID=119&tilausID=<?php echo $aRow['tilaus_id']; ?>" target="_BLANK">
				<span style="float:left;padding:1px 4px;font-weight:normal;"/>Karhu 2</span>
			</a>
            <?php }
            elseif (time() > $vrtaika2 && $aRow[perintailmaika] > '0000-00-00') { ?>
			<a class="ui-state-default ui-corner-all" style="float:left;margin:0 2px;" href="reskontra.php?ID=119&tilausID=<?php echo $aRow['tilaus_id']; ?>" target="_BLANK">
				<span style="float:left;padding:1px 4px;font-weight:normal;"/>Karhu 3</span>
			</a>
            <?php }
			?></td>
		</tr>
	<?php
	    }
       	$summa += $tilaukset->tilauksensumma ($aRow[tilaus_id]);
		$summaalv += $tilaukset->tilauksenAlvSumma ($aRow[tilaus_id]);
       	$kpl++;
       	$i++;
	}
?>		
		<tr>
			<td></td>
			<td align="right">Yhteensä <?php print $kpl ?> kpl</td>
			<td><?php print number_format($summa, 2, ',', ' ');?> € (alv 0%)</td>
			<td><?php print number_format($summaalv, 2, ',', ' ');?> € (sis. alv)</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
<?php
    if (count($productPages) != 0 && $_GET['page'] != 'all')
    {
        echo $pageNumbers = '<tr><th colspan="12" class="numbers">'.$pagination->links().'</th></tr>';
    }
}
else {?>
<tr>
<td colspan="12" class="tableContent" style="text-align:center;">Karhuttavia laskuja ei löydy</td>
</tr>
<?php }?> 
</tbody>
</table>