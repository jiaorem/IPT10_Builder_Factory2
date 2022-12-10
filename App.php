<?php
require "vendor/autoload.php";

use DesignPattern\MySQLQueryBuilder;

$builder = new MySQLQueryBuilder();
$query = $builder->select('users', '*')
    ->where(team1='POL' AND team2='RUS')
    ->orderBy('id', 'ASC')
    ->limit(100)
    ->getSQL();