<?php

loadModel('heroes');
loadModel('auth');

loadView('json');

function getAllHeroesAction()
{
    $heroes = allHeroes();

    response($heroes);
}

function addHeroAction()
{
    echo '<pre>';
    print_r($_SERVER);
    echo '</pre>';

    $token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING);

    if (!$token || !isTokenValid($token)) reject();

    response('Add hero');
}
