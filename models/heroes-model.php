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
