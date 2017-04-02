<?php

$sep = DIRECTORY_SEPARATOR;
include_once(dirname(__FILE__) . "{$sep}bootstrap.php");

$clientsList = GetClientsList($db);

/**
 * Gets clients list.
 *
 * @throws \PDOException in case of PDO error.
 *
 * @return array rieltors list.
 */
function GetClientsList(PDO $db)
{
    $sql = $db->prepare('SELECT surname, name, patronymic FROM client');
    try {
        $sql->execute();
    } catch (\PDOException $e) {
        \core\Logger::CatchError("index::GetClientsList: PDOException. Information about error: {$e->errorInfo[2]}");
    }

    return $sql->fetchAll();
}

include_once(VIEW_PATH . 'index.php');
