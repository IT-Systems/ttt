<!-- Mid Body Start -->
<a href="http://www.adiipl.com/opendelight/docs/users.php" title="info" rel="external"><span class="ui-icon ui-icon-info" style="float: right; margin-right: 2px;margin-top:2px;"></span></a>
<a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=70" title="Back"><span class="ui-icon ui-icon-circle-arrow-n ui-icon-back" style="margin-top:2px;"></span></a></div>
<div id="markerdiv"></div>
<div class="<?php print $aMsg[1]; ?>"> 
	<span class="<?php print $aMsg[2]; ?>" style="float: left; margin-right: .3em;"></span>
	<?php print $aMsg[0]?>
</div>
<form id="frmArrRoles" method="post" action="<?php print $_SERVER['PHP_SELF'].'?ID=74';?>" >
<div class="formSpacing">
<table class="tableData" cellspacing="0" cellpadding="0" border="0">
<?php 
foreach($aSortedRolesList as $aRow) { 
?>
<tr style="border-bottom:1px solid #C2E2AC;"> 
<td style="float:left;width:25%;">
  <label><?php print stripslashes($aRow[rolename]); ?></label>
  <input type="hidden" name="names[]" value="<?php print stripslashes($aRow[roleid]); ?>">	
</td>
<td style="float:left;width:60%;">
  <input type="button" onClick="moveElementUp(this.parentNode.parentNode);" value="&uarr;">&nbsp;&nbsp;
  <input type="button" onClick="moveElementDown(this.parentNode.parentNode);" value="&darr;">
</td>
</tr>
<?php } ?>
</table>
<input type="hidden" name="hidArrStatus" id="hidArrStatus" value="1" />
<a href="javascript:void(0);" onclick="javascript:frmSubmit();" title="Submit" style="float:left;margin:8px 0px 4px 0px;font-weight:bold;" class="ui-od-button-with-icon ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowthick-1-e"></span>Submit</a>

</div>
</form>
</div>
<!-- Mid Body End -->
<!-- Main content ends -->

<script type="text/javascript">
function frmSubmit()
{ 	
 	$("#frmArrRoles").submit();
}
function moveElementDown(element){
	
    var elements = element.parentNode.getElementsByTagName(element.nodeName);
    for(i=0;i<elements.length;i++){
        if(elements[i]==element){
            //swap
            var x = (i+1) % (elements.length);
            element.parentNode.insertBefore(element.cloneNode(true), 
                (x>0?elements[x].nextSibling:elements[x]));
            element.parentNode.removeChild(element);
        }
    }
}
function moveElementUp(element){
    var elements = element.parentNode.getElementsByTagName(element.nodeName);
    for(i=0;i<elements.length;i++){
        if(elements[i]==element){
            //swap
            element.parentNode.insertBefore(element.cloneNode(true), 
                    (i-1>=0?elements[i-1]:elements[elements.length-1].nextSibling));
            element.parentNode.removeChild(element);
        }
    }
}
 </script>		