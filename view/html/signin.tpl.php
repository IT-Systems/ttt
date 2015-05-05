<!-- Main content starts -->
<div id="kirjautumiscontainer">
<form id="frmLogin" method="post" action="<?php print $APP->BASEURL?>/sign.php?ID=2">

    <div class="ui-widget ui-corner-bl ui-corner-br ennen_kirjautumista_laatikko">

        <div style="margin:0px auto;width:500px;">

            <h1>TTT-Aviation - Kirjautuminen</h1>

            <div class="ui-widget-content ui-corner-all" style="overflow:hidden;padding:1em;">
            <div id="kirjautumislaatikko_inner">
                <div style="margin-top:5px;color:red;"><?php print $sErrMsg;?></div>
                <p><label>Käyttäjätunnus</label><br/><input type="text" id="txtUsername" name="txtUsername" style="float:left;clear:both;width:200px;" class="required" tabindex="1"/></p>
                <p style="float:left;clear:both;margin-top:10px;"><label>Salasana</label><br/><input type="password" id="txtPassword" name="txtPassword" style="float:left;clear:both;width:200px;" class="required" tabindex="2"/></p>
                <p style="float:left;clear:both;margin-top:10px;">
                    <a href="JavaScript:void(0);" class="ui-od-button-with-icon ui-state-default ui-corner-all" onclick="javascript:submitContactForm();" tabindex="3">
                        <span class="ui-icon ui-icon-key"></span>Kirjaudu sisään
                    </a>
                </p>
                <p style="float:left;clear:both;margin-top:10px;"><a href="<?php print $APP->BASEURL?>/sign.php?ID=3" title="Forgot Password?">Unohditko salasanasi?</a></p>
                </div>
            </div>

        </div>

    </div>

    <input type="hidden" name="hidStatus" id="hidStatus" value="1" />

</form>
</div>
<script type="text/javascript">

function submitContactForm()
{
    $("#frmLogin").validate();
    $("#frmLogin").submit();
}
$(function() {
   $('input').keyup(function(event) {
      if (event.keyCode == 13) {
          submitContactForm();
      } 
   });
});
</script>
<!-- Main content ends -->