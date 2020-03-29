<?php

function createMapper(string $controller)
{
    return function ($value) use ($controller)
    {
        return [
            'controller' => $controller,
            'action' => $value,
        ];
    };
}

function createRoutes(string $controller, array $actions)
{
    return array_map(createMapper($controller), $actions);
}


$actions = [
    require __DIR__ . '/list/heroes.php',
    require __DIR__ . '/list/auth.php',
];

return array_merge(...$actions);
