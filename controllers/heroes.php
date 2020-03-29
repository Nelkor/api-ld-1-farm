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
    $token = $_SERVER['HTTP_TOKEN'] ?? null;

    if (!$token || !isTokenValid($token)) reject('token');

    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $stat = filter_input(INPUT_POST, 'stat', FILTER_VALIDATE_INT);
    $img = filter_input(INPUT_POST, 'img', FILTER_SANITIZE_STRING);
    $result = filter_input(INPUT_POST, 'result', FILTER_VALIDATE_INT);
    $youtubeId = filter_input(INPUT_POST, 'youtubeId', FILTER_SANITIZE_STRING);

    $name = trim($name);
    $result = +$result;

    $ready = $name && $stat && $img;

    if (!$ready) reject('params');

    $hero = [
        'name' => $name,
        'stat' => $stat,
        'img' => $img,
        'result' => $result,
        'youtubeId' => $youtubeId,
        'createdAt' => time(),
    ];

    $id = createHero($hero);

    if (!$id) reject('db');

    response($id);
}
