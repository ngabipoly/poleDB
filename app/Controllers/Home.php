<?php

namespace App\Controllers;

class Home extends BaseController
{
protected $user;
protected $session;

public function __construct()
{
    // Initialize session
    $this->session = session();
    $this->user = $this->session->get('userData');
}

public function index()
{
    
    // Redirect if user is not logged in
    if (!$this->user) {
        return redirect()->to('/');
    }

    $data = [
        'page' => 'Home',
        'user' => $this->user,
    ];

    return view('front-end', $data);
}

}
