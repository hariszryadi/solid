<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FrontEndController extends Controller
{
    /**
     * URL Api.
     */
    protected $api_url;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->api_url = env('API_URL');
    }

    /**
     * Index page.
     *
     * @return view
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * Login page.
     *
     * @return view
     */
    public function login()
    {
        return view('frontend.login');
    }

    /**
     * Register page.
     *
     * @return view
     */
    public function register()
    {
        $response = Http::get($this->api_url . '/organization')->json();
        return view('frontend.register', ['response' => $response]);
    }
}
