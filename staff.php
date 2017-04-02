<?php

$sep = DIRECTORY_SEPARATOR;
include_once(dirname(__FILE__) . "{$sep}bootstrap.php");

$staffList = GetStaffList($db);

/**
 * Gets list with info about service staff.
 *
 * @throws \PDOException in case of PDO error.
 *
 * @return array list with staff.
 */
function GetStaffList(PDO $db)
{
    $sql = $db->prepare('SELECT surname, name, patronymic, post FROM staff');
    try {
        $sql->execute();
    } catch (\PDOException $e) {
        \core\Logger::CatchError("index::GetStaffList: PDOException. Information about error: {$e->errorInfo[2]}");
    }

    return $sql->fetchAll();
}

include_once(VIEW_PATH . 'staff.php');
