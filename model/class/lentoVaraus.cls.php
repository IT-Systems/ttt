<?php
class lentoVaraus extends Varaus {
    
    /**
     * Tämän varaustyypin taulun loppuosa 
     */
    protected $VARAUS_TAULU = 'lento';
    protected $MIEHISTO_TAULU = '_miehisto';
    protected $tiedot, $vaihtoehdot;
    public $id;
    
    public static function varausTiedot($args = null) {
        $tiedot = array(
            'kone'              => array('nimi' => 'Kone', 'vaihtoehdot' => 'haeKoneVaihtoehdot', 'rajoittava' => true),
            'ilma_aika'         => array('nimi' => 'Arvioitu ilma-aika'),
            'lkm'               => array('nimi' => 'Arvioitu lentojen lukumäärä'),
            'valvoja'           => array('nimi' => 'Valvoja', 'vaihtoehdot' => 'haeValvojaVaihtoehdot'),
            'kustannuspaikka'   => array('nimi' => 'Kustannus&shy;paikka', 'vaihtoehdot' => 'haeKustannuspaikkaVaihtoehdot'),
            'lisatieto'         => array('nimi' => 'Lisätiedot'),
            'tarkennukset'      => array('nimi' => 'Tarkentavat tiedot'),
            'tekstiviesti'      => array('nimi' => 'Lähetetäänkö tekstiviesti-ilmoitus?', 'tyyppi' => 'bool'),
            'miehisto'          => array('nimi' => 'Miehistö', 'vaihtoehdot' => 'haeMiehistoVaihtoehdot', 'set' => 'tallennaMiehisto', 'get' => 'haeMiehisto'),
            'konetyyppi'        => array('nimi' => 'Konetyyppi', 'set' => 'void', 'get' => 'haeKonetyyppi'),
            
        );
        $tiedot = array_merge(parent::$YHTEISET_TIEDOT, $tiedot);
        return $tiedot;
    }
    

    protected function haeKoneVaihtoehdot() {
        $sql  = "SELECT k.id AS koneetId, k.nimi AS koneetNimi, t.id AS konetyypitId, t.nimi AS konetyypitNimi FROM {$this->DB_PREFIX}koneet k ";
        $sql .= "LEFT JOIN {$this->DB_PREFIX}konetyypit t ON k.konetyyppi = t.id";
        $stmt = $this->DB->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    protected function haeMiehistoVaihtoehdot() {
        $sql = "SELECT userid, firstname, lastname FROM {$this->DB_PREFIX}od_user";
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
    
    protected function haeValvojaVaihtoehdot() {
        return $this->haeMiehistoVaihtoehdot();
    }
    
    
    
    
    protected function haeKonetyyppi() {
        $sql = "SELECT konetyyppi FROM " . $this->DB_PREFIX . "koneet WHERE id = :kone";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindValue(':kone', $this->haeTiedot('kone'));
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    
    /**
     * Hakee lentovarauksen miehistön
     * @return array käyttäjien tiedot taulukossa 
     */
    protected function haeMiehisto() {
        $sql  = "SELECT m.* FROM " . $this->DB_PREFIX . "od_user m ";
        $sql .= "LEFT JOIN " . $this->DB_PREFIX . $this->VARAUS_PREFIX . $this->VARAUS_TAULU . $this->MIEHISTO_TAULU . " l ON ";
        $sql .= "m.userid = l.henkilo WHERE l.lentovaraus = :id";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Tallentaa lentovarauksen miehistön
     * @return bool onnistuiko tallennus 
     */
    protected function tallennaMiehisto() {
        // Tyhjennetään entinen miehistö
        $taulu = $this->DB_PREFIX . $this->VARAUS_PREFIX . $this->VARAUS_TAULU . $this->MIEHISTO_TAULU;
        $sql = "DELETE FROM $taulu WHERE lentovaraus = :id";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $return = $stmt->execute();
        if (!$return) {
            throw new VarausException(202, 'lentoVaraus->tallennaMiehisto() (DELETE): ' . $sql);
        }
        
//        $miehisto = $this->haeTiedot('miehisto');
        $miehisto = $this->haeOlionTiedot('miehisto');
        // Jos on miehistö, tallennetaan
        if (!empty($miehisto)) {
            $sql = "INSERT INTO $taulu (lentovaraus, henkilo) VALUES ";
            $i = 0;
            foreach ($miehisto as $mies) {
                $sql .= '(:id, :mies' . $i . '), ';
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
                throw new VarausException(202, 'lentoVaraus->tallennaMiehisto(): ' . $sql);
            }
        }
        return $return;
    }
    
    public function poista() {
        $sql = "DELETE FROM " . $this->DB_PREFIX . $this->VARAUS_PREFIX . $this->VARAUS_TAULU . " WHERE id = " . $this->id;
        $stmt = $this->DB->prepare($sql);
        $stmt->execute();
        $sql = "DELETE FROM " . $this->DB_PREFIX . $this->VARAUS_PREFIX . $this->VARAUS_TAULU . $this->MIEHISTO_TAULU . " WHERE lentovaraus = " . $this->id;
        $stmt = $this->DB->prepare($sql);
        $stmt->execute();
    }
    
}

?>
