<?php

namespace App\Core;

abstract class Middleware {
    /**
     * Handle the request before it reaches the controller.
     * Must return true to proceed, or handle redirection/exit.
     */
    abstract public function handle();
}