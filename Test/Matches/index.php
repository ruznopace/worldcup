<?php
namespace Worldcup\App\Test\Matches;

require_once __DIR__ .'/../../worldcup.php';

use Worldcup\App\Worldcup;
use Worldcup\App\Controller\Results\Matches;

$worldcup = new Worldcup(new Matches);

header('Content-type: application/json');

echo $worldcup->get_results();
