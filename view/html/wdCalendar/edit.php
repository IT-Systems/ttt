<?php
include_once("php/dbconfig.php");
include_once("php/functions.php");
function getCalendarByRange($id) {
    try {
        $db = new DBConnection();
        $db->getConnection();
        $sql = "SELECT jq.*, t.nimi from `its_jqcalendar` jq LEFT JOIN `its_tilat` t ON t.id = jq.tila_id WHERE jq.`id` = " . $id;
        $handle = mysql_query($sql);
        //echo $sql;
        $row = mysql_fetch_object($handle);
    }catch(Exception $e) {
    }
    return $row;
}
function haeTilat() {
    $db = new DBConnection();
    $db->getConnection();
    $sql = "SELECT * FROM `its_tilat`";
    $stmt = mysql_query($sql);
    $tilat = array();
    while ($row = mysql_fetch_assoc($stmt)) {
        $tilat[] = $row;
    }
    return $tilat;
}
function haeSyssi(){
    $db = new DBConnection();
    $db->getConnection();
    $sql = "SELECT baseurl FROM `its_od_sys`";
    $stmt = mysql_query($sql);
    $row = mysql_fetch_object($stmt);
    return $row;
}
$DOGO = haeSyssi();

if($_GET["id"]) {
    $event = getCalendarByRange($_GET["id"]);
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
            <title>Kalenterin tiedot</title>
            <link href="<?php print $DOGO->baseurl;?>/view/html/wdCalendar/css/main.css" rel="stylesheet" type="text/css" />
            <link href="<?php print $DOGO->baseurl;?>/view/html/wdCalendar/css/dp.css" rel="stylesheet" />
            <link href="<?php print $DOGO->baseurl;?>/view/html/wdCalendar/css/dropdown.css" rel="stylesheet" />
            <link href="<?php print $DOGO->baseurl;?>/view/html/wdCalendar/css/colorselect.css" rel="stylesheet" />

            <script src="<?php print $DOGO->baseurl;?>/view/html/wdCalendar/src/jquery.js" type="text/javascript"></script>
            <script src="<?php print $DOGO->baseurl;?>/view/html/wdCalendar/src/Plugins/Common.js" type="text/javascript"></script>
            <script src="<?php print $DOGO->baseurl;?>/view/html/wdCalendar/src/Plugins/jquery.form.js" type="text/javascript"></script>
            <script src="<?php print $DOGO->baseurl;?>/view/html/wdCalendar/src/Plugins/jquery.validate.js" type="text/javascript"></script>
            <script src="<?php print $DOGO->baseurl;?>/view/html/wdCalendar/src/Plugins/datepicker_lang_US.js" type="text/javascript"></script>
            <script src="<?php print $DOGO->baseurl;?>/view/html/wdCalendar/src/Plugins/jquery.datepicker.js" type="text/javascript"></script>
            <script src="<?php print $DOGO->baseurl;?>/view/html/wdCalendar/src/Plugins/jquery.dropdown.js" type="text/javascript"></script>
            <script src="<?php print $DOGO->baseurl;?>/view/html/wdCalendar/src/Plugins/jquery.colorselect.js" type="text/javascript"></script>

            <script type="text/javascript">
                if (!DateAdd || typeof (DateDiff) != "function") {
                    var DateAdd = function(interval, number, idate) {
                        number = parseInt(number);
                        var date;
                        if (typeof (idate) == "string") {
                            date = idate.split(/\D/);
                            eval("var date = new Date(" + date.join(",") + ")");
                        }
                        if (typeof (idate) == "object") {
                            date = new Date(idate.toString());
                        }
                        switch (interval) {
                            case "y": date.setFullYear(date.getFullYear() + number); break;
                            case "m": date.setMonth(date.getMonth() + number); break;
                            case "d": date.setDate(date.getDate() + number); break;
                            case "w": date.setDate(date.getDate() + 7 * number); break;
                            case "h": date.setHours(date.getHours() + number); break;
                            case "n": date.setMinutes(date.getMinutes() + number); break;
                            case "s": date.setSeconds(date.getSeconds() + number); break;
                            case "l": date.setMilliseconds(date.getMilliseconds() + number); break;
                        }
                        return date;
                    }
                }
                function getHM(date)
                {
                    var hour =date.getHours();
                    var minute= date.getMinutes();
                    var ret= (hour>9?hour:"0"+hour)+":"+(minute>9?minute:"0"+minute) ;
                    return ret;
                }
                $(document).ready(function() {
                    //debugger;
                    var DATA_FEED_URL = "<?php print $DOGO->baseurl;?>/view/html/wdCalendar/php/datafeed.php";
                    var arrT = [];
                    var tt = "{0}:{1}";
                    for (var i = 0; i < 24; i++) {
                        arrT.push({ text: StrFormat(tt, [i >= 10 ? i : "0" + i, "00"]) }, { text: StrFormat(tt, [i >= 10 ? i : "0" + i, "30"]) });
                    }
                    $("#timezone").val(new Date().getTimezoneOffset()/60 * -1);
                    $("#stparttime").dropdown({
                        dropheight: 200,
                        dropwidth:60,
                        selectedchange: function() { },
                        items: arrT
                    });
                    $("#etparttime").dropdown({
                        dropheight: 200,
                        dropwidth:60,
                        selectedchange: function() { },
                        items: arrT
                    });
                    var check = $("#IsAllDayEvent").click(function(e) {
                        if (this.checked) {
                            $("#stparttime").val("00:00").hide();
                            $("#etparttime").val("00:00").hide();
                        }
                        else {
                            var d = new Date();
                            var p = 60 - d.getMinutes();
                            if (p > 30) p = p - 30;
                            d = DateAdd("n", p, d);
                            $("#stparttime").val(getHM(d)).show();
                            $("#etparttime").val(getHM(DateAdd("h", 1, d))).show();
                        }
                    });
                    if (check[0].checked) {
                        $("#stparttime").val("00:00").hide();
                        $("#etparttime").val("00:00").hide();
                    }
                    $("#Savebtn").click(function() { $("#fmEdit").submit(); });
                    $("#Closebtn").click(function() { CloseModelWindow(); });
                    $("#Deletebtn").click(function() {
                        if (confirm("Haluatko varmasti poistaa tämän merkinnän?")) {
                            var param = [{ "name": "calendarId", value: 8}];
                            $.post(DATA_FEED_URL + "?method=remove&user_id=<?php echo $USER->ID; ?>",
                            param,
                            function(data){
                                if (data.IsSuccess) {
                                    alert(data.Msg);
                                    CloseModelWindow(null,true);
                                }
                                else {
                                    alert("Tapahtui virhe.\r\n" + data.Msg);
                                }
                            }
                            ,"json");
                        }
                    });

                    $("#stpartdate,#etpartdate").datepicker({ picker: "<button class='calpick'></button>"});
                    var cv =$("#colorvalue").val() ;
                    if(cv=="")
                    {
                        cv="-1";
                    }
                    $("#calendarcolor").colorselect({ title: "Color", index: cv, hiddenid: "colorvalue" });
                    //to define parameters of ajaxform
                    var options = {
                        beforeSubmit: function() {
                            return true;
                        },
                        dataType: "json",
                        success: function(data) {
                            alert(data.Msg);
                            if (data.IsSuccess) {
                                CloseModelWindow(null,true);
                            }
                        }
                    };
                    $.validator.addMethod("date", function(value, element) {
                        var arrs = value.split(i18n.datepicker.dateformat.separator);
                        var year = arrs[i18n.datepicker.dateformat.year_index];
                        var month = arrs[i18n.datepicker.dateformat.month_index];
                        var day = arrs[i18n.datepicker.dateformat.day_index];
                        var standvalue = [year,month,day].join("-");
                        return this.optional(element) || /^(?:(?:1[6-9]|[2-9]\d)?\d{2}[\/\-\.](?:0?[1,3-9]|1[0-2])[\/\-\.](?:29|30))(?: (?:0?\d|1\d|2[0-3])\:(?:0?\d|[1-5]\d)\:(?:0?\d|[1-5]\d)(?: \d{1,3})?)?$|^(?:(?:1[6-9]|[2-9]\d)?\d{2}[\/\-\.](?:0?[1,3,5,7,8]|1[02])[\/\-\.]31)(?: (?:0?\d|1\d|2[0-3])\:(?:0?\d|[1-5]\d)\:(?:0?\d|[1-5]\d)(?: \d{1,3})?)?$|^(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])[\/\-\.]0?2[\/\-\.]29)(?: (?:0?\d|1\d|2[0-3])\:(?:0?\d|[1-5]\d)\:(?:0?\d|[1-5]\d)(?: \d{1,3})?)?$|^(?:(?:16|[2468][048]|[3579][26])00[\/\-\.]0?2[\/\-\.]29)(?: (?:0?\d|1\d|2[0-3])\:(?:0?\d|[1-5]\d)\:(?:0?\d|[1-5]\d)(?: \d{1,3})?)?$|^(?:(?:1[6-9]|[2-9]\d)?\d{2}[\/\-\.](?:0?[1-9]|1[0-2])[\/\-\.](?:0?[1-9]|1\d|2[0-8]))(?: (?:0?\d|1\d|2[0-3])\:(?:0?\d|[1-5]\d)\:(?:0?\d|[1-5]\d)(?:\d{1,3})?)?$/.test(standvalue);
                    }, "Annoit päivämäärän väärässä muodossa.");
                    $.validator.addMethod("time", function(value, element) {
                        return this.optional(element) || /^([0-1]?[0-9]|2[0-3]):([0-5][0-9])$/.test(value);
                    }, "Annoit ajan väärässä muodossa.");
                    $.validator.addMethod("safe", function(value, element) {
                        return this.optional(element) || /^[^$\<\>]+$/.test(value);
                    }, "$<> not allowed");
                    $("#fmEdit").validate({
                        submitHandler: function(form) { $("#fmEdit").ajaxSubmit(options); },
                        errorElement: "div",
                        errorClass: "cusErrorPanel",
                        errorPlacement: function(error, element) {
                            showerror(error, element);
                        }
                    });
                    function showerror(error, target) {
                        var pos = target.position();
                        var height = target.height();
                        var newpos = { left: pos.left, top: pos.top + height + 2 }
                        var form = $("#fmEdit");
                        error.appendTo(form).css(newpos);
                    }
                });
            </script>
            <style type="text/css">
                .calpick     {
                    width:16px;
                    height:26px;
                    border:none;
                    cursor:pointer;
                    background:url("sample-css/cal.gif") no-repeat center 2px;
                    margin-left:-22px;
                }
            </style>
    </head>
    <body>
        <div>
            <div class="toolBotton">
                <a id="Savebtn" class="imgbtn" href="javascript:void(0);">
                    <span class="Save"  title="Tallenna tapahtuma">Tallenna(<u>S</u>)
                    </span>
                </a>
                <?php if(isset($event)) { ?>
                <a id="Deletebtn" class="imgbtn" href="javascript:void(0);">
                    <span class="Delete" title="Peruuta tapahtuma">Peruuta(<u>D</u>)
                    </span>
                </a>
                    <?php } ?>
                <a id="Closebtn" class="imgbtn" href="javascript:void(0);">
                    <span class="Close" title="Sulje ikkuna" >Sulje
                    </span></a>
                </a>
            </div>
            <div style="clear: both">
            </div>
            <div class="infocontainer">
                <form action="<?php print $DOGO->baseurl;?>/view/html/wdCalendar/php/datafeed.php?method=adddetails<?php echo isset($event)?"&id=".$event->Id:""; ?>&user_id=<?php echo (int) htmlentities($_GET['user_id'], ENT_COMPAT);?>" class="fform" id="fmEdit" method="post">
                    <label>
                        <span>                        *Otsikko:
                        </span>
                        <div id="calendarcolor">
                        </div>
                        <input MaxLength="200" class="required safe" id="Subject" name="Subject" style="width:85%;" type="text" value="<?php echo isset($event)?$event->Subject:"" ?>" />
                        <input id="colorvalue" name="colorvalue" type="hidden" value="<?php echo isset($event)?$event->Color:"" ?>" />
                    </label>
                    <label>
                        <span>*Ajankohta:
                        </span>
                        <div>
                            <?php if(isset($event)) {
                                $sarr = explode(" ", php2JsTime(mySql2PhpTime($event->StartTime)));
                                $earr = explode(" ", php2JsTime(mySql2PhpTime($event->EndTime)));
                            }?>
                            <input MaxLength="10" class="required date" id="stpartdate" name="stpartdate" style="padding-left:2px;width:90px;" type="text" value="<?php echo isset($event)?$sarr[0]:""; ?>" />
                            <input MaxLength="5" class="required time" id="stparttime" name="stparttime" style="width:40px;" type="text" value="<?php echo isset($event)?$sarr[1]:""; ?>" /> -
                            <input MaxLength="10" class="required date" id="etpartdate" name="etpartdate" style="padding-left:2px;width:90px;" type="text" value="<?php echo isset($event)?$earr[0]:""; ?>" />
                            <input MaxLength="50" class="required time" id="etparttime" name="etparttime" style="width:40px;" type="text" value="<?php echo isset($event)?$earr[1]:""; ?>" />
                            <label class="checkp">
                                <input id="IsAllDayEvent" name="IsAllDayEvent" type="checkbox" value="1" <?php if(isset($event)&&$event->IsAllDayEvent!=0) {
    echo "checked";
} ?>/>          Koko päivän tapahtuma                      
                            </label>
                        </div>
                    </label>
                    <label>
                        <span>Paikka:</span>
                        <select name="Location">
                            <option value="">--Valitse--</option>
<?php
$tilat = haeTilat();
foreach ($tilat as $tila) {
    $sel = ($event->tila_id == $tila["id"]) ? " selected" : "";
?>
                            <option value="<?php print $tila["id"]; ?>"<?php print $sel; ?>><?php print $tila["nimi"]; ?></option>
<?php
} ?>
                        </select>
                        <br/>
<!--                        <input MaxLength="200" id="Location" name="Location" style="width:95%;" type="text" value="<?php echo isset($event)?$event->nimi:""; ?>" />-->
                    </label>
                    <label>
                        <span>Kuvaus:</span>
                        <textarea cols="20" id="Description" name="Description" rows="2" style="width:95%; height:70px">
<?php echo isset($event)?$event->Description:""; ?>
                        </textarea>
                    </label>
                    <input id="timezone" name="timezone" type="hidden" value="" />
                </form>
            </div>
        </div>
    </body>
</html>