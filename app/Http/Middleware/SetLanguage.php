<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $languages = array_keys(config('app.languages'));

        if (request('lang') && in_array(request('lang'), $languages)) {
            $lang = request('lang');
        } elseif ($request->cookie('language') && in_array($request->cookie('language'), $languages)) {
            $lang = $request->cookie('language');
        } elseif (setting('language') && in_array(setting('language'), $languages)) {
            $lang = setting('language');
        } else {
            $lang = config('app.locale');
        }
        if ($lang  && in_array($lang, $languages)) {
            setcookie("language", $lang, time() + (86400 * 30), "/");
            app()->setLocale($lang);
        }

        return $next($request);
    }
}
