<?php

// request => action
$actions = [
    'auth' => 'getToken',
];

return createRoutes('auth', $actions);
