<?php
function createGraph($v){
    $graphNodes = [];
    foreach ($v as $vv){
        if(key_exists($vv[0], $graphNodes)){
            if(!in_array($vv[1], $graphNodes[$vv[0]]) && !in_array($vv[0], $graphNodes[$vv[1]])){
                $graphNodes[$vv[0]][] = $vv[1];
            }
        } else {
            $graphNodes[$vv[0]] = [];
            $graphNodes[$vv[0]][] = $vv[1];
        }
    }
    return $graphNodes;
}
