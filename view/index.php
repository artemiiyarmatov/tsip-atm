<?php

/* @var \core\Site $site site module. */
/* @var array $clientList list of clients. */

$site->RenderHeader();
$site->StartContent();

/* Flashes */
if (true === $site->HasFlashes()) {
    $site->GetFlash();
}

/* Content */

$site->RenderLabel('Clients of site.', [
    'class' => 'data-table',
    'style' => 'font-size: 32px;',
]);

$columnNames = [
    'Surname',
    'Name',
    'Patronymic',
];

$site->RenderTable($columnNames, $clientsList, [
    'class' => 'data-table',
    'style' => 'background-color: green;',
]);

$site->EndContent();
$site->RenderFooter();
