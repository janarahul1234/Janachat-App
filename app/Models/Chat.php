<?php

namespace App\Models;

use App\core\Model;
use App\core\utils\Encryption;

class Chat extends Model
{
    protected $table = 'chats';

    public function save(array $data): void
    {
        $encryption = new Encryption($_ENV['ENCRYPTION_KET'], $_ENV['ENCRYPTION_AlGO']);
        $message = $encryption->encrypt($data['message']);

        $this->create([
            'to_user' => $data['to_user'],
            'from_user' => $data['uuid'],
            'message' => $message['encrypted'],
            'public_key' => $message['public_key'],
            'created_at' => timestamp(),
            'updated_at' => timestamp()
        ]);
    }

    public function getMessages(string $to, string $from): mixed
    {
        $encryption = new Encryption($_ENV['ENCRYPTION_KET'], $_ENV['ENCRYPTION_AlGO']);

        $result = $this->query(
            "SELECT * FROM {$this->table} 
            WHERE (to_user = :toUuid AND from_user = :fromUuid) OR (to_user = :fromUuid AND from_user = :toUuid)",
            ['toUuid' => $to, 'fromUuid' => $from]
        );

        foreach ($result ?? [] as $key => $value) {
            $result[$key]->message = $encryption->decrypt($result[$key]->public_key, $result[$key]->message);
        }

        return $result;
    }
}
