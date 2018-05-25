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
     * @param Request     $request
     * @param Closure     $next
     * @param null|string $group
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $group = null)
    {
        $this->response = $next($request);

        if ($this->shouldUseServerPush($request)) {
            app('server-push')->massAssign(config('server-push.groups.'.$group, []));

            $this->addServerPushHeaders();
        }

        return $this->response;
    }

    protected function addServerPushHeaders()
    {
        if (app('server-push')->hasLinks()) {
            $link = implode(',', app('server-push')->generateLinks());
            $this->response->headers->set('Link', $link, false);

            app('server-push')->clear();
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
