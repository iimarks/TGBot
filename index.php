<?php

include 'TGBot.php';
$TGBot = new TGBot(file_get_contents('php://input'), 'FPAM', $_GET['fpam'], $_GET['token']);
$TGBot->SecTest();
include 'conf.php';
include 'mysql.php';
include 'postgres.php';
include 'commands.php';
