<?php

$configFile = __DIR__ . '/.env';
if (!file_exists($configFile)) {
    echo "Config file: \"{$configFile}\" does not exist" . PHP_EOL;
    exit(0);
}
preg_match('/^DB_DSN\s*=\s*(.*)$/m', file_get_contents($configFile), $matches);
preg_match('/^DB_USERNAME\s*=\s*(.*)$/m', file_get_contents($configFile), $matchesUsername);
preg_match('/^DB_PASSWORD\s*=\s*(.*)$/m', file_get_contents($configFile), $matchesPassword);
preg_match('/^DB_CHARSET\s*=\s*(.*)$/m', file_get_contents($configFile), $matchesCharset);
if (!$matches || !$matchesUsername || !$matchesPassword || !$matchesCharset){
    echo "Invalid config file";
    exit(0);
}

$SERVERNAME = '';
$PORT = '';
$USERNAME = $matchesUsername[1];
$PASSWORD = $matchesPassword[1];
$CHARSET = $matchesCharset[1];
$DBNAME = '';
if ($matches && isset($matches[1])) {
    $parts = array_map(function ($part) use (&$SERVERNAME, &$DBNAME, &$PORT) {
        $arr = explode('=', $part);
        if ($arr[0] === 'mysql:host') {
            $SERVERNAME = $arr[1];
        } else if ($arr[0] === 'dbname'){
            $DBNAME = $arr[1];
        } else if ($arr[0] === 'port'){
            $PORT = $arr[1];
        }
    }, explode(';', $matches[1]));
}

try {
    $dbh = new PDO("mysql:host=".$SERVERNAME.';port='.$PORT, $USERNAME, $PASSWORD);

    $dbh->exec("CREATE DATABASE IF NOT EXISTS `$DBNAME` DEFAULT CHARACTER SET {$CHARSET} COLLATE {$CHARSET}_unicode_ci;")
    or die(print_r($dbh->errorInfo(), true));
    echo "Database \"$DBNAME\" has been successfully created".PHP_EOL;
} catch (PDOException $e) {
    die("DB ERROR: " . $e->getMessage());
}