<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Switch the application language.
     *
     * @param string $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch($locale)
    {
        $availableLocales = ['en', 'bn', 'es', 'fr', 'de'];
        
        if (!in_array($locale, $availableLocales)) {
            $locale = 'en';
        }
        
        App::setLocale($locale);
        Session::put('locale', $locale);
        
        return redirect()->back();
    }
}