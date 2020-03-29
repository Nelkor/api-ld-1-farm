<?php

// request => action
$actions = [
    'all' => 'getAllHeroes',
    'add' => 'addHero',
    'update' => 'updateHero',
    'delete' => 'removeHero',
];

return createRoutes('heroes', $actions);
