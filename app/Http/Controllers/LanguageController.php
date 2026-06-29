<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    /**
     * Switch the application language.
     *
     * @param  string  $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch($locale)
    {
        // Whitelist allowed languages to prevent injection
        if (!in_array($locale, ['en', 'id', 'fr'])) {
            abort(400, 'Invalid language selected.');
        }

        // Store the chosen locale in the session
        session()->put('locale', $locale);

        return redirect()->back();
    }
}
