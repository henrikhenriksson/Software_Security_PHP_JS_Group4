<?php declare(strict_types=1);

use Latitude\QueryBuilder\Engine\PostgresEngine;
use Latitude\QueryBuilder\QueryFactory;

function makeQueryFactory(): QueryFactory
{
    return new QueryFactory(new PostgresEngine());
}
