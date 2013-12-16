<?php

namespace Loot\Api\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
/**
 * @author Damien Pitard <damien.pitard@gmail.com>
 * @copyright Damien Pitard
 */
class WorldControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/{name}', function($name, Application $app) {

            //@todo check rights

            $collection = $app['mongo.db']->selectCollection('worlds');
            $world = $collection->findOne(array('name' => $name));
            if (!$world) {
                $app->abort(404, sprintf('World "%s" not found.', $name));
            }

            return $app->json($app['resource']->format($world));

        })->value('_format', 'json');


        $controllers->post('/{name}', function($name, Application $app) {

            //@todo check rights

            $collection = $app['mongo.db']->selectCollection('worlds');

            //does the world exist
            $world = $collection->findOne(array('name' => $name));
            if ($world) {
                $app->abort(400, sprintf('A world with the same name "%s" already exists.', $name));
            }

            //this array defines allowed fields for submit
            $allowed = array(
                "visibility",
                "smog"
            );

            //this array defines default values for fields
            $defaults = array(
                "name" => $name,
                "visibility" => 10.0,
                "smog" => true,
                "created_at" => new \MongoDate(time()),
                "created_by" => 'admin'
            );

            //find unallowed fields
            $fields = $app['request']->request->all();
            $diff = array_diff_key($fields, array_flip($allowed));
            if (count($diff)) {
                $app->abort(400, sprintf('Following fields are not allowed: %s.', implode(', ', array_keys($diff))));
            }

            //fill with default values
            $world = array_merge($defaults, $fields);

            //insert in mongo
            $collection->insert($world);

            return $app->json($app['resource']->format($world));
        })->value('_format', 'json');

        $controllers->put('/{name}', function($name, Application $app) {

            //@todo check rights

            $collection = $app['mongo.db']->selectCollection('worlds');

            //this array defines allowed fields for submit
            $allowed = array(
                "visibility",
                "smog"
            );

            //find unallowed fields
            $fields = $app['request']->request->all();
            $diff = array_diff_key($fields, array_flip($allowed));
            if (count($diff)) {
                $app->abort(400, sprintf('Following fields are not allowed: %s.', implode(', ', array_keys($diff))));
            }

            //update with submitted values
            $result = $collection->update(array('name' => $name), array('$set' => $fields), array('upsert' => false));
            if (0 == $result['n']) {
                $app->abort(404, sprintf('World "%s" not found.', $name));
            }

            return $app->noContent();
        })->value('_format', 'json');

        $controllers->delete('/{name}', function($name, Application $app) {

            //@todo check rights

            $collection = $app['mongo.db']->selectCollection('worlds');
            $result = $collection->remove(array('name' => $name), array('justOne' => true));
            if (0 == $result['n']) {
                $app->abort(404, sprintf('World "%s" not found.', $name));
            }

            return $app->noContent();
        })->value('_format', 'json');

        return $controllers;
    }
}
