<?php

function allHeroes()
{
    $pdo = pdo();

    $query = "
        SELECT
            id,
            name,
            stat,
            img,
            result,
            youtubeId
        FROM heroes
        WHERE deletedAt = 0
    ";

    $state = $pdo->query($query);

    return $state->fetchAll(PDO::FETCH_ASSOC);
}

function postHeroImage($img)
{
    $uploadDir = '/var/www/ld-1/images/';
    $fileName = rand(10000, 99999) . time() . '_' . $img['name'];

    move_uploaded_file($img['tmp_name'], $uploadDir . $fileName);

    return $fileName;
}

function createHero($hero)
{
    $pdo = pdo();

    $query = "
        INSERT INTO heroes
        (`name`, `stat`, `img`, `result`, `youtubeId`, `createdAt`)
        VALUES
        (
            :name,
            :stat,
            :img,
            :result,
            :youtubeId,
            :createdAt
        )
    ";

    try {
        $state = $pdo->prepare($query);
        $state->execute($hero);
    } catch (PDOException $e) {
        return null;
    }

    return $pdo->lastInsertId();
}
