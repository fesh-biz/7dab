<?php

namespace Tests\Browser\Extensions;

trait RouteNamesExtension
{
    public function routeByName(string $name): string
    {
        $routes = [
            'home' => '/',
            'login' => '/auth/login',
            'register' => '/auth/register',
            'forgotPassword' => '/auth/forgot-password',
            'resetPassword' => '/auth/reset-password'
        ];

        return $routes[$name];
    }
}
