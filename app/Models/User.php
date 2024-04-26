<?php

namespace App\Models;

use App\core\Model;

class User extends Model
{
    protected string $table = 'users';

    public function save(array $data): void
    {
        $this->create([
            'name' => $data['name'],
            'user_name' => $data['username'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'uuid' => generateUuid(),
            'created_at' => timestamp(),
            'updated_at' => timestamp()
        ]);
    }

    public function search(string $name, string $uuid): mixed
    {
        return $this->query("
            SELECT name, user_name, uuid FROM {$this->table} 
            WHERE name = :name AND uuid != :uuid
        ", ['name' => $name, 'uuid' => $uuid]);
    }

    public function setStatus(string $uuid): void
    {
        $this->where(['uuid' => $uuid])->update(['status' => time() + 10]);
    }

    public function addFriend(string $to, string $From): void
    {
        $user_from = $this->where(['uuid' => $From])->first();
        $Friends = $this->parseArray($user_from->friends);

        if (!in_array($to, $Friends)) {
            $Friends[] = $to;
            $this->where(['uuid' => $From])->update(['friends' => $this->parseString($Friends), 'updated_at' => timestamp()]);
        }
    }
}
