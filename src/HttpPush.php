<?php


namespace TomSchlick\ServerPush;


/**
 * Class HttpPush
 * @package TomSchlick\ServerPush
 */
class HttpPush
{
    /**
     * @var array
     */
    public $resources = [];

    /**
     * Push a resource onto the queue for the middleware.
     *
     * @param string $resourcePath
     * @param string $type
     */
    public function queueResource(string $resourcePath, string $type)
    {
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
            $links[] = '<' . $row['path'] . '>; rel=preload; as=' . $row['type'];
        }

        return $links;
    }

    /**
     * Clear all resources out of the queue.
     */
    public function clear()
    {
        $this->resources = [];
    }
}
