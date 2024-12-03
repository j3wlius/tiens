<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * The URIs that should be reachable while maintenance mode is on.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Add any routes you want to be accessible during maintenance mode
        // For example: '/health-check'
    ];
}
