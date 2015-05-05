

<?php
if ($_GET['PF'] == '1')
{
	$aMsg[0] = "Yhteystiedot päivitetty.";
	$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
	$aMsg[2] = "ui-icon ui-icon-info";
}
elseif ($_GET['PF'] == '2')
{
	$aMsg[0] = "Tiedot päivitetty.";
	$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
	$aMsg[2] = "ui-icon ui-icon-info";
}
elseif ($_GET['PF'] == '4')
{
	$aMsg[0] = "Syllabus päivitetty.";
	$aMsg[1] = "ui-state-highlight ui-corner-all ui-message-box";
	$aMsg[2] = "ui-icon ui-icon-info";
}
?>
<div class="<?php print $aMsg[1]; ?>"> 
	<span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
	<?php print $aMsg[0]?>
</div>
<div style="float:left; padding:5px 0px 5px 0px;">	
	<a class="ui-od-button-with-icon ui-state-default ui-corner-all" href="kalenteri.php?ID=11">Uusi merkintä</a>
</div>
<div style="float:left; padding:5px 0px 5px 6px;">	
	<a class="ui-od-button-with-icon ui-state-default ui-corner-all" href="kalenteri.php?ID=12">Päivänäkymä</a>
</div>
<div style="float:left; padding:5px 0px 5px 6px;">	
	<a class="ui-od-button-with-icon ui-state-default ui-corner-all" href="kalenteri.php?ID=11">Viikkonäkymä</a>
</div>
<div style="float:left; padding:5px 0px 5px 6px;">
	<a class="ui-od-button-with-icon ui-state-default ui-corner-all" href="kalenteri.php?ID=11">Kuukausinäkymä</a>
</div>
<div style="float:left; padding:5px 0px 5px 6px;">
	<a class="ui-od-button-with-icon ui-state-default ui-corner-all" href="kalenteri.php?ID=11">Tulosta</a>
</div>
<div class="kp_div" style="float:left; width:100%; padding:5px 0px 0px 5px;">
	<div class="kalenteri">
		<table class="tbl_kalenteri">
			<thead class="fixedHeader">
				<tr>
			<?php 
				$naytetty = '0';
				foreach ($otsikot as $otsikko)
				{	
					?>					
						<td>
						<?php
							echo $otsikko;
						?>
						</td>
					<?php
			
				}
			?>
				<tr>
			</thead>
		</table>
		<div class="scrollContent">
		<table class="tbl_kalenteri">			
			<?php				
			foreach ($ajat as $aika)
			{
				foreach ($aika as $aika2)
				{
				?>
					<tr>
				<?php
					$sarake = 0;
					foreach ($aika2 as $aika3)
					{
						$sarake++;
						
						if ($sarake == 1)
						{
			?>								
							<td id="<?php echo $aika3['tunti'] . $aika3['minuutit']; ?>">
							<?php 														
							echo $aika3['tunti'] . ":" . $aika3['minuutit']; ?>
							</td>								
					<?php
						}
						else
						{
					?>
							<td>
							<?php 
							if ($aika3['merkinta'] != '' AND $naytetty == '0')
							{
								echo "<script>$('#" . $aika3['tunti'] . $aika3['minuutit'] ."').scroll();</script>";
								$naytetty = '1';
							}							
							
							echo $aika3['merkinta']; ?>
							</td>
					<?php
						}
					}
					?>
					</tr>
			<?php
				}
			}
			?>
			</tbody>
		</table>
		</div>
		<script>$(".scrollContent").scrollTop(3000);</script>
	</div>		
</div>	
<div id="qwe">

</div>
<script type="text/javascript">
function naytaViikko()
{
	$("#frmYhteystiedot").validate();
	$("#frmYhteystiedot").submit();
}

/* $("#naytatiedote").click(function () {		
	
});    */

 </script>