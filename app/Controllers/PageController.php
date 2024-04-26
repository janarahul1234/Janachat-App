<?php

namespace App\Controllers;

use App\core\Controller;
use App\Middlewares\Auth;

class PageController extends Controller
{
    public $layout = 'masterLayout';

    public function index(): string
    {
        return !Auth::verifyUser() ? $this->redirect('/login') : view('pages/chat');
    }

    public function login(): string
    {
        return Auth::verifyUser() ? $this->redirect('/') : view('pages/login');
    }

    public function register(): string
    {
        return Auth::verifyUser() ? $this->redirect('/') : view('pages/register');
    }
}