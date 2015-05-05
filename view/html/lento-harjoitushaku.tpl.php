<?php
$return_arr = array();
foreach ($harjoitukset as $row) {
    $row_array["optionValue"] = $row["id"];
    $row_array["optionDisplay"] = $row["nimi"] . " - " . $row["otsikko"];
    $row_array["optionSelected"] = (!empty($row["valittu"])) ? 1 : 0;
    array_push($return_arr,$row_array);
}
//echo JSON to page
$response = json_encode($return_arr);
print $response;
?>