<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\core\Database;
use MongoDB\BSON\UTCDateTime;

$col = Database::collection('users');

/**
 * Si la colección ya existe,
 * borra la colección y vuelve a crear todo desde cero.
 */
$col->drop();
$col->createIndex(['username' => 1], ['unique' => true]);

$now = new UTCDateTime();

$users = [
  [
    'username'     => 'admin',
    'passwordHash' => password_hash('Admin#123', PASSWORD_BCRYPT),
    'role'         => 'admin',
    'createdAt'    => $now,
    'updatedAt'    => $now,
    'lastLogin'    => null
  ],
  [
    'username'     => 'recruiter',
    'passwordHash' => password_hash('Recruit#123', PASSWORD_BCRYPT),
    'role'         => 'recruiter',
    'createdAt'    => $now,
    'updatedAt'    => $now,
    'lastLogin'    => null
  ],
];

$insertResult = $col->insertMany($users);

echo "Usuarios insertados: " . $insertResult->getInsertedCount() . PHP_EOL;
foreach ($insertResult->getInsertedIds() as $i => $id) {
  echo " - {$users[$i]['username']} => {$id}" . PHP_EOL;
}
