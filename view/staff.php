<?php

/* @var \core\Site $site site module. */
/* @var array $staffList list of staff. */

$site->RenderHeader();
$site->StartContent();

/* Flashes */
if (true === $site->HasFlashes()) {
    $site->GetFlash();
}

/* Content */

$site->RenderLabel('Service staff.', [
    'class' => 'data-table',
    'style' => 'font-size: 32px;',
]);

$columnNames = [
    'Surname',
    'Name',
    'Patronymic',
    'Post',
];

$site->RenderTable($columnNames, $staffList, [
    'class' => 'data-table',
    'style' => 'background-color: green;',
]);

$site->EndContent();
$site->RenderFooter();
