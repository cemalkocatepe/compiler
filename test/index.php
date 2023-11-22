<?php

include_once __DIR__ . '/../vendor/autoload.php';

use Boyner\Compailer\DB\DBConnection;
use Boyner\Compailer\PageAndWidgetGenerate;

/* Insert DB */

$dbProd = new DBConnection();
$dbProdConnection = $dbProd->connection('cms-prod');

/* Read DB */
$dbMp = new DBConnection();
$dbMpConnection = $dbMp->connection('cms-mp');

$dd = new PageAndWidgetGenerate;
print_r($dd->importPagesAndWidgets($dbMpConnection, $dbProdConnection));
