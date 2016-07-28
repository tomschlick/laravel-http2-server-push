<?php

namespace TomSchlick\ServerPush;

use Closure;
use Illuminate\Http\Request;

/**
 * Class Http2ServerPushMiddleware.
 */
class Http2ServerPushMiddleware
{
    protected $response;

    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->response = $next($request);

        if ($this->shouldUseServerPush()) {
            $this->addServerPushHeaders();
        }

        return $this->response;
    }

    protected function addServerPushHeaders()
    {
        if (app('server-push')->hasLinks()) {
            $link = implode(',', app('server-push')->generateLinks());
            $this->response->headers->set('Link', $link, true);
        }
    }

    /**
     * @return bool
     */
    protected function shouldUseServerPush() : bool
    {
        return true;
    }
}
