<?php

include_once ("dbconn.php");
include_once ("table_location.php");
include_once ("tableRide.php");
    $tablelocation= new tablelocation();
    $dbconn = new dbconn();

$pickupLocation = $_POST['pickupLocation'];
$dropLocation = $_POST['dropLocation'];
$cabType = $_POST['cabType'];
$luggage = isset($_POST['luggage']) ? $_POST['luggage'] : 0;

$result= $tablelocation->allLocation($dbconn->conn); 
if($result->num_rows>0){
    while($row=$result->fetch_assoc()){
    
        if($row['name']==$pickupLocation){
        $pickupKM=$row['distance'];
        }elseif($row['name']==$dropLocation){
            $dropKM=$row['distance'];
      }
    }
}

$distance = abs($pickupKM - $dropKM);


if ($luggage != null)
{
    if($luggage==0){
        $luggageFare = 0;
    }
    elseif ($luggage <= 10){
        $luggageFare = 50;
    }
    elseif ($luggage <= 20){
        $luggageFare = 100;
    }
    elseif ($luggage > 20){
        $luggageFare = 200;
    }
}
else
{
    $luggage=0;
    $luggageFare = 0;
}


if ($cabType == 'CedMicro')
{
    $fixedFare = 50;
    $km10 = 13.50;
    $km50 = 12.00;
    $km100 = 10.20;
    $kmAbove100 = 8.50;

    if ($distance <= 10)
    {
        $km10fare = $distance * $km10;
        $totalFare = $km10fare;

    }
    elseif ($distance <= 60)
    {
        $km10fare = 10 * $km10;
        $km50fare = ($distance - 10) * $km50;
        $totalFare = $km10fare + $km50fare;

    }
    elseif ($distance <= 160)
    {
        $km10fare = 10 * $km10;
        $km50fare = 50 * $km50;
        $km100fare = ($distance - 60) * $km100;
        $totalFare = $km10fare + $km50fare + $km100fare;

    }
    elseif ($distance > 160)
    {
        $km10fare = 10 * $km10;
        $km50fare = 50 * $km50;
        $km100fare = 100 * $km100;
        $kmAbove100fare = ($distance - 160) * $kmAbove100;
        $totalFare = $km10fare + $km50fare + $km100fare + $kmAbove100fare;
    }
    $finalFare = $totalFare + $fixedFare;

}
elseif ($cabType == 'CedMini')
{
    $fixedFare = 150;
    $km10 = 14.50;
    $km50 = 13.00;
    $km100 = 11.20;
    $kmAbove100 = 9.50;

    if ($distance <= 10)
    {
        $km10fare = $distance * $km10;
        $totalFare = $km10fare;

    }
    elseif ($distance <= 60)
    {
        $km10fare = 10 * $km10;
        $km50fare = ($distance - 10) * $km50;
        $totalFare = $km10fare + $km50fare;

    }
    elseif ($distance <= 160)
    {
        $km10fare = 10 * $km10;
        $km50fare = 50 * $km50;
        $km100fare = ($distance - 60) * $km100;
        $totalFare = $km10fare + $km50fare + $km100fare;

    }
    elseif ($distance > 160)
    {
        $km10fare = 10 * $km10;
        $km50fare = 50 * $km50;
        $km100fare = 100 * $km100;
        $kmAbove100fare = ($distance - 160) * $kmAbove100;
        $totalFare = $km10fare + $km50fare + $km100fare + $kmAbove100fare;
    }
     $finalFare = $totalFare + $fixedFare + $luggageFare;

}
elseif ($cabType == 'CedRoyal')
{
    $fixedFare = 200;
    $km10 = 15.50;
    $km50 = 14.00;
    $km100 = 12.20;
    $kmAbove100 = 10.50;

    if ($distance <= 10)
    {
        $km10fare = $distance * $km10;
        $totalFare = $km10fare;

    }
    elseif ($distance <= 60)
    {
        $km10fare = 10 * $km10;
        $km50fare = ($distance - 10) * $km50;
        $totalFare = $km10fare + $km50fare;

    }
    elseif ($distance <= 160)
    {
        $km10fare = 10 * $km10;
        $km50fare = 50 * $km50;
        $km100fare = ($distance - 60) * $km100;
        $totalFare = $km10fare + $km50fare + $km100fare;

    }
    elseif ($distance > 160)
    {
        $km10fare = 10 * $km10;
        $km50fare = 50 * $km50;
        $km100fare = 100 * $km100;
        $kmAbove100fare = ($distance - 160) * $kmAbove100;
        $totalFare = $km10fare + $km50fare + $km100fare + $kmAbove100fare;
    }
     $finalFare = $totalFare + $fixedFare + $luggageFare;

}
elseif ($cabType == 'CedSUV')
{
    $fixedFare = 250;
    $km10 = 16.50;
    $km50 = 15.00;
    $km100 = 13.20;
    $kmAbove100 = 11.50;
    $luggageFareSUV = 2 * $luggageFare;

    if ($distance <= 10)
    {
        $km10fare = $distance * $km10;
        $totalFare = $km10fare;

    }
    elseif ($distance <= 60)
    {
        $km10fare = 10 * $km10;
        $km50fare = ($distance - 10) * $km50;
        $totalFare = $km10fare + $km50fare;

    }
    elseif ($distance <= 160)
    {
        $km10fare = 10 * $km10;
        $km50fare = 50 * $km50;
        $km100fare = ($distance - 60) * $km100;
        $totalFare = $km10fare + $km50fare + $km100fare;

    }
    elseif ($distance > 160)
    {
        $km10fare = 10 * $km10;
        $km50fare = 50 * $km50;
        $km100fare = 100 * $km100;
        $kmAbove100fare = ($distance - 160) * $kmAbove100;
        $totalFare = $km10fare + $km50fare + $km100fare + $kmAbove100fare;
    }
     $finalFare = $totalFare + $fixedFare + $luggageFareSUV;
}
 $rideInfo=array('distance'=>$distance,'fare'=>$finalFare,'luggage'=>$luggage);
echo json_encode($rideInfo);