<?php

$sep = DIRECTORY_SEPARATOR;
include_once(dirname(__FILE__) . "{$sep}bootstrap.php");

$banksList = GetBanksList($db);

/**
 * Gets list with info about banks.
 *
 * @throws \PDOException in case of PDO error.
 *
 * @return array list with banks.
 */
function GetBanksList(PDO $db)
{
    $sql = $db->prepare('SELECT name, address, chairmain, share_premium FROM bank');
    try {
        $sql->execute();
    } catch (\PDOException $e) {
        \core\Logger::CatchError("index::GetBankList: PDOException. Information about error: {$e->errorInfo[2]}");
    }

    return $sql->fetchAll();
}

include_once(VIEW_PATH . 'banks.php');
