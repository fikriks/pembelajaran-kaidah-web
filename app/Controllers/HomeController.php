<?php

namespace App\Controllers;

class HomeController extends BaseController
{
    public function index()
    {
        // If user is logged in, redirect to dashboard
        if ($this->currentUser) {
            return redirect()->to(site_url('dashboard'));
        }

        // Show landing page for non-logged in users
        return view('auth/login', $this->data);
    }
}