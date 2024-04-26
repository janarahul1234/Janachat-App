<?php

namespace App\Controllers;

use App\core\Controller;
use App\Models\Chat;
use App\Models\User;
use App\Middlewares\Auth;
use App\Helpers\ApiError;
use App\core\utils\Encryption;

class ChatController extends Controller
{
    public function sendMessage()
    {
        $encryption = new Encryption($_ENV['ENCRYPTION_KET'], $_ENV['ENCRYPTION_AlGO']);
        $userDetails = Auth::verifyUser();

        if (!$userDetails) {
            return ApiError::send(401, 'Unauthorized');
        }

        $data = $this->json->read();
        $fields = ['uuid', 'message'];

        if (!allFieldsPresent($fields, $data)) {
            return ApiError::send(
                400,
                'Bad Request: Required fields are missing',
                ['missing_fields' => $fields]
            );
        }

        $data['to_user'] = $userDetails['uuid'];

        $chat = new Chat();
        $chat->save($data);

        $user = new User();
        $fromUserDetails = $user->where(['uuid' => $data['uuid']])->first();

        if (!$fromUserDetails) {
            return ApiError::send(404, 'User are not exist');
        }

        $user->addFriend($userDetails['uuid'], $data['uuid']);
        $user->addFriend($data['uuid'], $userDetails['uuid']);

        return $this->json->send([
            'status' => 'success',
            'message' => 'Message successfully Send it'
        ]);
    }

    public function getMessages()
    {
        $userDetails = Auth::verifyUser();

        if (!$userDetails) {
            return ApiError::send(401, 'Unauthorized');
        }

        $data = $this->json->read();
        $fields = ['uuid'];

        if (!allFieldsPresent($fields, $data)) {
            return ApiError::send(
                400,
                'Bad Request: Required fields are missing',
                ['missing_fields' => $fields]
            );
        }

        $chat = new Chat();
        $result = $chat->getMessages($userDetails['uuid'], $data['uuid']);

        if (empty($result)) {
            return ApiError::send(404, 'Messages is not found');
        }

        $messages = [];

        foreach ($result as $message) {
            if ($message->to_user === $userDetails['uuid']) {
                $type = 'outgoing';
            } else if ($message->from_user === $userDetails['uuid']) {
                $type = 'incoming';
            }

            $messages[] = [
                'id' => $message->_id,
                'message' => $message->message,
                'timestamp' => $message->created_at,
                'type' => $type
            ];
        }

        return $this->json->send([
            'status' => 'success',
            'messages' => $messages
        ]);
    }
}
