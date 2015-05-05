<?php
$return_arr = array();
//build array of results
foreach ($kayttajat as $row) {
    $row_array["value"] = $row["nimi"];
    $row_array["name"] = $row["nimi"] . ": " . $row["puhelin"];
    $row_array["id"] = $row["userid"];
    array_push($return_arr,$row_array);
}
//echo JSON to page
$response = json_encode($return_arr);
echo $response;
?>