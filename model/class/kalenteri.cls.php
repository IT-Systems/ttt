<?php 
class kalenteri
{
	/* CONSTRUCTOR */
	function __construct() 
	{
	    global $DB,$APP,$USER;
		$this->DB   = $DB;
		$this->APP  = $APP;	
		$this->USER = $USER;	
		return true;
	}
	
	function haeMerkinnatVko()
	{
		$sSqlData = "SELECT * FROM {$this->APP->TABLEPREFIX}merkinnat WHERE kayttaja_id = :userid";
		$oStmtData= $this->DB->prepare($sSqlData);
		$oStmtData->bindParam(':userid', $this->USER->ID);
		$oStmtData->execute();
		$merkinnat = $oStmtData->fetchAll();
		return $merkinnat;
	}
	
	function haeMerkintaVko($paiva_etsi, $aika)
	{	
		//echo $paiva_etsi . " " . $aika . "<br>";		
				
		$sSqlData = "SELECT * FROM {$this->APP->TABLEPREFIX}merkinnat " . 					
					"WHERE kayttaja_id = :userid " . 
					"AND (pvm_alkaa = :paiva OR pvm_loppuu = :paiva) AND (aika_alkaa = :aika)";
		$oStmtData= $this->DB->prepare($sSqlData);
		$oStmtData->bindParam(':userid', $this->USER->ID);
		$oStmtData->bindParam(':paiva', $paiva_etsi);
		$oStmtData->bindParam(':aika', $aika);
		$oStmtData->execute();
		$merkinnat = $oStmtData->fetch();
		return $merkinnat;
	}
	
	function haeMerkinnatPaiva($paiva)
	{
		$sSqlData = "SELECT * FROM {$this->APP->TABLEPREFIX}merkinnat WHERE kayttaja_id = :userid AND (pvm_alkaa = :paiva OR pvm_loppuu = :paiva)";
		$oStmtData= $this->DB->prepare($sSqlData);
		$oStmtData->bindParam(':userid', $this->USER->ID);
		$oStmtData->bindParam(':paiva', $paiva);
		$oStmtData->execute();
		$merkinnat = $oStmtData->fetchAll();
		return $merkinnat;	
	}
	
	function haeMerkintaPaiva($paiva_etsi, $aika)
	{	
		//echo $paiva_etsi . " " . $aika . "<br>";
				
		$sSqlData = "SELECT * FROM {$this->APP->TABLEPREFIX}merkinnat " . 					
					"WHERE kayttaja_id = :userid " . 
					"AND (pvm_alkaa = :paiva OR pvm_loppuu = :paiva) AND (aika_alkaa = :aika)";
		$oStmtData= $this->DB->prepare($sSqlData);
		$oStmtData->bindParam(':userid', $this->USER->ID);
		$oStmtData->bindParam(':paiva', $paiva_etsi);
		$oStmtData->bindParam(':aika', $aika);
		$oStmtData->execute();
		$merkinnat = $oStmtData->fetch();
		return $merkinnat;
	}
	
	function haeViikonpaivat()
	{		
		$week_number = date("W");
		$year = date("Y");
		$otsikot = array();
		
		for($day=1; $day<=7; $day++)
		{			
			$otsikot[0] = '';
			$otsikot[$day] = date('d.m.Y', strtotime($year."W".$week_number.$day));			
		}
		
		return $otsikot;	
	}
	
	function teeViikkokalenteri()
	{
		$merkinnat = $this->haeMerkinnatVko();
		
		/* echo "<pre>";
			print_r($merkinnat);
		echo "</pre>"; */	
		
		$ajat = array();	
		$rivi = 0;
		$week_number = date("W");
		$year = date("Y");
		
		for ($i = 0; $i <= 23; $i++)
		{
			
						
			$rivi++;			
			$tunti = $i;
			
			if ($i < 10)
			{
				$tunti = '0' . $i;
			}			
			
			for ($a = 0; $a <= 59; $a+=5)
			{
				$sarake = 0;
				$minuutit = $a;
				
				if ($a < 10)
				{
					$minuutit = '0' . $a;
				}	
					
				$ajat[$rivi][$a][$sarake]['tunti'] = $tunti;
				$ajat[$rivi][$a][$sarake]['minuutit'] = $minuutit;
				
				for($day=1; $day<=7; $day++)
				{
					$sarake++;
					$paiva = date('d.m.Y', strtotime($year."W".$week_number.$day));
					$aika = $tunti . ":" . $minuutit;
					$ajat[$rivi][$a][$sarake]['paiva'] = $paiva;
					$ajat[$rivi][$a][$sarake]['tunti'] = $tunti;
					$ajat[$rivi][$a][$sarake]['minuutit'] = $minuutit;
										
					$paiva_explode = explode('.', $paiva);
					$paiva_etsi = $paiva_explode[2] . "-" . $paiva_explode[1] . "-" . $paiva_explode[0];					
					$merkinta = $this->in_multi_array($paiva_etsi, $merkinnat, $aika);
					
					/* echo "<pre>";
						print_r($merkinta);
					echo "</pre>"; */
										
					if (!empty($merkinta))
					{
						$ajat[$rivi][$a][$sarake]['merkinta'] = $merkinta['otsikko'];
						$ajat[$rivi][$a][$sarake]['merkinta_id'] = $merkinta['id'];
					}
					else
					{
						$ajat[$rivi][$a][$sarake]['merkinta'] = '';
						$ajat[$rivi][$a][$sarake]['merkinta_id'] = '';
					}
				}			
			
			}		
		}		
		
		/* echo "<pre>";
			print_r($ajat);
		echo "</pre>"; */
		return $ajat;
	}
	
	function teePaivakalenteri($paiva)
	{
		$merkinnat = $this->haeMerkinnatPaiva($paiva);
		
		/* echo "<pre>";
			print_r($merkinnat);
		echo "</pre>"; */
		
		$ajat = array();	
		$rivi = 0;
		$week_number = date("W");
		$year = date("Y");
		
		for ($i = 0; $i <= 23; $i++)
		{						
			$rivi++;			
			$tunti = $i;
			
			if ($i < 10)
			{
				$tunti = '0' . $i;
			}			
			
			for ($a = 0; $a <= 59; $a+=5)
			{
				$minuutit = $a;
				
				if ($a < 10)
				{
					$minuutit = '0' . $a;
				}	
					
				$ajat[$rivi][$a]['tunti'] = $tunti;
				$ajat[$rivi][$a]['minuutit'] = $minuutit;
				$ajat[$rivi][$a]['paiva'] = $paiva;				
				$aika = $tunti . ":" . $minuutit;
				
				$merkinta = $this->in_multi_array_day($paiva, $merkinnat, $aika);
														
				if (!empty($merkinta))
				{
					$ajat[$rivi][$a]['merkinta'] = $merkinta['otsikko'];
					$ajat[$rivi][$a]['merkinta_id'] = $merkinta['id'];
					$ajat[$rivi][$a]['teksti'] = $merkinta['teksti'];
				}
				else
				{
					$ajat[$rivi][$a]['merkinta'] = '';
					$ajat[$rivi][$a]['merkinta_id'] = '';
					$ajat[$rivi][$a]['teksti'] = '';
				}			
			}		
		}		
		
		/* echo "<pre>";
			print_r($ajat);
		echo "</pre>"; */
		return $ajat;
	
	}
	
	function in_multi_array($needle, $haystack, $time)
	{	
		//echo $needle;
		
		if(in_array($needle, $haystack))
		{
			$in_multi_array = true;
			$merkinta = $this->haeMerkintaVko($needle, $time);
			return $merkinta;
		}
		else
		{
			for($i = 0; $i < sizeof($haystack); $i++)
			{
				if(is_array($haystack[$i]))
				{
					if($this->in_multi_array($needle, $haystack[$i], $time))
					{
						$in_multi_array = true;
						$merkinta = $this->haeMerkintaVko($needle, $time);
						return $merkinta;
						break;
					}
				}
			}
		}
	}	
	
	function in_multi_array_day($needle, $haystack, $time)
	{	
		//echo $needle;
		
		if(in_array($needle, $haystack))
		{
			$in_multi_array = true;
			$merkinta = $this->haeMerkintaPaiva($needle, $time);
			return $merkinta;
		}
		else
		{
			for($i = 0; $i < sizeof($haystack); $i++)
			{
				if(is_array($haystack[$i]))
				{
					if($this->in_multi_array_day($needle, $haystack[$i], $time))
					{
						$in_multi_array = true;
						$merkinta = $this->haeMerkintaPaiva($needle, $time);
						return $merkinta;
						break;
					}
				}
			}
		}
	}	
	
	function haePaivanNimi($paiva)
	{
		$paiva_etsi = explode('-', $paiva);
		$paiva_nro = date('N', mktime(0, 0, 0, $paiva_etsi[1], $paiva_etsi[2], $paiva_etsi[0]));
		
		if ($paiva_nro == '1')
		{
			$nimi = 'Maanantai';
		}
		elseif ($paiva_nro == '2')
		{
			$nimi = 'Tiistai';
		}
		elseif ($paiva_nro == '3')
		{
			$nimi = 'Keskiviikko';
		}
		elseif ($paiva_nro == '4')
		{
			$nimi = 'Torstai';
		}
		elseif ($paiva_nro == '5')
		{
			$nimi = 'Perjantai';
		}
		elseif ($paiva_nro == '6')
		{
			$nimi = 'Lauantai';
		}
		elseif ($paiva_nro == '7')
		{
			$nimi = 'Sunnuntai';
		}
		
		return $nimi;
	}
		
} //End Of Class Statement
?>
