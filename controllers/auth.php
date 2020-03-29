<?php

loadModel('auth');
loadView('json');

function getTokenAction()
{
    $pin = filter_input(INPUT_GET, 'pin', FILTER_SANITIZE_STRING);

    if (!$pin) reject();

    $token = tokenByPin($pin);

    if (!$token) reject();

    response($token);
}
