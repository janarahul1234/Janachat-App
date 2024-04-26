<?php

namespace App\core\utils;

class Encryption
{
    private string $key = '';
    private string $cipher_algo = '';
    private int $options = 0;

    public function __construct(string $key, string $cipher_algo)
    {
        $this->key = $key;
        $this->cipher_algo = $cipher_algo;
    }

    public function encrypt(string $message): array
    {
        $iv = $this->generateIv($this->cipher_algo);
        $encrypted = openssl_encrypt(
            $message,
            $this->cipher_algo,
            $this->key,
            $this->options,
            $iv
        );

        return ['public_key' => bin2hex($iv), 'encrypted' => base64_encode($encrypted)];
    }

    public function decrypt(string $public_key, string $message): string
    {
        $decrypted = openssl_decrypt(
            base64_decode($message),
            $this->cipher_algo,
            $this->key,
            $this->options,
            hex2bin($public_key)
        );

        return $decrypted;
    }

    public function generateIv(string $cipher_algo): string
    {
        $iv_length = openssl_cipher_iv_length($cipher_algo);
        $iv = openssl_random_pseudo_bytes($iv_length);
        return $iv;
    }
}
