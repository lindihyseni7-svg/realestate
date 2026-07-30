<?php
require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../Bootstrap.php";

use App\Models\User;
use Illuminate\Database\Capsule\Manager as Capsule;

// Create a new user
$user = new User();
$user->first_name = 'John';
$user->last_name = 'Doe';
$user->country = 'USA';
$user->city = 'New York';
$user->address = '123 Main St';
$user->phone = '1234567890';
$user->email = 'john.doe@example.com';
$user->password = password_hash('password123', PASSWORD_BCRYPT); // It's a good practice to hash passwords
$user->status = 1;
$user->save();

echo "User created successfully with ID: " . $user->id;
?>
