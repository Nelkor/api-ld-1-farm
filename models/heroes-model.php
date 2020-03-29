<?php

function allHeroes()
{
    $pdo = pdo();

    $query = "
        SELECT
            *
        FROM heroes
        WHERE deletedAt = 0
    ";

    $state = $pdo->query($query);

    return $state->fetchAll(PDO::FETCH_ASSOC);
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
