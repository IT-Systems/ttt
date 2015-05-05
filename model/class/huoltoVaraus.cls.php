<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of huoltoVaraus
 *
 * @author oem
 */
class huoltoVaraus extends Varaus {
    protected $VARAUS_TAULU = 'huolto';
    protected $tiedot, $vaihtoehdot;
    public $id;
    
    public static function varausTiedot() {
        $tiedot = array(
            'kone'              => array('nimi' => 'Kone', 'vaihtoehdot' => 'haeKoneVaihtoehdot', 'rajoittava' => true),
            'vastuuhenkilo'     => array('nimi' => 'Vastuuhenkilö', 'vaihtoehdot' => 'haeVastuuhenkiloVaihtoehdot', 'get' => 'haeVastuuhenkilo'),
            'nimike'            => array('nimi' => 'Huollon nimike', 'vaihtoehdot' => 'haeNimikeVaihtoehdot', 'get' => 'haeNimikkeet'),
            'lisatieto'         => array('nimi' => 'Tarkentavat tiedot'),
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
    
    protected function haeVastuuhenkiloVaihtoehdot() {
        $sql = "SELECT userid, firstname, lastname FROM {$this->DB_PREFIX}od_user";
        $stmt = $this->DB->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    protected function haeNimikeVaihtoehdot() {
        $taulu = $this->DB_PREFIX . 'huoltonimikkeet';
        $sql = "SELECT id, nimike, koodi FROM $taulu";
        $stmt = $this->DB->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    protected function haeKonetyyppi() {
        $sql  = "SELECT kt.nimi AS konetyyppi_nimi FROM " . $this->DB_PREFIX . "konetyypit kt ";
        $sql .= "LEFT JOIN " . $this->DB_PREFIX . "koneet k ON k.konetyyppi = kt.id WHERE k.id = :kone";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindValue(':kone', $this->haeTiedot('kone'));
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    protected function haeVastuuhenkilo() {
        $sql  = "SELECT m.userid, m.username, m.email, m.firstname, m.lastname FROM " . $this->DB_PREFIX . "od_user m ";
        $sql .= "LEFT JOIN " . $this->DB_PREFIX . $this->VARAUS_PREFIX . $this->VARAUS_TAULU . " v ON ";
        $sql .= "m.userid = v.vastuuhenkilo WHERE v.id = :id";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    protected function haeNimikkeet() {
        $sql  = "SELECT h.nimike, h.koodi FROM " . $this->DB_PREFIX . "huoltonimikkeet h ";
        $sql .= "LEFT JOIN " . $this->DB_PREFIX . $this->VARAUS_PREFIX . $this->VARAUS_TAULU . " v ON ";
        $sql .= "v.nimike = h.id WHERE v.id = :id";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

?>
