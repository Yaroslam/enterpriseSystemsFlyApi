<?php

use App\Servisec\Classes\Graph;

$graph = ['AC'=> ['B', 'C'],
         'B'=> ['C', 'D'],
         'C'=> ['D'],
         'D'=> ['C', "F"],
         'E'=> ['F'],
         'F'=> ['C']];



$g = new Graph(count($graph));
foreach (array_keys($graph) as $k){ //$k = $i
    foreach ($graph[$k] as $e){
        $g->addEdgr($k, $e);
    }
}

var_dump($g->pathes);
