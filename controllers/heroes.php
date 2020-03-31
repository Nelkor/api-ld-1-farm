<?php

loadModel('heroes');
loadModel('auth');

loadView('json');

function checkAuth()
{
    $token = $_SERVER['HTTP_TOKEN'] ?? null;

    return $token && isTokenValid($token);
}

function heroParams()
{
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $stat = filter_input(INPUT_POST, 'stat', FILTER_VALIDATE_INT);
    $img = filter_input(INPUT_POST, 'img', FILTER_SANITIZE_STRING);
    $result = filter_input(INPUT_POST, 'result', FILTER_VALIDATE_INT);
    $youtubeId = filter_input(INPUT_POST, 'youtubeId', FILTER_SANITIZE_STRING);

    $id = +$id;
    $name = trim($name);
    $result = +$result;
    $stat = +$stat;

    return [
        'id' => $id,
        'name' => $name,
        'stat' => $stat,
        'img' => $img,
        'result' => $result,
        'youtubeId' => $youtubeId,
    ];
}

function isValidImg($img)
{
    return array_key_exists('name', $img)
        && is_string($img['name'])
        && array_key_exists('type', $img)
        && $img['type'] == 'image/jpeg';
}

function prepareHero($hero)
{
    $hero['id'] = +$hero['id'];
    $hero['stat'] = +$hero['stat'];
    $hero['result'] = +$hero['result'];

    return $hero;
}

function getAllHeroesAction()
{
    $heroes = allHeroes();

    response(array_map('prepareHero', $heroes));
}

function addHeroAction()
{
    if (!checkAuth()) reject('token');

    // Получение входных значений
    $imgFile = $_FILES['img'] ?? [];

    if (!isValidImg($imgFile)) reject('img');

    $params = heroParams();

    unset($params['id']);
    unset($params['img']);

    if (!$params['name'] || !$params['stat']) reject('params');

    // Запись данных
    $img = postHeroImage($imgFile);

    array_merge($params, ['img' => $img, 'createdAt' => time()]);

    $hero = array_merge($params, ['img' => $img, 'createdAt' => time()]);

    $id = createHero($hero);

    if (!$id) reject('db');

    response(['id' => +$id, 'img' => $img]);
}

function updateHeroAction()
{
    if (!checkAuth()) reject('token');

    // Получение входных значений
    $imgFile = $_FILES['newImg'] ?? [];

    $newImg = isValidImg($imgFile);

    $params = heroParams();

    $enoughParams = $params['name']
        && $params['stat']
        && ($newImg || $params['img']);

    if (!$enoughParams) reject('params');

    // Запись данных
    if ($newImg) {
        removeHeroImage(imgByHeroId($params['id']));

        $img = postHeroImage($imgFile);

        unset($params['img']);

        $hero = array_merge($params, ['img' => $img, 'updatedAt' => time()]);
    } else {
        $img = $params['img'];

        $hero = array_merge($params, ['updatedAt' => time()]);
    }

    $result = updateHero($hero);

    if ($result) response($img);
    else reject('db');
}

function removeHeroAction()
{
    if (!checkAuth()) reject('token');
}
