<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // No middleware — Home page is public
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $signatureDishes = [
            [
                'id' => 1,
                'name' => 'चिप्ले चिञ्चा मासु',
                'price' => 599,
                'spice_level' => 3,
                'description' => 'मसालेदार मासुको विशेषतया चिचड़ीको स्वादमा तलेको।',
                'image' => 'images/chichhena-masu.jpg'
            ],
            [
                'id' => 2,
                'name' => 'खसीको मासुको तोरी',
                'price' => 699,
                'spice_level' => 4,
                'description' => 'खसीको मासुलाई खास ढंगले तलेको विशेष आइटम।',
                'image' => 'images/khasi-tori.jpg'
            ],
            [
                'id' => 3,
                'name' => 'दालभात सेट',
                'price' => 299,
                'spice_level' => 2,
                'description' => 'नेपालीको मनपर्ने परम्परागत भोजन सेट।',
                'image' => 'images/dalbhat-set.jpg'
            ],
            [
                'id' => 4,
                'name' => 'मकैको रोटी',
                'price' => 150,
                'spice_level' => 1,
                'description' => 'गाउँघरको स्वादको मकैको रोटी।',
                'image' => 'images/makai_roti.jpg'
            ],
            [
                'id' => 5,
                'name' => 'अचार मिक्स प्लेट',
                'price' => 199,
                'spice_level' => 5,
                'description' => 'विभिन्न प्रकारका नेपाली अचारको मिक्स।',
                'image' => 'images/achar_plate.jpg'
            ]
        ];

        return view('home', compact('signatureDishes'));
    }
}
