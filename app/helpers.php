<?php

use Illuminate\Support\Facades\Route;

function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

function active_class($condition, $activeClass = 'active', $inactiveClass = '')
{
    return $condition ? $activeClass : $inactiveClass;
}

function if_route($route)
{
    return request()->routeIs($route);
}

function if_route_param($param, $value)
{
    $current = request()->route($param);

    if ($current instanceof \Illuminate\Database\Eloquent\Model) {
        $current = $current->getKey();
    }

    return (string) $current === (string) $value;
}

function category_nav_active($category_id)
{
    return active_class(if_route('categories.show') && if_route_param('category', $category_id));
}
