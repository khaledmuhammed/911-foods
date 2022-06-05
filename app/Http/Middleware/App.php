<?php namespace App\Http\Middleware;

use App\Models\Upload;
use Closure;
use Exception;
use Illuminate\Http\Request;

class App
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // dd($request);
        // $order_permission = $request->route()->uri;
        // if($order_permission == 'order'){

        // }
        // dd($permission);
        try {
            // dd(setting('app_logo', '') );
            $upload = Upload::where('uuid', setting('app_logo', ''))->first();
            $appLogo = asset('images/logo/logo.png');
            // dd($appLogo);
            if ($upload && $upload->hasMedia('app_logo')) {
                // $appLogo = $upload->getFirstMediaUrl('app_logo');
                $appLogo = asset('images/logo/logo.png');
            }
            view()->share('app_logo', $appLogo);
        } catch (Exception $exception) {
        }

        return $next($request);
    }

}
