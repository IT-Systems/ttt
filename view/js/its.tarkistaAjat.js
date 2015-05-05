/**
     * Tarkistaa varauksen ajat määrätyistä kentistä
     * @param bool finalCheck Onko kyseessä viimeinen tarkastus (tällöin myös tyhjät solut tarkastetaan)
     * @return Jos kaikki kentät ovat OK, palautetaan <b>true</b>. Jos on virheitä, palautetaan <b>false</b>.
     */
    function tarkistaAjat(trigger, finalCheck) {
        /**
         * Prefiksi, joka haetaan kutsuvan elementin lähimmän (vanhemman) "prefix"-HTML-attribuutista
         */
        var prefix = trigger.closest('.aika_wrapper').attr('prefix');
        
        // Määritetään erottimia ja päivämäärämuoto
        var DATE_SEPARATOR = '.';
        var TIME_SEPARATOR = '.';
        var DATE_FORMAT = 'dd' + DATE_SEPARATOR + 'mm' + DATE_SEPARATOR + 'yy';
        
        /**
         * HTML-Kentät, jotka tarkistetaan
         */
        var FIELDS = {ad: $('#'+prefix+'alkuaika-pvm'), at: $('#'+prefix+'alkuaika-aika'), ld: $('#'+prefix+'loppuaika-pvm'), lt: $('#'+prefix+'loppuaika-aika')};
        
        /**
         * CSS-luokka, joka lisätään virheellisiin kenttiin
         */
        var ERROR_CLASS = 'aika-error';
        
        // Poistetaan aluksi mahdollinen virhe-CSS jokaisesta kentästä
        for (f in FIELDS) {
            FIELDS[f].removeClass(ERROR_CLASS);
        }
        
        // Haetaan kenttien arvot muuttujiin
        var alkuPaiva = FIELDS.ad.val();
        var loppuPaiva = FIELDS.ld.val();
        var alkuAika = FIELDS.at.val();
        var loppuAika = FIELDS.lt.val();
        
        /**
         * Pitää kirjaa virheiden määrästä
         */
        var hasErrors = 0;
        
        
        /**
         * Jos jotain aikatietoa ei ole, haetaan tiedot nykyisen hetken aikatiedoista.
         */
        var fallback = new Date();
        
        /**
         * Pitää kirjaa, missä kentissä on virheitä.
         */
        var errors = {ad: false, at: false, ld: false, lt: false};
        
        // Tarkistetaan, onko joku aikakenttä tyhjä
        // Jos molemmat ajoista uupuu, asetetaan niiden ajoiksi nykyinen aika ja molempiin virhe
        if (!alkuAika && !loppuAika) {
            alkuAika = loppuAika = fallback.getHours() + TIME_SEPARATOR + fallback.getMinutes();
            errors.at = errors.lt = true;
        }
        // Jos aloitusaika uupuu, asetetaan se samaksi kuin loppuaika (myöhempää vertailua varten) ja aloitusaikaan virhe
        else if (!alkuAika) {
            alkuAika = loppuAika;
            errors.at = true;
        }
        // Kääntäen sama kuin yllä, mutta loppuajalle
        else if (!loppuAika) {
            loppuAika = alkuAika;
            errors.lt = true;
        }
        
        // Vastaavat vertailut suoritetaan päivämääräkentille
        if (!alkuPaiva && !loppuPaiva) {
            alkuPaiva = loppuPaiva = $.datepicker.formatDate(DATE_FORMAT, fallback);
            errors.ad = errors.ld = true;
        }
        else if (!alkuPaiva) {
            alkuPaiva = loppuPaiva;
            errors.ad = true;
        }
        else if (!loppuPaiva) {
            loppuPaiva = alkuPaiva;
            errors.ld = true;
        }
        
        // Hajotetaan päivämäärät ja ajat aiemmin määriteltyjen erottimien mukaisesti (jotta niistä voidaan muodostaa erilaisia Date-olioita)
        var ads = alkuPaiva.split(DATE_SEPARATOR);
        var lds = loppuPaiva.split(DATE_SEPARATOR);
        var ats = alkuAika.split(TIME_SEPARATOR);
        var lts = loppuAika.split(TIME_SEPARATOR);
        
        // KOSKA JAVASCRIPTIN KUUKAUSI SAA ARVOJA VÄLILTÄ 0-11, EI 1-12.
        ads[1]--;
        lds[1]--;
        
        // Muutetaan hajoitetut ajat Date-olioiksi
        // TODO ota DATE_FORMAT huomioon - nyt ei ota, pitää käsin asettaa oikea järjestys alle.
        var alkuDate = new Date(ads[2], ads[1], ads[0], ats[0], ats[1], 0, 0);
        var loppuDate = new Date(lds[2], lds[1], lds[0], lts[0], lts[1], 0, 0);
        
        // Jos alkupäivä on suurempi (1) tai yhtäsuuri (2) kuin loppuaika (= varauksessa on JOKU virhe)
        if (alkuDate >= loppuDate) {
            // Verrataan ensin pelkkiä päivämääriä
            var alkuDateDate = new Date(ads[2], ads[1], ads[0]);
            var loppuDateDate = new Date(lds[2], lds[1], lds[0]);
            
            // (1) Jos alkupäivämäärä on suurempi kuin loppupäivämäärä, niissä on virhe.
            if (alkuDateDate > loppuDateDate) {
                errors.ad = errors.ld = true;
            }
            
            // (2) Jos alkupäivämäärä on yhtäsuuri kuin loppupäivämäärä, virhe on oltava aikakentissä
            else {
                errors.at = errors.lt = true;
            }
            
            // Käydään virhemuuttuja läpi ja asetetaan ERROR_CLASS CSS-luokka niille kentille, joissa on joku virhe.
            // Jokaisen virheen kohdalla, kasvatetaan hasErrors-muuttujaa
            for (e in errors) {
                if (errors[e] === true) {
                    // Lisätään virhe vain jos kenttä ei ole tyhjä TAI jos finalCheck on määritetty
                    if ((FIELDS[e].val()) || (finalCheck)) {
                        FIELDS[e].addClass(ERROR_CLASS);
                        hasErrors++;
                    }
                }
            }
        }
        
        // Jos kentissä on virheitä, palautetaan (bool) false; muuten (bool) true.
        return (hasErrors > 0) ? false : true;
    }