<?php
if ($_POST['requestctrlid'])
{
	if(count($aEventDetails) > 0)
	{
	   $sSelectEvent = '<label>Default Event <span>(Choose the Event that will be instantiated by default after signing in)</span></label>
	   <select name="selEvent" id="selEvent" class="dropdown required" style="float:left;clear:both;width:138px;">
	   <option value="">Select Event</option>';
	   foreach($aEventDetails as $aRow)
	   {
	   		$sSelectEvent .= '<option value="'.$aRow['eventid'].'">'.$aRow['eventname'].'</option> .';
	   }
	   $sSelectEvent .= '</select>';
	   print $sSelectEvent;
	}
	else 
	{
	   $sSelectEvent = '<label>Default Event <span>(Choose the Event that will be instantiated by default after signing in)</span></label>
	   <select name="selEvent" id="selEvent" class="dropdown required" style="float:left;clear:both;width:138px;">
	   <option value="">Select Event</option></select>';	
	   print $sSelectEvent;	       
	}
}
else 
{
    $sSelectEvent = '<label>Default Event <span>(Choose the Event that will be instantiated by default after signing in)</span></label>
    <select name="selEvent" id="selEvent" class="dropdown required" style="float:left;clear:both;width:138px;">
    <option value="">Select Event</option></select>';		
    print $sSelectEvent;       
}
?>
