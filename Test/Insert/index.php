<?php
namespace Worldcup\App\Test\Teams\Results;

require_once __DIR__ .'/../../worldcup.php';

use Worldcup\App\Worldcup;
use Worldcup\App\Controller\Results\Matches;

$worldcup = new Worldcup(new Matches);

header("Content-Type: text/html");

if ($worldcup->save_results())
	echo "<div>You have successfully retrieved the data and placed them into the database.</div>
		<a href='../Teams/Results/' target='_blank'>Summary teams results</a><br>
		<a href='../Matches/' target='_blank'>Matches sorted by temperature from the hottest</a>";
else
	echo 'An error occurred and the data is not stored into the database.';
