<?php

if (!function_exists('pushImage')) {
    /**
     * @param string $resourcePath
     */
    function pushImage(string $resourcePath)
    {
        return app('server-push')->queueResource($resourcePath, 'image');
    }
}

if (!function_exists('pushScript')) {
    /**
     * @param string $resourcePath
     */
    function pushScript(string $resourcePath)
    {
        return app('server-push')->queueResource($resourcePath, 'script');
    }
}

if (!function_exists('pushStyle')) {
    /**
     * @param string $resourcePath
     */
    function pushStyle(string $resourcePath)
    {
        return app('server-push')->queueResource($resourcePath, 'style');
    }
}
