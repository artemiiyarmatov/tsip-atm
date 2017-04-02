<?php

/* @var \core\Site $site site module. */
/* @var array $banksList list of banks. */

$site->RenderHeader();
$site->StartContent();

/* Flashes */
if (true === $site->HasFlashes()) {
    $site->GetFlash();
}

/* Content */

$site->RenderLabel('Banks.', [
    'class' => 'data-table',
    'style' => 'font-size: 32px;',
]);

$columnNames = [
    'Name',
    'Address',
    'Chairmain',
    'Share Premium',
];

$site->RenderTable($columnNames, $banksList, [
    'class' => 'data-table',
    'style' => 'background-color: green;',
]);

$site->EndContent();
$site->RenderFooter();
