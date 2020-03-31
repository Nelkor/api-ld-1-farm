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

function removeHeroImage(string $img)
{
    unlink("/var/www/ld-1/images/$img");
}

function imgByHeroId(int $id)
{
    $pdo = pdo();

    $query = "
        SELECT img
        FROM heroes
        WHERE id = $id
    ";

    $state = $pdo->query($query);

    return $state->fetch(PDO::FETCH_ASSOC)['img'];
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

function updateHero($hero)
{
    $pdo = pdo();

    $query = "
        REPLACE INTO heroes
        (`id`, `name`, `stat`, `img`, `result`, `youtubeId`, `updatedAt`)
        VALUES
        (
            :id,
            :name,
            :stat,
            :img,
            :result,
            :youtubeId,
            :updatedAt
        )
    ";

    try {
        $state = $pdo->prepare($query);
        $state->execute($hero);
    } catch (PDOException $e) {
        return false;
    }

    return true;
}

function removeHero($id)
{
    $pdo = pdo();
    $now = time();

    $pdo->query("UPDATE heroes SET deletedAt = $now WHERE id = $id");
}
