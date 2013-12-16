<?php

namespace Loot\Api\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Loot\Api\MongoResource;

/**
 * @author Damien Pitard <damien.pitard@gmail.com>
 * @copyright Damien Pitard
 */
class ApiServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        /** handle exception in json */
        $app->error(function (\Exception $e, $code) use ($app) {
            if ('json' === $app['request']->getRequestFormat()) {

                //hide exceptions except 40x exceptions
                //but show every exceptions if debug mode is on
                if (!$app['debug']
                    && (!$e instanceof HttpException
                    || ($e->getStatusCode() < 400 && $e->getStatusCode() >= 500))) {

                    $data = array(
                        'message' => 'Something wrong happened.'
                    );
                } else {
                    $data = array(
                        'message' => $e->getMessage()
                    );
                }

                return $app->json($data);
            }
        });

        //accept json body
        $app->before(function (Request $request) {
            if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {

                if ('' === $request->getContent()) {
                    $data = array();
                } else {
                    $data = json_decode($request->getContent(), true);
                    if (null === $data) {
                        throw new BadRequestHttpException('Json body is not well formated.');
                    }
                }

                $request->request->replace(is_array($data) ? $data : array());
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
    }
}
