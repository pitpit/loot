<?php

namespace Loot;

use Silex\Application as BaseApplication;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Damien Pitard <damien.pitard@gmail.com>
 * @copyright Damien Pitard
 */
class Application extends BaseApplication
{
    /**
     * Return HTTP 204 Response
     *
     * @param array $headers An array of response headers
     *
     * @return Response
     */
    public function noContent($headers = array())
    {
        return new Response('', 204, $headers);
    }

}
