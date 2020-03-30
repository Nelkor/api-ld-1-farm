<?php

loadModel('heroes');
loadModel('auth');

loadView('json');

function isValidImg($img)
{
    return array_key_exists('name', $img)
        && is_string($img['name'])
        && array_key_exists('type', $img)
        && $img['type'] == 'image/jpeg';
}

function getAllHeroesAction()
{
    $heroes = allHeroes();

    response($heroes);
}

function addHeroAction()
{
    // Проверка авторизации
    $token = $_SERVER['HTTP_TOKEN'] ?? null;

    if (!$token || !isTokenValid($token)) reject('token');

    // Получение входных значений
    $imgFile = $_FILES['img'] ?? [];

    if (!isValidImg($imgFile)) reject('img');

    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $stat = filter_input(INPUT_POST, 'stat', FILTER_VALIDATE_INT);
    $result = filter_input(INPUT_POST, 'result', FILTER_VALIDATE_INT);
    $youtubeId = filter_input(INPUT_POST, 'youtubeId', FILTER_SANITIZE_STRING);

    $name = trim($name);
    $result = +$result;
    $stat = +$stat;

    if (!$name || !$stat) reject('params');

    // Запись данных
    $img = postHeroImage($imgFile);

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
