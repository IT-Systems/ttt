<?php

class paikallaoloVaraus extends Varaus {

    protected $VARAUS_TAULU = 'paikallaolo';
    protected $tiedot, $vaihtoehdot;
    public $id;
    private $saatavuusVaihtoehdot = array(
        1 => 'Paikalla',
        2 => 'Ei käytettävissä'
    );

    public static function varausTiedot() {
        $tiedot = array(
            'koskee_lentoja' => array('nimi' => 'Koskee lentoja'),
            'koskee_teoriaopetusta' => array('nimi' => 'Koskee teoriaopetusta'),
            'saatavuus' => array('nimi' => 'Saatavuus', 'vaihtoehdot' => 'haeSaatavuusVaihtoehdot'),
            'userid' => array('nimi' => 'Henkilö', 'vaihtoehdot' => 'haeHenkiloVaihtoehdot', 'preventDefault' => true),
            'lisatieto' => array('nimi' => 'Lisätiedot')
        );
        $tiedot = array_merge(parent::$YHTEISET_TIEDOT, $tiedot);
        return $tiedot;
    }

    protected function haeSaatavuusvaihtoehdot() {
        return $this->saatavuusVaihtoehdot;
    }

    protected function haeHenkiloVaihtoehdot() {
        $sql = "SELECT userid, username, firstname, lastname FROM {$this->DB_PREFIX}od_user ORDER BY username ASC";
        $stmt = $this->DB->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function eiRajoittavia() {
        //return parent::eiRajoittavia();

        $lento = $this->haeTiedot('koskee_lentoja');
        $teoria = $this->haeTiedot('koskee_teoriaopetusta');

        if (($lento == '1') || ($teoria == '1')) {
            
            $taulu = $this->DB_PREFIX . $this->VARAUS_PREFIX . $this->VARAUS_TAULU;
            $sql = "SELECT id FROM $taulu WHERE FROM_UNIXTIME(:alkuaika) < loppuaika AND FROM_UNIXTIME(:loppuaika) > alkuaika AND (";
            if ($lento == '1') {
                $sql .= "koskee_lentoja = '1' ";
            }
            if ($teoria == '1') {
                if ($lento == '1') {
                    $sql .= "OR ";
                }
                $sql .= "koskee_teoriaopetusta = '1' ";
            }
            $sql .= ")";
            if ($this->id > 0) {
                $sql .= ' AND id != :id ';
            }
            $sql .= ' AND userid = :userid';
            $stmt = $this->DB->prepare($sql);
            $stmt->bindValue(':alkuaika', $this->haeTiedot('alkuaika'));
            $stmt->bindValue(':loppuaika', $this->haeTiedot('loppuaika'));
            if ($this->id > 0) {
                $stmt->bindValue(':id', $this->id);
            }
            $stmt->bindValue(':userid', $this->haeTiedot('userid'));
            $stmt->execute();
            $count = count($stmt->fetchAll());
            $paikallaoloilmoitusrajoitus = ($count > 0) ? false : true;
        } else {
            $paikallaoloilmoitusrajoitus = false;
        }
        $normaalit_rajoittavat = parent::eiRajoittavia();
        if ($normaalit_rajoittavat && $paikallaoloilmoitusrajoitus) {
            return true;
        } else {
            return false;
        }
    }

}