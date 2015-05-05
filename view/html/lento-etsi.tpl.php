<script type="text/javascript" src="<?php print $APP->BASEURL; ?>/view/js/jquery.ui.datepicker-fi.js"></script>
<script type="text/javascript" src="<?php print $APP->BASEURL; ?>/view/js/jquery.ui.timepicker.js"></script>
<script type="text/javascript">
    $(function() {
        $.datepicker.setDefaults($.datepicker.regional["fi"]);
        $("#strFromDate").datepicker();
        $("#strToDate").datepicker();

        $("#frmSubmitButton").click(function(){
            $("#frmSearch").validate();
            $("#frmSearch").submit();
        });
    });
</script>

<?php include('lennot-napit.tpl.php'); ?>

<div class="kp_div">

    <h2>Lentojen etsiminen</h2>


    <div class="tiedot_osio">
        <a href="<?php print $APP->BASEURL; ?>/lennot.php?ID=53&mode=extended" style="font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-zoomin"></span>Laajennettu haku</a><br><br>
        <form action="<?php print $APP->BASEURL; ?>/lennot.php?ID=53&mode=simple" method="POST" id="frmSearch" name="frmSearch">
            <table class="datataulukko" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th colspan="4" class="ul_toprow">Hakuehdot</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Aikaväli</td>
                        <td><input type="text" class="textboxNormal required" style="width:80px;" name="strFromDate" id="strFromDate"/></td>
                        <td> - </td>
                        <td><input type="text" class="textboxNormal required" style="width:80px;" name="strToDate" id="strToDate"/></td>
                    </tr>
                    <tr>
                        <td>Näkymä</td>
                        <td colspan="3">
                            <input type="radio" name="intView" id="intView" class="required" value="1"/>Yleinen<br/>
                            <input type="radio" name="intView" id="intView" class="required" value="2"/>JAR 1.080 Logbook
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="divSpacing">
                <input type="hidden" name="hidSimpleSearch" id="hidSimpleSearch" value="1" />
                <a href="javascript:void(0);" id="frmSubmitButton" title="Submit" style="float:left;margin:0px 0px 0px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-search"></span>Etsi</a>
            </div>
        </form>
    </div>
    <?php
    include 'lento-hakutulokset.tpl.php'
    ?>
</div>