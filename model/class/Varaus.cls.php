<?php



abstract class Varaus {
    /**
     * Varausten tauluissa käytettävä prefiksi 
     */
    protected $VARAUS_PREFIX = 'varaus_';
    protected $DB_PREFIX, $VARAUS_TAULU, $tiedot, $vaihtoehdot;
    /**
     * Todelliset, tietokantaan tallennettavat: alkuaika ja loppuaika
     * Virtuaaliset, vain olioon tallennettavat: alkupäivämäärä, alkutunti, lopputunti
     * @var Array 
     */
    protected static $YHTEISET_TIEDOT = array(
            'alkuaika'  => array('nimi' => 'Alkuaika', 'tyyppi' => 'aika'),
            'loppuaika' => array('nimi' => 'Loppuaika', 'tyyppi' => 'aika'),
            'userid'    => array('nimi' => 'Käyttäjä'),
            'alkupvm'   => array('nimi' => 'Alkupäivämäärä', 'set' => 'void', 'get' => 'haeAlkupvm'),
            'alkutunti' => array('nimi' => 'Varauksen alkamistunti', 'set' => 'void', 'get' => 'haeAlkuTunti'),
            'lopputunti'=> array('nimi' => 'Varauksen päättymistunti', 'set' => 'void', 'get' => 'haeLoppuTunti')
    );
    public $id;
    
    /**
     * Määrittelee varausolion tietokentät, joiden tiedot tallennetaan varausluokan tietokantatauluun.
     * Jos argumentiksi on annettu assosiatiivinen taulukko, asetetaan taulukon arvot olion tietokenttien arvoiksi.
     * @param array $args olion tiedot-ominaisuuden arvot
     * @return array varaustyypin tiedot  
     */
    abstract public static function varausTiedot(); 
    

    /**
     * Varauksen alustus. Jos id on määritetty, haetaan ko. varauksen tiedot tietokannasta olioon.
     * @global type $DB Open Delight DB
     * @global type $APP Open Delight APP
     * @param int $args (optional) varauksen id.
     */
    public function __construct($args = null) {
        global $DB,$APP, $USER;
        $this->DB         = $DB;
        $this->DB_PREFIX  = $APP->TABLEPREFIX;
  
        if (is_int($args)) { // jos on annettu id
            $this->tiedot = $this->haeVaraus($args);
            $this->id = $args;
        }
        elseif (is_array($args)) { // jos on annettu tiedot
            $this->tiedot = $this->varausTiedot();
            $this->tallennaTiedot($args);
        }
        else {
            $this->tiedot = $this->varausTiedot();
        }
        if (isset($this->tiedot['userid']) && !$this->tiedot['userid']['preventDefault']) {
            $this->tallennaTiedot(array('userid' => $USER->ID));
        }
        
    }
    
    /**
     * Ei tee mittää. 
     */
    final public function void() { }


    /**
     * Hakee määrätyn varauksen tiedot tietokannasta aliluokassa määritettyjen asetusten mukaisesti.
     * @param int $id varauksen ID
     * @return array
     */
    public function haeVaraus($id) {
        $sql = "SELECT * FROM {$this->DB_PREFIX}" . $this->VARAUS_PREFIX . $this->VARAUS_TAULU;
        $sql .= " WHERE id = :id";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $tiedot_db = $stmt->fetch(PDO::FETCH_ASSOC);
        unset($tiedot_db['id']);
        $return = $this->varausTiedotArvot($tiedot_db);
        return $return;
    }
    
    protected function haeAlkupvm() {
        $alku = new DateTime($this->haeTiedot('alkuaika'));
        return $alku->format('Y-m-d');
    }
    
    protected function laskeKesto() {
        $alku = new DateTime($this->haeTiedot('alkuaika'));
        $loppu = new DateTime($this->haeTiedot('loppuaika'));
        $erotus = $loppu->format('U') - $alku->format('U');
        return $erotus;
    }
    protected function haeAlkuTunti() {
        return $this->haeTunti('alkuaika');
    }
    protected function haeLoppuTunti() {
        return $this->haeTunti('loppuaika');
    }
    protected function haeTunti($aika) {
        $alku = new DateTime($this->haeTiedot($aika));
        $alkutunti = $alku->format('G');
        if ($alku->format('i') >= 45) {
            $alkutunti++;
        }
        return $alkutunti;
    }
    
    /**
     * Palauttaa varaustyypin rajoittavat tietotyypit, joita ei voi olla yhtäaikaisesti kuin yksi.
     * @return array 
     */
    public function haeRajoittavatTiedot() {
        $return = array();
        foreach ($this->tiedot as $key => $val) {
            if ($val['rajoittava'] === true) {
                $return[] = $key;
            }
        }
        return $return;
    }
    
    /**
     * Yhdistää saadun assosiatiivisen taulukon arvot varauksen tietojen ao. kenttien arvoiksi
     * @param array $arvot arvot assosiatiivisena taulukkona
     * @return array
     */
    protected function varausTiedotArvot($arvot = null) {
        $tiedot = $this->varausTiedot();
        if (!is_array($arvot)) {
            foreach ($tiedot as $key => $val) {
                $tiedot[$key]['arvo'] = '';
            }
        }
        else {
            foreach ($arvot as $key => $val) {
                $tiedot[$key]['arvo'] = $val;
            }
        }
        return $tiedot;
    }
    
    /**
     * Hakee kaikki tyypin varaukset.
     * @param bool $jarjesta Järjestetäänkö oliot ajan mukaan
     * @see aikaJarjestys
     * @return array sisältää kutsuvan luokan tyyppiset oliot taulukkomuodossa 
     */
    public function haeKaikkiVaraukset($jarjesta = true) {
        $sql = "SELECT id FROM " . $this->DB_PREFIX . $this->VARAUS_PREFIX . $this->VARAUS_TAULU;
        if ($jarjesta) {
            $sql .= " ORDER BY alkuaika ASC";
        }
        $stmt = $this->DB->prepare($sql);
        $stmt->execute();
        $tiedot_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $return = array();
        $class = get_class($this);
        foreach ($tiedot_db as $rivi) {
            $return[] = new $class((int) $rivi['id']);
        }
        return $return;
    } 
    
    
    /**
     * Hakee varaustyypin varaukset annetulta aikaväliltä järjestettynä annetun sarakkeen mukaisesti
     * @param string $alku alkuaika MySQL:n tukemassa DATETIME-muodossa, esim. yyyy-mm-dd tai yyyy-mm-dd HH:nn
     * @param string $loppu loppuaika MySQL:n tukemassa DATETIME-muodossa, esim. yyyy-mm-dd tai yyyy-mm-dd HH:nn
     * @param string $jarjesta taulukon sarake, jonka mukaan ensisijaisesti järjestetään (toissijaisesti järjestetään aina alkuajan mukaan)
     * @return \class 
     */
    public function haeVarauksetAjalta($alku, $loppu, $jarjesta) {
        $sql = "SELECT id FROM " . $this->DB_PREFIX . $this->VARAUS_PREFIX . $this->VARAUS_TAULU . " WHERE alkuaika >= :alku AND alkuaika <= :loppu ORDER BY :jarjesta ASC, alkuaika ASC";
        $stmt = $this->DB->prepare($sql);
        $stmt->bindValue(':alku', $alku);
        $stmt->bindValue(':loppu', $loppu);
        $stmt->bindValue(':jarjesta', $jarjesta);
        
        $stmt->execute();
        $tiedot_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $return = array();
        $class = get_class($this);
        foreach ($tiedot_db as $rivi) {
            $return[] = new $class((int) $rivi['id']);
        }
        return $return;
    }
    
    /**
     * Hakee käyttäjän varaustyypin varaukset alkaen annetusta päivämäärästä sekä varaukset, jotka ovat meneillään.
     * @param type $userid käyttäjän ID, jonka varaukset haetaan.
     * @param type $alkuaika (optional) aika, josta lähtien varaukset haetaan (oletusarvona on tämä päivä)
     * @return \class 
     */
    public function haeKayttajanVaraukset($userid, $alkuaika = null) {
       $where = ($userid === '0') ? null : 'AND userid = :userid';
       $alkuaika = ($alkuaika !== null) ? $alkuaika : date('Y-m-d');
       $sql = "SELECT id FROM " . $this->DB_PREFIX . $this->VARAUS_PREFIX . $this->VARAUS_TAULU . " WHERE ((alkuaika >= :alkuaika) OR (loppuaika >= :alkuaika)) $where ORDER BY alkuaika ASC";
       $stmt = $this->DB->prepare($sql);
       $stmt->bindValue(':alkuaika', $alkuaika);
       if (!empty($where)) {
           $stmt->bindValue(':userid', $userid);
       }
       $stmt->execute();
       
       $tiedot_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
       $return = array();
       $class = get_class($this);
       foreach ($tiedot_db as $rivi) {
           $return[] = new $class((int) $rivi['id']);
       }
       return $return;
    }
    
    /**
     * Hakee käyttäjän vanhat varaukset, eli ne, joiden päättymispäivä on jo mennyt
     * (Muuten identtinen haeKayttajanVaraukset-funktion kanssa, ja ehkä nämä olisi voinut yhdistääkin)
     * @param type $userid käyttäjän ID, jonka varaukset haetaan.
     * @param type $loppuaika (optional) aika, josta lähtien taaksepäin varaukset haetaan (oletusarvona on tämä päivä)
     * @see haeKayttajanVaraukset
     * @return \class 
     */
    public function haeKayttajanVanhatVaraukset($userid, $loppuaika = null) {
       $where = ($userid === '0') ? null : 'AND userid = :userid';
       $loppuaika = ($loppuaika !== null) ? $loppuaika : date('Y-m-d');
       $sql = "SELECT id FROM " . $this->DB_PREFIX . $this->VARAUS_PREFIX . $this->VARAUS_TAULU . " WHERE loppuaika <= :loppuaika $where ORDER BY alkuaika ASC";
       $stmt = $this->DB->prepare($sql);
       $stmt->bindValue(':loppuaika', $loppuaika);
       if (!empty($where)) {
            $stmt->bindValue(':userid', $userid);
       }
       $stmt->execute();
       
       $tiedot_db = $stmt->fetchAll(PDO::FETCH_ASSOC);
       $return = array();
       $class = get_class($this);
       foreach ($tiedot_db as $rivi) {
           $return[] = new $class((int) $rivi['id']);
       }
       return $return;
    }
    
    
    
    
    public function haeRajoittavatTiedotArvot() {
        $keys = $this->haeRajoittavatTiedot();
        $return = array();
        foreach ($keys as $key) {
            $arvo = $this->haeTiedot($key);
            if (!empty($arvo)) {
                $return[] = $arvo;
            }
        }
        return $return;
    }
    
    /**
     * <i><b>A</b>ppend <b>I</b>D</i><br>
     * Lisää annettuun tekstiin olion id:n ja echottaa sen. Kätevä esim. lomakkeiden id-kentissä.
     * @param string $text 
     */
    public function ai($text) {
        echo $text . $this->id;
    }
    
    /**
     * Tallentaa olion tiedot tietokantaan. Jos oliolle on asetettu id, päivitetään kyseistä riviä.
     * @return bool onnistuiko tallennus 
     * @throws VarausException
     */
    public function tallennaVaraus($debug = false) {
        $rajoittavien_arvot = $this->haeRajoittavatTiedotArvot();
        $rajoittavat_kentat = $this->haeRajoittavatTiedot();
        if ((!empty($rajoittavien_arvot) && (!empty($rajoittavat_kentat))) || (empty($rajoittavat_kentat))) {
            if ($this->eiRajoittavia() === true) {
                // Jos ID on asetettu, päivitetään taulua 
                if (is_int($this->id)) {
                    $sql = "UPDATE ";
                    $where = " WHERE id = :id";
                }
                // Muuten lisätään tiedot tauluun
                else {
                    $sql = "INSERT INTO ";
                    $where = "";
                }
                $sql .= $this->DB_PREFIX. $this->VARAUS_PREFIX . $this->VARAUS_TAULU;
                $sql .= " SET ";

                $normaalit_tiedot = array();
                $muut_tiedot = array();

                /**
                * Tarkistetaan, onko tietueella oma setteri 
                */
                foreach ($this->tiedot as $nimi => $tieto) {
                    if (array_key_exists('set', $tieto)) {
                        $muut_tiedot[$nimi] = $tieto;
                    }
                    else {
                        $normaalit_tiedot[$nimi] = $tieto;
                    }
                }

                /**
                * NORMAALIEN TIETOJEN KÄSITTELY (suoraan varaustyypin omaan tauluun) 
                */        

                foreach ($normaalit_tiedot as $key => $val) {
                    if ($val['tyyppi'] == 'aika') {
                        $sql .= "$key = FROM_UNIXTIME(:$key), ";
                    }
                    else {
                        $sql .= "$key = :$key, ";
                    }
                }
                // poistetaan ylimääräinen pilkku ja välilyönti
                $sql = substr($sql, 0, -2);
                $sql .= $where;
                $stmt = $this->DB->prepare($sql);
                if (is_int($this->id)) {
                    $stmt->bindValue(':id', $this->id);
                }
                // Käydään olion tiedot läpi ja yhdistetään arvot tietokantakäskyyn
                foreach ($normaalit_tiedot as $key => $val) {
                    $arvo = (empty($val['arvo'])) ? '' : $val['arvo'];
                    $stmt->bindValue(":$key", $arvo);
                }

                $return[0] = $stmt->execute();

                if (!$return[0]) {
                    throw new VarausException(203, $sql);
                }

                // Jos lisättiin uusi rivi tauluun, asetetaan olion id:ksi tietokantarivin id
                if (!is_int($this->id) && $return[0]) {
                    $this->id = (int) $this->DB->lastInsertId();
                }


                /**
                * MUIDEN, MONIMUTKAISEMPIEN TIETOJEN KÄSITTELY ('set' callback-funktion mukainen suoritus) 
                */
                foreach ($muut_tiedot as $nimi => $tieto) {
                    $return[] = $this->{$this->tiedot[$nimi]['set']}();
                }

                if ($debug === false) {
                    return (in_array(false, $return)) ? false : true;
                }
                else {
                    return $return;
                }
            } // endif ei ole päällekkäisiä aikoja
            else {
                throw new VarausException(201);
            }
        } // endif rajoittavien arvot ovat ei-tyhjät
        else {
            throw new VarausException(301);
        }
    }
    
    /**
     * @return bool true: jos tietokannassa ei ole samoja rajoittava-tyyppisiä tietoja samalla ajalla (= voidaan tallentaa varaus tietokantaan), muuten false.
     */
    public function eiRajoittavia() {
        $rajoittavat = $this->haeRajoittavatTiedot();
        
        if (count($rajoittavat) > 0) {
            $taulu = $this->DB_PREFIX. $this->VARAUS_PREFIX . $this->VARAUS_TAULU;
            $sql = "SELECT id FROM $taulu WHERE FROM_UNIXTIME(:alkuaika) < loppuaika AND FROM_UNIXTIME(:loppuaika) > alkuaika AND ";
            foreach ($rajoittavat as $raj) {
                $sql .= "$raj = :$raj AND ";
            }
            $sql = substr($sql, 0, -5);
            if ($this->id > 0) {
                $sql .= ' AND id != :id';
            }
            $stmt = $this->DB->prepare($sql);
            foreach ($rajoittavat as $raj) {
                $stmt->bindValue(":$raj", $this->haeTiedot($raj));
            }
            $stmt->bindValue(':alkuaika', $this->haeTiedot('alkuaika'));
            $stmt->bindValue(':loppuaika', $this->haeTiedot('loppuaika'));
            if ($this->id > 0) {
                $stmt->bindValue(':id', $this->id);
            }
            $stmt->execute();
            $count = count($stmt->fetchAll());
            return ($count > 0) ? false : true;
        }
        else {
            return true;
        }
    }
    
    /**
     * Poistaa varauksen ID:n mukaan
     * @return bool onnistuiko tallennus
     */
    public function poistaVaraus($id) {
        if (is_int($id)) {
            $sql = "DELETE FROM " . $this->DB_PREFIX . $this->VARAUS_PREFIX . $this->VARAUS_TAULU . " WHERE id = :id";
            $stmt = $this->DB->prepare($sql);
            $stmt->bindValue(':id', $id);
            return $stmt->execute();
        }
        else {
            return false;
        }
    }
    
    /**
     * Poistaa $args taulukosta alkiot, joita ei ole määritelty aliluokassa.
     * @param array $args viittaus taulukkoon, joka puhdistetaan
     * @return bool true, jos taulukko on muuttunut; false jos pysynyt samanlaisena
     */
    public function puhdistaKentat(&$args) {
        $puhdas = array_intersect_key($args, $this->varausTiedot());
        $return = ($args === $puhdas) ? false : true;
        $args = $puhdas;
        return $return;
    }
    
    /**
     * Tallentaa tiedot olioon
     * @param array $args tiedot taulukkomuodossa (mysql-sarake => arvo)
     */
    public function tallennaTiedot($args) {
        $this->puhdistaKentat($args);
        if (is_array($args) && count($args) > 0) {
            foreach ($args as $key => $val) {
                $this->tiedot[$key]['arvo'] = $val;
            }
            return true;
        }
        return false;
    }
    
    /**
     * Hae tietueen nimi
     * @param string $nimi tietosarake
     * @return type tietosarakkeen nimi
     */
    public function haeNimi($nimi, $echo = true) {
         $return = $this->tiedot[$nimi]['nimi'];
         if ($echo) {
             echo $return;
         }
         else {
            return $return;
         }
    }
    
    /**
     * Palauttaa mahdolliset vaihtoehdot, joita voi argumenttina annettuun tietueeseen tallentaa
     * @param string $vaihtoehto Minkä tietueen vaihtoehdot halutaan (jos null, niin palautetaan kaikki)
     * @return type mahdolliset vaihtoehdot. Voi palauttaa myös muuta dataa, esimerkiksi datan esittämistä varten.
     */
    public function haeVaihtoehdot($vaihtoehto = null) {
        if ($vaihtoehto !== null) {
            return $this->{$this->tiedot[$vaihtoehto]['vaihtoehdot']}();
        }
        else {
            
        }
    }
    
    /**
     * Hae olion tietty tieto (tai kaikki tiedot jos ei parametrejä)
     * @param string $tietue (optional) olion tietue, joka haetaan
     * @return type 
     */
    public function haeTiedot($tietue = null) {
        if ($tietue !== null) {
            if (array_key_exists('get', $this->tiedot[$tietue])) {
                return $this->{$this->tiedot[$tietue]['get']}();
            }
            else {
                return $this->tiedot[$tietue]['arvo'];
            }
        }
        else {
            $return = array();
            foreach($this->tiedot as $tietue => $val) {
                if (array_key_exists('get', $this->tiedot[$tietue])) {
                    $return[$tietue] = $this->{$this->tiedot[$tietue]['get']}();
                }
                else {
                    $return[$tietue] =  $this->tiedot[$tietue]['arvo'];
                }
            }
            return $return;
        }
    }
    
    /**
     * Hakee suoraan vain olion tiedot (ei ota huomioon varauksen omia gettereitä).
     * @param string $tietue (optional) tietty tietue. Jos null, niin haetaan kaikki tiedot.
     * @return type 
     */
    public function haeOlionTiedot($tietue = null) {
        if ($tietue !== null) {
            return $this->tiedot[$tietue]['arvo'];
        } else {
            $return = array();
            foreach ($this->tiedot as $tietue => $val) {
                $return[$tietue] = $this->tiedot[$tietue]['arvo'];
            }
            return $return;
        }
    }
    
    public function haeVarausTyyppi() {
        return $this->VARAUS_TAULU;
    }
    

    /**
     * Järjestää annetun varausoliotaulukon aikajärjestykseen
     * @param type $varaukset järjestettävät varausoliot taulukkomuodossa
     * @param type $tyyppi aikatyyppi, jonka mukaan järjestetään (oletus: alkuaika)
     * @param type $suunta järjestyksen suunta (oletus SORT_ASC)
     */
    public static function aikaJarjestys(&$varaukset, $tyyppi = 'alkuaika', $suunta = SORT_ASC) {
        // Koska DateTimen uusimpia funktioita ei ole kehitysympäristön PHP:n versiossa, pilkotaan ensin aika strptime():lla $ajat_helperiin,
        // ja muodostetaan järjestettävät ajat UNIX-timestampeiksi $ajat:iin
        $ajat_helper = $ajat = array();
        
        foreach ($varaukset as $key => $varaus) {
            // Haetaan jokainen varauksen $tyyppi-aika varauksen tiedoista ja oletetaan sen olevan muotoa YYYY-mm-dd HH:mm:ss
            $ajat_helper[$key] = strptime($varaus[$tyyppi], '%F %T');
        }
        
        foreach ($ajat_helper as $key => $aika) {
            // Käydään läpi jokainen muodostettu $ajat_helper, joista jokainen sisältää aikatiedot taulukkomuodossa
            // HOX: tm_mon on "kuinka monta kuukautta on kulunut tammikuusta", eli 0-11
            // HOX: tm_year on "kuinka monta vuotta on kulunut vuodesta 1900", eli esim. 2012 = 112
            $ajat[$key] = mktime($aika['tm_hour'], $aika['tm_min'], $aika['tm_sec'], 1 + $aika['tm_mon'], $aika['tm_mday'], 1900 + $aika['tm_year']);
        }
        
        // järjestää $ajat järjestykseen $suunnan mukaan; sitten järjestää $varaukset samaan järjestykseen kuin uusi $ajat.
        array_multisort($ajat, $suunta, $varaukset);
    }
    
    
    public static function varausToArray($varaus) {
        $return;
        if (is_array($varaus)) {
            $i = 0;
            foreach ($varaus as $olio) {
                $return[$i] = $olio->haeTiedot();
                $return[$i]['id'] = $olio->id;
                $i++;
            }
        }
        else {
            $return = $varaus->haeTiedot();
            $return['id'] = $varaus->id;
        }
        return $return;
    }
    
    /**
     * Yhdistää $liitettävät varaukset, järjestää ne ensisijaisesti $liitoksen mukaan ja toissijaisesti alkuajan mukaan
     * @param array $liitettavat liitettävät varaustaulukko-oliot 
     * @param string $liitos tietueen nimi, jonka suhteen järjestetään ensisijaisesti (esim. 'kone' tai 'kustannuspaikka')
     * @return array järjestetty taulukko 
     */
    public static function yhdistaVarausTiedot($liitettavat, $liitos) {
        $varaukset = $liitokset = array();
        /**
         * Ympätään kaikki annetut varausoliot yhteiseen varaustaulukkoon 
         */
        foreach ($liitettavat as $oliot) {
            foreach ($oliot as $varaus) {
                $tiedot = $varaus->haeTiedot();
                $tiedot['varaus_tyyppi'] = $varaus->haeVarausTyyppi();
                $tiedot['id'] = $varaus->id;
                $varaukset[] = $tiedot;
            }
        }
        /**
         * Haetaan jokaisen olion "$liitos" tietue liitostaulukkoon, jonka rivien avaimena toimii olion järjestysnumero varaustaulukossa.
         */
        foreach ($varaukset as $key => $varaus) {
            $liitokset[$key] = $varaus[$liitos];
        }
        
        /**
         * Järjestetään ensin liitostaulukon arvot kasvavaan järjestykseen, sitten järjestetään varaustaulukko samaan järjestykseen 
         */
        array_multisort($liitokset, SORT_ASC, $varaukset);

        /**
         * Tallennetaan jokaisen olion ajat varaustaulukon avaimen mukaan strpajat taulukkoon, jonka arvoja voidaan hyödyntää mktime()-funktiossa. 
         */
        $strpajat = array();
        foreach ($varaukset as $key => $varaus) {
            $strpajat[$key] = strptime($varaus['alkuaika'], '%F %T');
        }
        
        /**
         * Järjestetään varaukset $liitoksittain ajan mukaan. 
         */
        $aika_varaukset = array();
        $aika_varaukset_liitos = array();
        $alkuajat = array();
        $edellinen = false;
        foreach ($varaukset as $key => $varaus) {
            // Suorittuu vain ensimmäisellä kierroksella
            if (!$edellinen) {
                $edellinen = $varaus[$liitos];
            }
            
            elseif ($edellinen !== $varaus[$liitos]) {
                // $liitos on eri kuin viime kierroksella, eli suoritetaan järjestäminen
                array_multisort($alkuajat, SORT_ASC, $aika_varaukset_liitos);
                // yhdistetään järjestetyt varaukset lopulliseen, palautettavaan taulukkoon
                $aika_varaukset = array_merge($aika_varaukset, $aika_varaukset_liitos);
                
                // nollataan $liitos-riippuvaiset taulukot
                $alkuajat = array();
                $aika_varaukset_liitos = array();
            }
            
            // Luodaan varauksen ajoista UNIX-aika taulukkosolu, joka myöhemmin järjestetään
            $alkuajat[$key] = mktime($strpajat[$key]['tm_hour'], $strpajat[$key]['tm_min'], $strpajat[$key]['tm_sec'], 1 + $strpajat[$key]['tm_mon'], $strpajat[$key]['tm_mday'], 1900 + $strpajat[$key]['tm_year']);
            // Tallennetaan itse varaus erilliseen taulukkoon, joka tulee sisältämään aina vain yhden $liitos-tyyppisen tiedon varauksia
            $aika_varaukset_liitos[$key] = $varaus;
            // tallennetaan tämän kierroksen $liitos-tyyppi
            $edellinen = $varaus[$liitos];
        }
        // Viimeisen kierroksen jälkeen foreach-loopin sisällä oleva vertailuehto ei luonnollisesti suoritu, joten suoritetaan järjestäminen vielä kerran
        array_multisort($alkuajat, SORT_ASC, $aika_varaukset_liitos);
        $aika_varaukset = array_merge($aika_varaukset, $aika_varaukset_liitos);
        
        return $aika_varaukset;
    }
}




?>
