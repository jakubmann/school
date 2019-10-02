<?php
include 'router.php';
include 'lib/db.php';

Db::connect('localhost', 'root', '', 'school');

$router = new Router();