<?php
namespace App\core;

use MongoDB\Client;

class Database {
    private static ?Client $client = null;
    private static ?\MongoDB\Database $db = null;

    public static function db(): \MongoDB\Database {
        if (!self::$db) {
            $uri = getenv('MONGO_URI') ?: 'mongodb://root:rootpass@localhost:27017';
            $dbName = getenv('MONGO_DB') ?: 'appdb';
            self::$client = new Client($uri, ['retryWrites' => true]);
            self::$db = self::$client->selectDatabase($dbName);
        }
        return self::$db;
    }

    public static function collection(string $name): \MongoDB\Collection {
        return self::db()->selectCollection($name);
    }
}