<?php

function getFileName()
{
    return __DIR__ . '/../config/auth.json';
}

function createToken()
{
    return md5(rand());
}

function update(string $pin, string $fileName)
{
    $token = createToken();

    $fileContent = [
        'pin' => $pin,
        'token' => $token,
    ];

    file_put_contents($fileName, json_encode($fileContent));

    return $token;
}

function tokenByPin(string $pin)
{
    $fileName = getFileName();

    if (!file_exists($fileName)) touch($fileName);

    $config = json_decode(file_get_contents($fileName), true);

    $invalidPin = $config
        && array_key_exists('pin', $config)
        && $config['pin'] != $pin;

    return $invalidPin ? null : update($pin, $fileName);
}

function isTokenValid(string $token)
{
    $fileName = getFileName();

    if (!file_exists($fileName)) return false;

    $config = json_decode(file_get_contents($fileName), true);

    if (!$config || !array_key_exists('pin', $config)) return false;

    return $token == $config['token'];
}
