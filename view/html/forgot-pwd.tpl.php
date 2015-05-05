<?php $iTabIndex = 0; ?>
<!-- Main content starts -->
<div id="kirjautumiscontainer">

<?php if ($sMsg == '') { ?>
    <form id="forgetPwd" action="./sign.php?ID=4" method="post">
        <div class="ui-widget ui-corner-bl ui-corner-br ennen_kirjautumista_laatikko" >
            <div style="margin:0px auto;width:500px;">
                <h1>Unohditko salasanasi?</h1>
                <h3>Syötä alle sähköpostiosoitteesi</h3>
                <div class="ui-widget-content ui-corner-all" style="overflow:hidden;padding:1em;">
                    <p><label>Sähköposti<span class="mandatory">*</span></label><br/><input type="text" id="txtEmailId" name="txtEmailId" style="float:left;clear:both;width:200px;" class="required" tabindex="<?php print $iTabIndex + 1; ?>"/></p>



                    <p style="float:left;clear:both;margin-top:10px;">
                        <a class="ui-od-button-with-icon ui-state-default ui-corner-all" onclick="javascript:submitPwdForm();" tabindex="<?php print $iTabIndex + 1; ?>">
                            <span class="ui-icon ui-icon-arrowthick-1-e"></span>Lähetä
                        </a>

                    </p>
                </div>
                <br /><br /><br />
            </div>
        </div>
        <input id="hidPwd" name="hidPwd" size="30" type="hidden" value="2" />
    </form>
<?php } else { ?>
    <div class="ui-widget ui-corner-bl ui-corner-br ennen_kirjautumista_laatikko">
        <div style="margin:0px auto;width:500px;">
            <h1>Unohditko salasanasi?</h1>
            <h3>Syötä alle sähköpostiosoitteesi</h3>
            <div class="ui-widget-content ui-corner-all" style="overflow:hidden;padding:1em;">
                <?php print '<p style="color:#000000;font-weight:normal;line-height:18px;" >' . $sMsg . '</p>'; ?>
            </div>
            <br /><br /><br />
        </div>
    </div>
<?php } ?>
</div>
<script type="text/javascript">
    function submitPwdForm()
    {
        $("#forgetPwd").validate();
        $("#forgetPwd").submit();
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


