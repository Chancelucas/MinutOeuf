<?php

namespace App\Models;

use App\Core\BaseModel;

class UserModel extends BaseModel {
    protected function getCollectionName(): string {
        return 'users';
    }

    public function findByEmail(string $email): ?array {
        return $this->findOne(['email' => $email]);
    }

    public function createUser(string $email, string $password): bool {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        return $this->create([
            'email' => $email,
            'password' => $hashedPassword,
            'role' => 'admin',
            'createdAt' => new \MongoDB\BSON\UTCDateTime()
        ]);
    }

    public function verifyPassword(string $email, string $password): bool {
        $user = $this->findByEmail($email);
        if (!$user) {
            return false;
        }
        return password_verify($password, $user['password']);
    }
}
