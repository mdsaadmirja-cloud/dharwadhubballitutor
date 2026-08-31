<?php

class Middleware {
    private $middlewares = [];

    public function add($middleware) {
        $this->middlewares[] = $middleware;
    }

    public function handle($request) {
        foreach ($this->middlewares as $middleware) {
            $middleware($request);
        }
    }
}


?>