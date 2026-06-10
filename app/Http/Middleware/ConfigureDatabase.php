<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class ConfigureDatabase
{
    /**
     * Handle an incoming request and check connection
     *
     * @author CandorIVF
     * @version 1.0
     * @since 1.0 new method by Yogesh Rangoliya on date 27/02/2022
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $domain = 'mysql';
        $subdomain = explode('.', request()->getHost())[0];
        if(isset($subdomain) && !empty($subdomain) && $subdomain=='parvatpatiya'){
            $domain = 'parvatpatiya';
            config()->set('app.url', 'https://parvatpatiya.candorivf.com/');
            config()->set('app.asset_url', 'https://parvatpatiya.candorivf.com/');
        }
        $connection = $this->getConnectionForSubDomain($domain);
        config()->set('database.default', $connection);
        \DB::purge();

        return $next($request);
    }

    /**
     * Verify database connection is valid or set default
     *
     * @author CandorIVF
     * @version 1.0
     * @since 1.0 new method by Yogesh Rangoliya on date 27/02/2022
     * @param string $domain
     * @return string
     */
    protected function getConnectionForSubDomain(string $domain): string
    {
        $connections = [
            'parvatpatiya' => 'mysql2',
        ];

        return $connections[$domain] ?? 'mysql';
    }
}
