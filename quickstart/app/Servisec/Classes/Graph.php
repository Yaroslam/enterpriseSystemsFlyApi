<?php

namespace App\Servisec\Classes;

class Graph
{
    private $v;
    private $graph;
    public $pathes;

    public function __construct($vertices){
        $this->v = $vertices;
        $this->graph = [];
        $this->pathes = [];
    }

    public function addEdgr($u ,$v){
        if(key_exists($u, $this->graph)) {
            array_push($this->graph[$u], $v);
        } else {
            $this->graph[$u] = [];
            array_push($this->graph[$u], $v);
        }
    }

    private function printAllPaths($u, $d, $visited, $path){
        $visited[array_search($u, array_keys($this->graph))] = true;
        $path[] = $u;
        if($u == $d) {
            $this->pathes[] = $path;
        } else {
            foreach ($this->graph[$u] as $i){
                if(!$visited[array_search($i, array_keys($this->graph))]){
                    $this->printAllPaths($i, $d, $visited, $path);
                }
            }
        }
        array_pop($path);
        $visited[array_search($u, array_keys($this->graph))] = false;
    }

    public function printAllPathsInGrapg($s, $d){
        $visited = [];
        for($i=0; $i<$this->v; $i++){
            $visited[$i] = false;
        }
        $path = [];
        $this->printAllPaths($s,$d,$visited,$path);
    }
}
