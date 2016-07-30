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

        if ($this->shouldUseServerPush($request)) {
            $this->addServerPushHeaders();
        }

        return $this->response;
    }

    protected function addServerPushHeaders()
    {
        if (app('server-push')->hasLinks()) {
            $link = implode(',', app('server-push')->generateLinks());
            $this->response->headers->set('Link', $link);
        }
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    protected function shouldUseServerPush(Request $request) : bool
    {
        return !$request->ajax();
    }
}
