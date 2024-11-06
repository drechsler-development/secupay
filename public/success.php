<?php

use DD\SecuPayOrder;

session_start ();

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config.php';

function RenderOutput ($array, $indent = 0): void {
	echo '<div class="array">';
	foreach ($array as $key => $value) {
		if (is_array ($value)) {
			echo '<div class="key">' . ucfirst ($key) . ':</div>';
			RenderOutput ($value, $indent + 1);
		} else {
			echo '<div><span class="key">' . ucfirst ($key) . ':</span> <span class="value">' . htmlspecialchars ($value) . '</span></div>';
		}
	}
	echo '</div>';
}
?>
	<!DOCTYPE html>
	<html lang="de">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Array Ausgabe</title>
		<style>
		    body {
		        font-family: Arial, sans-serif;
		    }

		    .array {
		        border-left: 2px solid #ccc;
		        padding-left: 10px;
		        margin-top: 10px;
		    }

		    .key {
		        color: #333;
		        font-weight: bold;
		    }

		    .value {
		        color: #555;
		        margin-left: 10px;
		    }
		</style>
	</head>
	<body>
    <?php
    try {

        //Get Transaction
        echo '<h1>Get Transaction</h1>';

        $SecuPay  = new SecuPayOrder(CLIENT_ID, CLIENT_SECRET, true);
        $response = $SecuPay->GetTransaction ($_SESSION['STX_ID'] ?? '');

        if (!empty($response['status'])) {
            $color = $response['status'] == 'approved' || $response['status'] == 'ok' ? 'lightgreen' : 'red';
            echo '<div class="statusResponse ' . $color . '">Status: ' . $response['status'] . '</div>';
        }

        RenderOutput ($response);

        //Capture
        echo '<h1>Capture Transaction</h1>';

        $response = $SecuPay->CaptureTransaction ($_SESSION['STX_ID'] ?? '');

        if (!empty($response['status'])) {
            $color = $response['status'] == 'approved' || $response['status'] == 'ok' ? 'lightgreen' : 'red';
            echo '<div class="statusResponse ' . $color . '">Status: ' . $response['status'] . '</div>';
        }

        RenderOutput ($response);

    } catch (Exception $e) {
        echo '<div class="error">' . $e->getMessage () . '</div>';
    }
    ?>
    </body>
    </html>
