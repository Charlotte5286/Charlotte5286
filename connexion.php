<?php
function connexobjet($base,$param)
{
    include_once($param.".inc.php");
    $idcom = new mysqli(HOST,USER,PASS,$base,PORT);
    return $idcom;
}
?>
