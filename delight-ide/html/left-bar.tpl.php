<!-- Left Bar Start -->
<div class="ui-corner-tr ui-bg-white" id="leftcontentbar" style="float:left;margin-top:10px;width:16%;padding: 5px 10px;">
<div class="navigation">
<ul>
<li class="ui-menu" style="margin-bottom:6px;"><a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=1" style="color:#3a8104;" title="Dashboard">Dashboard</a></li>
<li class="ui-menu" style="margin-bottom:6px;"><a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=20" style="color:#3a8104;" title="Controllers and Events">Controllers and Events</a></li>
<li><span class="ui-menu">Model</span>
<ul>
<li class="ui-submenu"><a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=30" title="Application Classes">Application Classes</a></li>
<li class="ui-submenu"><a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=35" title="Script Includes">Script Includes</a></li>
<li class="ui-submenu"><a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=50" title="Configuration Variables">Configuration Variables</a></li>
</ul>
</li>
<li><span class="ui-menu">View</span>
<ul>
<li class="ui-submenu"><a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=40&VFT=html" title="View Page Parts">View Page Parts</a></li>
<li class="ui-submenu"><a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=90" title="View Page Variables">View Page Variables</a></li>
<li class="ui-submenu"><a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=40&VFT=js" title="JavaScript Files">JavaScript Files</a></li>
<li class="ui-submenu"><a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=40&VFT=css" title="CSS Files">CSS Files</a></li>
</ul>
</li>
<li><span class="ui-menu">Users</span>
<ul>
<li class="ui-submenu"><a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=63" title="Manage Users">Manage Users</a></li>
<li class="ui-submenu"><a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=70" title="Manage Roles">Manage Roles</a></li>
</ul>
</li>
<li class="ui-menu" style="margin-bottom:6px;"><a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=80" style="color:#3a8104;" title="Lifestream">Lifestream</a></li>
<li><span class="ui-menu"><a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=100" style="color:#3a8104;" title="Application Settings">Application Settings</a></span>
</li>
<li class="ui-menu"><a href="<?php print $APP->BASEURL;?>/delight-ide/index.php?ID=104" style="color:#3a8104;" title="Change Password">Change Password</a></li>
</ul>
</div>
</div>
<!-- Left Bar End -->
<div class="ui-corner-tl" id="middlecontentbar" style="margin:10px 0px 0px 10px;height:auto;width:81%;float:left;background-color:#ffffff;padding-bottom:10px;padding-right:0px;">
<div style="float:left;width:98.9%;margin-left:10px;margin-right:10px;padding:0px 0px 0px 0px;">
<div style="margin-top:5px;font-size:11px;"><strong>You are here: </strong><?php print $APP->PAGEVARS['breadcrumb'];?></div>
<div class="ui-widget-header" style="margin-top:10px;font-size:16px;padding:5px;margin-right:7px;">
<?php print $APP->PAGEVARS['headertext'];?>
