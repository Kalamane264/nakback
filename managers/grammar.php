<?php
class Grammar{
  static function ev_ho_nap($dateString){
    $tol = str_replace(". ", "/", $dateString);
    $tol = str_replace(".", "", $tol);
    $time = strtotime($tol);
    $date = getdate($time);
    return $date["year"].". év ". $date["mon"].". hó ".$date["mday"].".";
  }
}
?>