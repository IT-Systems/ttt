<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tilaVaraus
 *
 * @author oem
 */
class tilaVaraus extends Varaus {
    protected $VARAUS_TAULU = 'tila';
    protected $OSALLISTUJAT_TAULU = '_osallistujat';
    protected $tiedot, $vaihtoehdot;
    public $id;
    
    public static function varausTiedot() {
        $tiedot = array(
            'tila'              => array('nimi' => 'Tila', 'vaihtoehdot' => 'haeTilaVaihtoehdot', 'rajoittava' => true),
            'kustannuspaikka'   => array('nimi' => 'Kustannuspaikka', 'vaihtoehdot' => 'haeKustannuspaikkaVaihtoehdot'),
            'lisatieto'         => array('nimi' => 'Varausta koskevat lisätiedot'),
            'tarkennukset'      => array('nimi' => 'Varausta koskevat tarkentavat tiedot'),
            'tekstiviesti'      => array('nimi' => 'Lähetetäänkö vastuuhenkilöille varauksen vahvistamista koskeva tekstiviesti'),
            'osallistujat'      => array('nimi' => 'Osallistujat', 'vaihtoehdot' => 'haeOsallistujatVaihtoehdot', 'set' => 'tallennaOsallistujat', 'get' => 'haeOsallistujat'),
            'vastuuhenkilot'    => array('nimi' => 'Vastuuhenkilöt', 'vaihtoehdot' => 'haeVastuuhenkilotVaihtoehdot', 'set' => 'tallennaVastuuhenkilot', 'get' => 'haeVastuuhenkilot')
        );
        $tiedot = array_merge(parent::$YHTEISET_TIEDOT, $tiedot);
        return $tiedot;
    }
    
    protected function haeTilaVaihtoehdot() {
        $sql  = "SELECT id, nimi FROM {$this->DB_PREFIX}tilat t ";
        $stmt = $this->DB->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    protected function haeKustannuspaikkaVaihtoehdot() {
        $sql = "SELECT id, nimi FROM {$this->DB_PREFIX}kustannuspaikat";
        $stmt = $this->DB->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    protected function haeOsallistujatVaihtoehdot() {
        $sql = "SELECT userid, firstname, lastname FROM {$this->DB_PREFIX}od_user";
        $stmt = $this->DB->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    protected function haeVastuuhenkilotVaihtoehdot() {
        return $this->haeOsallistujatVaihtoehdot();
    }
    
    
    protected function haeOsallistujat() {
        return $this->haeOsallistujatVastuuhenkilot('0');
    }
    protected function haeVastuuhenkilot() {
        return $this->haeOsallistujatVastuuhenkilot('1');
    }
    protected function haeOsallistujatVastuuhenkilot($vastuu) {
        $taulu = $this->DB_PREFIX . $this->VARAUS_PREFIX . $this->VARAUS_TAULU . $this->OSALLISTUJAT_TAULU;
        $user_data_list = "m.userid, m.username, m.email, m.firstname, m.lastname, m.roleid, m.puhelin, m.oletuskustp, m.lupakirja, m.syllabus, m.kouluttaja";
        $sql  = "SELECT $user_data_list FROM {$this->DB_PREFIX}od_user m ";
        $sql .= "LEFT JOIN $taulu o ON m.userid = o.henkilo WHERE o.tilavaraus = :id AND o.vastuuhenkilo = $vastuu";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    protected function tallennaOsallistujat() {
        return $this->tallennaOsallistujatVastuuhenkilot('osallistujat');
    }
    protected function tallennaVastuuhenkilot() {
        return $this->tallennaOsallistujatVastuuhenkilot('vastuuhenkilot');
    }


    protected function tallennaOsallistujatVastuuhenkilot($tyyppi) {
        $miehisto = $this->haeOlionTiedot($tyyppi);
        if ($tyyppi == 'vastuuhenkilot') {
            $sql_lisays['del'] = " AND vastuuhenkilo = 1";
            $sql_lisays['ins'] = "(tilavaraus, henkilo, vastuuhenkilo)";
            $sql_lisays['val'] = ", 1";
        }
        elseif ($tyyppi == 'osallistujat') {
            $sql_lisays['del'] = '';
            $sql_lisays['ins'] = "(tilavaraus, henkilo)";
            $sql_lisays['val'] = '';
        }
        // Tyhjennetään entinen miehistö
        $taulu = $this->DB_PREFIX . $this->VARAUS_PREFIX . $this->VARAUS_TAULU . $this->OSALLISTUJAT_TAULU;
        $sql  = "DELETE FROM $taulu WHERE tilavaraus = :id";
        $sql .= $sql_lisays['del'];
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $return = $stmt->execute();
        if (!$return) {
            throw new VarausException(202, 'tilaVaraus->tallennaOsallistujatVastuuhenkilot() (DELETE)');
        }
        // Jos on miehistö, tallennetaan
        if (!empty($miehisto)) {
            $sql = "INSERT INTO $taulu {$sql_lisays['ins']} VALUES ";
            $i = 0;
            foreach ($miehisto as $mies) {
                $sql .= '(:id, :mies' . $i . $sql_lisays['val'] . '), ';
                $i++;
            }
            $sql = substr($sql, 0, -2);
            $stmt = $this->DB->prepare($sql);
            $stmt->bindParam(':id', $this->id);

            $i = 0;
            foreach ($miehisto as $mies) {
                $param = ':mies' . $i;
                $stmt->bindValue($param, $mies);
                $i++;
            }
            $return = $stmt->execute();
            if (!$return) {
                throw new VarausException(202, 'tilaVaraus->tallennaOsallistujatVastuuhenkilot() (INSERT)');
            }
        }
        return $return;
    }
}

?>
