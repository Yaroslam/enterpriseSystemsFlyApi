<?php

function IsSessionExpired($todayDate, $sessionDropDate){
    return $todayDate > $sessionDropDate;
}
