<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>TTT-Aviation :: dokumentaatio</title>
    <link rel="stylesheet" type="text/css" href="../style.css" media="screen" />
</head>
<body>

    <div id="container2">
        <div id="container1">
            <div id="left">
                <h3>Toteutus</h3>

                <h4>Hallinta</h4>
                Tehty käyttöliittymä Markun tietokantapohjan ja omien päätelmien mukaisesti
                <ul>
                    <li>Kelpuutuksien</li>
                    <li>Käyttäjien</li>
                    <li>Koneiden</li>
                    <li>Konetyyppien</li>
                    <li>Kustannuspaikkojen</li>
                    <li>Tilojen</li>
                    <li>Tiedotteiden ja toiminnallisten ohjeiden</li>

                </ul>
                hallintaan ts. perustaminen, muokkaus ja poisto.

                <h4>Käyttäjän työvuoro</h4>
                Muutettu hallinta user_id -pohjaiseksi vrt ennen sessioon yhdistetty. Ei myöskään poisteta työvuoroa sitä lopetettaessa. Opiskelijalle ei anneta
                tätä ominaisuutta käyttöön.

                <h4>Omat tiedot</h4>
                <ul>
                    <li>Kuvan tallentaminen palvelimelle (png tai jpg). Salasanan vaihto onnistuu.</li>
                    <li>Omien kelpuutuksien tallennus, muokkaus ja poisto.</li>
                    <li>Käyttäjien listaus, ja linkkaus viesteille käyttäjän kohdalta. Dokumentaatiosta poiketen listataan 15 käyttäjää per sivu, ja järjestystä voidaan
                    muuttaa käyttäjätunnuksen perusteella (aakkos tai öökkös).</li>
                </ul>
                <h4>Tiedostot</h4>
                Adminilla ja opettajalla on oikeus lisätä ja poistaa tiedostoja ja kansioita. Opiskelija voi vain ladata tiedostot itselleen.

                <h4>Viestit</h4>
                <ul>
                    <li>Viestin kirjoittaminen (html) ja vastaanottajien valinta autofill -kentästä.</li>
                    <li>Viestin lukeminen</li>
                    <li>Viestin merkintä tärkeäksi, normaaliksi</li>
                    <li>Viestin siirto roskakoriin - ei poisteta järjestelmästä, mutta käyttäjä ei enää sitä näe</li>
                    <li>Viestikansion luominen</li>
                    <li>Viesti(e)n siirto toiseen kansioon</li>
                    <li>Tekstiviestin kirjoittaminen ja vastaanottajien valinta</li>
                    <li>Kalenterimerkinnän lisääminen viestiin</li>
                    <li>Jos kalenterimerkinnälle valittiin osallistumisen vahvistus, vastaanottajien etusivulla näytetään vahvistuslomake siihen asti, kun
                        tapahtuman alkamiseen on 24h.</li>
                </ul>

                <h4>Työvuorot</h4>
                <ul>
                    <li>"Ulkoisten" työvuorojen lisääminen</li>
                    <ul>
                        <li>Vuorot (etusivulta syötettävät "sisäiset", sekä täältä syötettävät "ulkoiset") eivät voi olla päälekkäisiä, sillä järjestelmä ei sellaisia hyväksy.</li>
                        <li>Vuoroja ei voi sijoittaa tulevaisuuteen, vaan vain jo tehdyt vuorot voidaan täältä käsin syöttää.</li>
                    </ul>
                    <li>Yhteenvetosivu, johon haetaan sekä ulkoiset että sisäiset vuorot</li>
                    <ul>
                        <li>Sisäisiin vuoroihin haetaan lentoajat lentovarauksista, jos käyttäjä on valvoja tai miehistöön kuuluva kyseisellä lennolla.</li>
                        <li>Lentovarauksen tulee sijoittua vuoron alku- ja loppuajan sisään, jotta järjestelmä tulkitsee sen tietylle vuorolle.</li>
                        <li>Ulkoinen vuoro on aina lento(aikaa).</li>
                    </ul>
                </ul>
            </div>
            <div id="right">
                <h3>Tehtäviä</h3>
                <h4>Viestit / kalenteri</h4>
                <ul>
                    <li>SMS-lähetys ja siihen liittyvät tallennukset (Juha)</li>
                    <li>Kalenterimerkintöjen ilmestyminen myös vastaanottajien kalenteriin (Jari)</li>
                    <ul>
                        <li>Lisätään heti, mikäli osallistumisesta ei vaadita vahvistusta.</li>
                        <li>Lisätään vasta silloin, kun vahvistus on tehty, jos vahvistus vaaditaan.</li>
                    </ul>
                    <li>Kalenterimerkinnän väri, jos tapahtuma lisätään viestin kautta (Jari)</li>
                </ul>
                <h4>Työaika</h4>
                <ul>
                    <li>Yhteenvetoon lentotunnit lennoista (jos sisäinen työvuoro).</li>
                </ul>
                <h4>Omat tiedot</h4>
                <ul>
                    <li>Oma lentokokemus, tarkennuksia TTT:ltä, kuinka saahaan nää ulkoset lennot tähän?? Pitääkö sitä formia laajentaa??</li>
                    <ul>
                        <li>Käsin muokkaus ja syöttö => uusi tietokantataulu (x2) => lentokokemus, johon mahdollinen erotus vrt. lennoista (+ ehkä ulkoisesta) saataviin.</li>
                    </ul>
                </ul>
                <h4>Varauskirja</h4>
                Jarska vie loppuun (omia täsmennyksiä).
                <h4>Lennot</h4>
                Läpikäynti kokonaisuudessaan.

            </div>
        </div>
    </div>
    <div id="footer"></div>

</body>
</html>