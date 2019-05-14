<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomClasses\ParseOptions;

class PagesContoller extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index']]);
    }

    /**
     * Show the welcome page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.index');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        return view('pages.home');
    }

    /**
     * Show the customers management interface.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function customers(Request $request)
    {
        // If pre-set options have been passed, return view with parsed options
        $parseObj = new ParseOptions($request);
        $options = $parseObj->getOptions();

        // Return view with options
        return view('pages.customers')->with('options', $options);
    }

    /**
     * Show the orders management interface.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function orders(Request $request)
    {
        // If pre-set options have been passed, return view with parsed options
        $parseObj = new ParseOptions($request);
        $options = $parseObj->getOptions();

        // Return view with options
        return view('pages.orders')->with('options', $options);
    }

    /**
     * Show the products management interface.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function products(Request $request)
    {
        // If pre-set options have been passed, return view with parsed options
        $parseObj = new ParseOptions($request);
        $options = $parseObj->getOptions();

        // Return view with options
        return view('pages.products')->with('options', $options);
    }

    /**
     * Show the product inventory items (items) management interface.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function items(Request $request)
    {
        // If pre-set options have been passed, return view with parsed options
        $parseObj = new ParseOptions($request);
        $options = $parseObj->getOptions();

        // Return view with options
        return view('pages.items')->with('options', $options);
    }
}

?>
