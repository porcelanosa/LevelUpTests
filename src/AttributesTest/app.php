<?php

declare(strict_types = 1);

use App\AttributesTest\TestService;

$strArr = [
    'ррр ',
    ' l48',
    ' l45',
    ' 123 ',
    ' ммм ',
    ' ЛЛЛ ',
    ' RRR ',
    ' nnn ',
    'kkk ',
    ' mmm',
];


$service = new TestService();

$arr = $service->saveToDB($strArr);