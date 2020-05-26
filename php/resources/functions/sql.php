<?php

declare(strict_types=1);

/*******************************************************************************
 * Project Group 4 DT167G
 * File: sql.php
 ******************************************************************************/

use Latitude\QueryBuilder\Engine\PostgresEngine;
use Latitude\QueryBuilder\QueryFactory;

function makeQueryFactory(): QueryFactory
{
    return new QueryFactory(new PostgresEngine());
}
