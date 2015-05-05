<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Yleisiä staattisia funktioita
 */
class its {
    
    /**
     * Palauttaa annetun päivän viikon maanantain ja sunnuntain muodossa Y-m-d
     * @param int $pvm Päivämäärä (UNIX timestamp), jonka viikolta haetaan maanantai ja sunnuntai.
     * @return array [0] => maanantai [1] => sunnuntai 
     */
    public static function viikonAlkuLoppu($pvm) {
        // 86400 = 1 päivä sekunteina
        $ma = $pvm - (date('N', $pvm) - 1) * 86400;
        $su = $ma + 6 * 86400;
        return array(date('Y-m-d', $ma), date('Y-m-d', $su));
    }
    
    /**
     * Ottaa viikon minkä tahansa päivän (UNIX-timestamp) ja palauttaa viikon jokaisen päivän taulukkona UNIX-timestampina
     * @param int $pvm viikon joku päivä UNIX-timestampina
     * @return array [0]: maanantai ... [6]: sunnuntai 
     */
    public static function viikonPaivat($pvm) {
        $ma = $pvm - (date('N', $pvm) - 1) * 86400;
        $paivat[0] = $ma;
        for ($i = 1; $i <= 6; $i++) {
            $paivat[$i] = $ma + $i * 86400;
        }
        return $paivat;
    }
    

    /**
     * Ryhmittelee kaksiulotteisen taulukon annetun $key:n arvojen mukaan
     * @param array $array viittaus kaksiulotteiseen taulukkoon, joka ryhmitellään
     * @param string $key toisen ulottuvuuden taulukon avain, jonka arvon mukaan taulukko järjestetään
     * @param bool $sort true (default): järjestää avaimet, false: ei järjestä avaimia
     * @param bool $flat false (default): lisää arvot taulukkona $key:n alle; true: lisää arvot $key:n alle (hyvä, jos $key on uniikki)
     */
    public static function array_group(&$array, $key, $sort = true, $flat = false) {
        $grouped = array();
        if (isset($array)) {
            foreach ($array as $item) {
                if (!$flat) {
                    $grouped[$item[$key]][] = $item;
                }
                else {
                    $grouped[$item[$key]] = $item;
                }
            }
            if ($sort === true) {
                ksort($grouped);
            }
            $array = $grouped;
        }
    }
    
    /**
     * <p>Kahden taulukon pseudo-join</p>
     * <p>Hakee $from-taulukon $on-soluun tiedon $join taulukon solusta, jonka <b>avain</b> on sama kuin $from-taulukon $on-solun <b>arvo</b>.</p>
     * @param type $from viittaus taulukkoon, johon tiedot tuodaan.
     * @param type $join taulukko, josta liitettävät tiedot haetaan
     * @param type $on $from-taulukon solun avain, johon tiedot haetaan ja jonka arvon perusteella haetaan tietoa $join-taulukosta
     */
    public static function left_join(&$from, $join, $on) {
        if (isset($from)) {
            foreach ($from as $key => $row) {
                $joined_result = $join[$row[$on]];
                $from[$key][$on] = $joined_result;
            }
        }
    }
}

?>
