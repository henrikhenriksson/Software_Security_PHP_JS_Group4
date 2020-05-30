<?php

// This maps internal function names to sink types that we don’t want to end up there

return [
'exec' => [['shell']],
'file_put_contents' => [['shell']],
'fopen' => [['shell']],
'passthru' => [['shell']],
'pcntl_exec' => [['shell']],
'printr' => [['html', 'user_secret', 'system_secret']],
'PDO::prepare' => [['sql']],
'PDO::query' => [['sql']],
'PDO::exec' => [['sql']],
'shell_exec' => [['shell']],
'system' => [['shell']],
];
