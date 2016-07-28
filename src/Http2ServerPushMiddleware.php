<?php

namespace TomSchlick\ServerPush;

use Closure;
use Illuminate\Support\Facades\Request;

/**
 * Class Http2ServerPushMiddleware
 * @package TomSchlick\ServerPush
 */
class Http2ServerPushMiddleware
{
    protected $response;

    /**
     * @param Request $request
     * @param Closure $next
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
        foreach (app('server-push')->generateLinks() as $link) {
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
