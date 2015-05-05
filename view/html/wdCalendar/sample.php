    <link href="<?php print $APP->BASEURL;?>/view/html/wdCalendar/css/dailog.css" rel="stylesheet" type="text/css" />
    <link href="<?php print $APP->BASEURL;?>/view/html/wdCalendar/css/calendar.css" rel="stylesheet" type="text/css" /> 
    <link href="<?php print $APP->BASEURL;?>/view/html/wdCalendar/css/dp.css" rel="stylesheet" type="text/css" />   
    <link href="<?php print $APP->BASEURL;?>/view/html/wdCalendar/css/alert.css" rel="stylesheet" type="text/css" /> 
    <link href="<?php print $APP->BASEURL;?>/view/html/wdCalendar/css/main.css" rel="stylesheet" type="text/css" /> 
    

    <script src="<?php print $APP->BASEURL;?>/view/html/wdCalendar/src/jquery.js" type="text/javascript"></script>  
    
    <script src="<?php print $APP->BASEURL;?>/view/html/wdCalendar/src/Plugins/Common.js" type="text/javascript"></script>    
    <script src="<?php print $APP->BASEURL;?>/view/html/wdCalendar/src/Plugins/datepicker_lang_US.js" type="text/javascript"></script>     
    <script src="<?php print $APP->BASEURL;?>/view/html/wdCalendar/src/Plugins/jquery.datepicker.js" type="text/javascript"></script>

    <script src="<?php print $APP->BASEURL;?>/view/html/wdCalendar/src/Plugins/jquery.alert.js" type="text/javascript"></script>    
    <script src="<?php print $APP->BASEURL;?>/view/html/wdCalendar/src/Plugins/jquery.ifrmdailog.js" defer="defer" type="text/javascript"></script>
    <script src="<?php print $APP->BASEURL;?>/view/html/wdCalendar/src/Plugins/wdCalendar_lang_US.js" type="text/javascript"></script>    
    <script src="<?php print $APP->BASEURL;?>/view/html/wdCalendar/src/Plugins/jquery.calendar.js" type="text/javascript"></script>   
    
    <script type="text/javascript">
        $(document).ready(function() {     
           var view="week";          
           
            var DATA_FEED_URL = "<?php print $APP->BASEURL;?>/view/html/wdCalendar/php/datafeed.php";
            var op = {
                view: view,
                theme:3,
                showday: new Date(),
                EditCmdhandler:Edit,
                DeleteCmdhandler:Delete,
                ViewCmdhandler:View,    
                onWeekOrMonthToDay:wtd,
                onBeforeRequestData: cal_beforerequest,
                onAfterRequestData: cal_afterrequest,
                onRequestDataError: cal_onerror, 
                autoload:true,
                url: DATA_FEED_URL + "?method=list&user_id=<?php echo $USER->ID; ?>",  
                quickAddUrl: DATA_FEED_URL + "?method=add&user_id=<?php echo $USER->ID; ?>", 
                quickUpdateUrl: DATA_FEED_URL + "?method=update&user_id=<?php echo $USER->ID; ?>",
                quickDeleteUrl: DATA_FEED_URL + "?method=remove&user_id=<?php echo $USER->ID; ?>"        
            };			
            var $dv = $("#calhead");
            var _MH = document.documentElement.clientHeight;
            var dvH = $dv.height() + 2;
            op.height = _MH - dvH;
            op.eventItems =[];

            var p = $("#gridcontainer").bcalendar(op).BcalGetOp();
            if (p && p.datestrshow) {
                $("#txtdatetimeshow").text(p.datestrshow);
            }
            $("#caltoolbar").noSelect();
            
            $("#hdtxtshow").datepicker({ picker: "#txtdatetimeshow", showtarget: $("#txtdatetimeshow"),
            onReturn:function(r){                          
                            var p = $("#gridcontainer").gotoDate(r).BcalGetOp();
                            if (p && p.datestrshow) {
                                $("#txtdatetimeshow").text(p.datestrshow);
                            }
                     } 
            });
            function cal_beforerequest(type)
            {
                var t="Ladataan tietoja...";
                switch(type)
                {
                    case 1:
                        t="Ladataan tietoja...";
                        break;
                    case 2:                      
                    case 3:  
                    case 4:    
                        t="Pyyntöä käsitellään...";                                   
                        break;
                }
                $("#errorpannel").hide();
                $("#loadingpannel").html(t).show();    
            }
            function cal_afterrequest(type, data)
            {
                switch(type)
                {
                    case 1:
                        $("#loadingpannel").hide();
                        break;
                    case 2:
                    case 3:
                    case 4:
                        $("#loadingpannel").html(data.Msg);
                        window.setTimeout(function(){ $("#loadingpannel").hide();},2000);
                    break;
                }              
               
            }
            function cal_onerror(type,data)
            {
                $("#errorpannel").html(data.Msg);
                $("#errorpannel").show();
            }
            function Edit(data)
            {
               var eurl="<?php print $APP->BASEURL;?>/view/html/wdCalendar/edit.php?id={0}&start={2}&end={3}&isallday={4}&title={1}&user_id=<?php echo $USER->ID; ?>";   
                if(data)
                {
                    var url = StrFormat(eurl,data);
                    OpenModelWindow(url,{ width: 600, height: 400, caption:"Muokkaa tapahtumaa",onclose:function(){
                       $("#gridcontainer").reload();
                    }});
                }
            }    
            function View(data)
            {
                // void             
            }    
            function Delete(data,callback)
            {           
                
                $.alerts.okButton="Ok";  
                $.alerts.cancelButton="Cancel";  
                hiConfirm("Haluatko varmasti poistaa merkinnän?", 'Confirm',function(r){ r && callback(0);});           
            }
            function wtd(p)
            {
               if (p && p.datestrshow) {
                    $("#txtdatetimeshow").text(p.datestrshow);
                }
                $("#caltoolbar div.fcurrent").each(function() {
                    $(this).removeClass("fcurrent");
                })
                $("#showdaybtn").addClass("fcurrent");
            }
            //to show day view
            $("#showdaybtn").click(function(e) {
                //document.location.href="#day";
                $("#caltoolbar div.fcurrent").each(function() {
                    $(this).removeClass("fcurrent");
                })
                $(this).addClass("fcurrent");
                var p = $("#gridcontainer").swtichView("day").BcalGetOp();
                if (p && p.datestrshow) {
                    $("#txtdatetimeshow").text(p.datestrshow);
                }
            });
            //to show week view
            $("#showweekbtn").click(function(e) {
                //document.location.href="#week";
                $("#caltoolbar div.fcurrent").each(function() {
                    $(this).removeClass("fcurrent");
                })
                $(this).addClass("fcurrent");
                var p = $("#gridcontainer").swtichView("week").BcalGetOp();
                if (p && p.datestrshow) {
                    $("#txtdatetimeshow").text(p.datestrshow);
                }

            });
            //to show month view
            $("#showmonthbtn").click(function(e) {
                //document.location.href="#month";
                $("#caltoolbar div.fcurrent").each(function() {
                    $(this).removeClass("fcurrent");
                })
                $(this).addClass("fcurrent");
                var p = $("#gridcontainer").swtichView("month").BcalGetOp();
                if (p && p.datestrshow) {
                    $("#txtdatetimeshow").text(p.datestrshow);
                }
            });
            
            $("#showreflashbtn").click(function(e){
                $("#gridcontainer").reload();
            });
            
            //Add a new event
            $("#faddbtn").click(function(e) {
                var url ="<?php print $APP->BASEURL;?>/view/html/wdCalendar/edit.php?user_id=<?php echo $USER->ID; ?>";
                OpenModelWindow(url,{ width: 500, height: 400, caption: "Luo uusi tapahtuma"});
            });
            //go to today
            $("#showtodaybtn").click(function(e) {
                var p = $("#gridcontainer").gotoDate().BcalGetOp();
                if (p && p.datestrshow) {
                    $("#txtdatetimeshow").text(p.datestrshow);
                }


            });
            //previous date range
            $("#sfprevbtn").click(function(e) {
                var p = $("#gridcontainer").previousRange().BcalGetOp();
                if (p && p.datestrshow) {
                    $("#txtdatetimeshow").text(p.datestrshow);
                }

            });
            //next date range
            $("#sfnextbtn").click(function(e) {
                var p = $("#gridcontainer").nextRange().BcalGetOp();
                if (p && p.datestrshow) {
                    $("#txtdatetimeshow").text(p.datestrshow);
                }
            });
            
        });
    </script>    
</head>
<body>
    <div style="float:left;">

      <div id="calhead" style="padding-left:1px;padding-right:1px;">          
            <div class="cHead">
            <div id="loadingpannel" class="ptogtitle loadicon" style="display: none;">Ladataan...</div>
             <div id="errorpannel" class="ptogtitle loaderror" style="display: none;">Tietojen lataus epäonnistui. Yritä myöhemmin uudelleen.</div>
            </div>          
            
            <div id="caltoolbar" class="ctoolbar">
              <div id="faddbtn" class="fbutton">
                <div><span title='Click to Create New Event' class="addcal">

                Uusi tapahtuma                
                </span></div>
            </div>
            <div class="btnseparator"></div>
             <div id="showtodaybtn" class="fbutton">
                <div><span title='Click to back to today ' class="showtoday">
                Palaa tähän päivään</span></div>
            </div>
              <div class="btnseparator"></div>

            <div id="showdaybtn" class="fbutton">
                <div><span title='Day' class="showdayview">Päivänäkymä</span></div>
            </div>
              <div  id="showweekbtn" class="fbutton fcurrent">
                <div><span title='Week' class="showweekview">Viikkonäkymä</span></div>
            </div>
              <div  id="showmonthbtn" class="fbutton">
                <div><span title='Month' class="showmonthview">Kuukausinäkymä</span></div>

            </div>
            <div class="btnseparator"></div>
              <div  id="showreflashbtn" class="fbutton">
                <div><span title='Refresh view' class="showdayflash">Lataa uudelleen</span></div>
                </div>
             <div class="btnseparator"></div>
            <div id="sfprevbtn" title="Prev"  class="fbutton">
              <span class="fprev"></span>

            </div>
            <div id="sfnextbtn" title="Next" class="fbutton">
                <span class="fnext"></span>
            </div>
            <div class="fshowdatep fbutton">
                    <div>
                        <input type="hidden" name="txtshow" id="hdtxtshow" />
                        <span id="txtdatetimeshow"></span>

                    </div>
            </div>
            
            <div class="clear"></div>
            </div>
      </div>
      <div style="padding:1px;">

        <div class="t1 chromeColor">
            &nbsp;</div>
        <div class="t2 chromeColor">
            &nbsp;</div>
        <div id="dvCalMain" class="calmain printborder">
            <div id="gridcontainer" style="overflow-y: visible;">
            </div>
        </div>
        <div class="t2 chromeColor">

            &nbsp;</div>
        <div class="t1 chromeColor">
            &nbsp;
        </div>   
        </div>
     
  </div>
    
</body>
</html>
