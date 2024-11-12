<?php

namespace App\Http\Middleware;

use URL;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $validLocales = config('app.available_locales', ['en', 'it']);
        $defaultLocale = 'en'; // Impostiamo l'italiano come default per le rotte
        $locale = $request->segment(1);

        // Se non c'è locale nell'URL o non è valido
        if (!$locale || !in_array($locale, $validLocales)) {
            // Prova a prendere il locale dal cookie
            $cookieLocale = $request->cookie('preferred_locale');
            
            // Usa il locale dal cookie o il default (it)
            $locale = $cookieLocale && in_array($cookieLocale, $validLocales) 
                     ? $cookieLocale 
                     : $defaultLocale;

            // Reindirizza mantenendo il path originale
            $redirectPath = $locale;
            if ($request->path() !== '/') {
                $redirectPath .= '/' . $request->path();
            }

            return redirect($redirectPath)->withCookie(
                cookie()->forever('preferred_locale', $locale)
            );
        }

        // Imposta il locale e salva nel cookie
        app()->setLocale($locale);
        Carbon::setLocale($locale);
        URL::defaults(['locale' => $locale]);

        // Aggiorna il cookie
        if ($request->cookie('preferred_locale') !== $locale) {
            Cookie::queue('preferred_locale', $locale, 60 * 24 * 365);
        }

        return $next($request);
    }
}