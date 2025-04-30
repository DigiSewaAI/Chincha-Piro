<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TranslateController extends Controller
{
    public function show()
    {
        return view('translate');
    }

    public function translate(Request $request)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://translation.googleapis.com/language/translate/v2?key='.env('GOOGLE_TRANSLATE_KEY'), [
            'q' => $request->text,
            'target' => 'en'
        ]);

        return response()->json([
            'translatedText' => $response->json()['data']['translations'][0]['translatedText']
        ]);
    }
}
