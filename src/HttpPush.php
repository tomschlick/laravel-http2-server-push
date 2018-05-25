<?php

namespace TomSchlick\ServerPush;

/**
 * Class HttpPush.
 */
class HttpPush
{
    /**
     * @var array
     */
    public $resources = [];

    /**
     * @param array       $paths
     * @param string|null $type
     */
    public function queueResources(array $paths = [], string $type = null)
    {
        foreach ($paths as $path) {
            $this->queueResource($path, $type);
        }
    }

    /**
     * Push a resource onto the queue for the middleware.
     *
     * @param string      $resourcePath
     * @param null|string $type
     */
    public function queueResource(string $resourcePath, string $type = null)
    {
        if (!$type) {
            $type = static::getTypeByExtension($resourcePath);
        }

        $this->resources[] = [
            'path' => $resourcePath,
            'type' => $type,
        ];
    }

    /**
     * Generate the server push link strings.
     *
     * @return array
     */
    public function generateLinks() : array
    {
        $links = [];

        foreach ($this->resources as $row) {
            $links[] = '<'.$row['path'].'>; rel=preload; as='.$row['type'];
        }

        return $links;
    }

    /**
     * @return bool
     */
    public function hasLinks() : bool
    {
        return !empty($this->resources);
    }

    /**
     * Clear all resources out of the queue.
     */
    public function clear()
    {
        $this->resources = [];
    }

    /**
     * @param string $resourcePath
     *
     * @return string
     */
    public static function getTypeByExtension(string $resourcePath) : string
    {
        $parts = explode('.', $resourcePath);
        $extension = end($parts);
        switch ($extension) {
            case 'css': return 'style';
            case 'js': return 'script';
            case 'ttf': return 'font';
            case 'otf': return 'font';
            case 'woff': return 'font';
            case 'woff2': return 'font';
            default: return 'image';
        }
    }

    /**
     * @param array $data
     */
    public function massAssign(array $data = [])
    {
        foreach ($data as $type => $paths) {
            $type = rtrim($type, 's');
            $this->queueResources($paths, $type);
        }
    }
}
