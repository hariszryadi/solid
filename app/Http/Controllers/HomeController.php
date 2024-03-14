<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $categoryArr = [];
        $seriesArr = [];

        $parse = Carbon::now()->format('Y-m-d');
        $date = Carbon::parse($parse)->isoFormat('dddd, D MMMM YYYY');
        $categories = Category::orderBy('id')->get();
        foreach ($categories as $key => $category) {
            $transaction = Transaction::where('category_id', $category->id)->whereDate('created_at', $parse)->sum('weight');

            array_push($categoryArr, $category->name);
            array_push($seriesArr, floatval($transaction));
        }
        $error = 5;
        $transactions = Transaction::with('account', 'category', 'organization')->orderBy('created_at')->limit(5)->get();

        return view('home', [
            'error' => $error,
            'sum' => array_sum($seriesArr),
            'categories' => $categoryArr,
            'series' => $seriesArr,
            'date' => $date,
            'transactions' => $transactions
        ]);
    }
}
