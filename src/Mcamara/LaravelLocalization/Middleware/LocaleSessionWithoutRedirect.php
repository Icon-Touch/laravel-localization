<?php namespace Mcamara\LaravelLocalization\Middleware;

use Illuminate\Contracts\Routing\Middleware;
use Illuminate\Http\RedirectResponse;
use Closure;

class LocaleSessionWithoutRedirect implements Middleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle( $request, Closure $next )
    {
        $conf = app('laravellocalization');

        $params = explode('/', $request->path());

        if ( count($params) > 0 && $locale = app('laravellocalization')->checkLocaleInSupportedLocales($params[ 0 ]) )
        {
            session([ 'locale' => $params[ 0 ] ]);

            return $next($request);
        }

        if (\Session::get('locale'))
        {
            \App::setLocale(\Session::get('locale'));
            return $next($request);
        }
        else
        {
            \App::setLocale($conf->getDefaultLocale());
            return $next($request);
        }
    }
}