<?php
namespace Worldcup\App\Test\Teams\Results;

require_once __DIR__ .'/../../../worldcup.php';

use Worldcup\App\Worldcup;
use Worldcup\App\Controller\Results\TeamsSummary;

$worldcup = new Worldcup(new TeamsSummary);

header('Content-type: application/json');

echo $worldcup->get_results();
