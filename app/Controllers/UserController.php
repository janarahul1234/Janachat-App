<?php

namespace App\Controllers;

use App\core\Controller;
use App\Models\User;
use App\Models\Chat;
use App\core\utils\JwtToken;
use App\core\utils\Cookies;
use App\Middlewares\Auth;
use App\Helpers\ApiError;

class UserController extends Controller
{
    public function register()
    {
        $data = $this->json->read();
        $fields = ['name', 'username', 'password'];

        if (!allFieldsPresent($fields, $data)) {
            return ApiError::send(
                400,
                'Bad Request: Required fields are missing',
                ['missing_fields' => $fields]
            );
        }

        $user = new User();

        if ($user->where(['user_name' => $data['username']])->first()) {
            return ApiError::send(409, 'Conflict: Username already registered');
        }

        $user->save($data);

        return $this->json->send([
            'status' => 'success',
            'message' => 'User successfully registered'
        ]);
    }

    public function login()
    {
        $data = $this->json->read();
        $fields = ['username', 'password'];

        if (!allFieldsPresent($fields, $data)) {
            return ApiError::send(
                400,
                'Bad Request: Required fields are missing',
                ['missing_fields' => $fields]
            );
        }

        $user = new User();

        $userDetails = $user->where(['user_name' => $data['username']])->first();

        if (!$userDetails) {
            return ApiError::send(
                401,
                'Unauthorized: Invalid credentials',
                [
                    'field' => 'username',
                    'reason' => 'The provided username does not exist'
                ]
            );
        }

        if (!password_verify($data['password'], $userDetails->password)) {
            return ApiError::send(
                401,
                'Unauthorized: Invalid credentials',
                [
                    'field' => 'password',
                    'reason' => 'The provided password is incorrect'
                ]
            );
        }

        $token = JwtToken::create([
            'name' => $userDetails->name,
            'username' => $userDetails->user_name,
            'uuid' => $userDetails->uuid
        ]);

        Cookies::setCookie('Authorization', $token, 1);

        return $this->json->send([
            'status' => 'success',
            'message' => 'User logged in successfully'
        ]);
    }

    public function logout()
    {
        $userDetails = Auth::verifyUser();

        if (!$userDetails) {
            return ApiError::send(401, 'Unauthorized: Invalid credentials');
        }

        Cookies::deleteCookie('Authorization');

        return $this->json->send([
            'status' => 'success',
            'message' => 'User logged out successfully',
        ]);
    }

    public function status()
    {
        $userDetails = Auth::verifyUser();

        if (!$userDetails) {
            return ApiError::send(401, 'Unauthorized: Invalid credentials');
        }

        $user = new User();
        $user->setStatus($userDetails['uuid']);

        return $this->json->send([
            'status' => 'success',
            'message' => 'Status updated...',
        ]);
    }

    public function getUserData($uuid)
    {
        $userDetails = Auth::verifyUser();

        if (!$userDetails) {
            return ApiError::send(401, 'Unauthorized: Invalid credentials');
        }

        $user = new User();
        $result = $user->where(['uuid' => $uuid])->first();

        if (!$result) {
            return ApiError::send(404, 'User is not found');
        }

        if ($result->uuid === $userDetails['uuid']) {
            return ApiError::send(404, 'User is not found');
        }

        return $this->json->send([
            'status' => 'success',
            'data' => [
                'name' => $result->name,
                'username' => $result->user_name,
                'uuid' => $result->uuid,
                'status' => $result->status > time() ? "Online" : "Offline"
            ]
        ]);
    }

    public function getUserInfo()
    {
        $userDetails = Auth::verifyUser();
        $data = $this->json->read();
        $fields = ['name'];

        if (!$userDetails) {
            return ApiError::send(401, 'Unauthorized: Invalid credentials');
        }

        if (!allFieldsPresent($fields, $data)) {
            return ApiError::send(
                400,
                'Bad Request: Required fields are missing',
                ['missing_fields' => $fields]
            );
        }

        $user = new User();
        $result = $user->search($data['name'], $userDetails['uuid']);

        if (!$result) {
            return ApiError::send(404, 'Name is not found');
        }

        return $this->json->send([
            'status' => 'success',
            'data' => $result
        ]);
    }

    public function getFriends()
    {
        $userDetails = Auth::verifyUser();

        if (!$userDetails) {
            return ApiError::send(401, 'Unauthorized: Invalid credentials');
        }

        $user = new User();

        $result = $user->where(['uuid' => $userDetails['uuid']])->first();
        $friends = $user->parseArray($result->friends);
        $friendsData = [];

        if (count($friends) === 0) {
            return ApiError::send(404, 'No friends found');
        }
        
        foreach ($friends as $friend) {
            $data = $user->where(['uuid' => $friend])->first();
            $message = $this->lastMessage($userDetails['uuid'], $friend);

            $friendsData[] = [
                'name' => $data->name,
                'uuid' => $data->uuid,
                'message' => $message['text'],
                'timestamp' => $message['timestamp'],
                'type' => $message['type']
            ];
        }

        return $this->json->send([
            'status' => 'success',
            'friends' => $friendsData
        ]);
    }

    private function lastMessage(string $to, string $from): array
    {
        $chat = new Chat();
        $result = $chat->getMessages($to, $from);

        if (is_null($result)) {
            return [
                'text' => 'No message are available',
                'type' => 'none',
                'timestamp' => 'none'
            ];
        }

        if (end($result)->to_user === $to) {
            $type = 'outgoing';
        } else if (end($result)->from_user === $to) {
            $type = 'incoming';
        }

        return [
            'text' => end($result)->message,
            'type' => $type,
            'timestamp' => end($result)->created_at
        ];
    }
}
