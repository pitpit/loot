<?php

namespace Loot\Api;

/**
 * @author Damien Pitard <damien.pitard@gmail.com>
 * @copyright Damien Pitard
 */
class MongoResource
{
    /**
     * {@inheritdoc}
     */
    public function format($fields)
    {
        foreach ($fields as $name => $value) {
            if ($value instanceof \MongoDate) {
                $fields[$name] = date('c', $value->sec);
            } else if ($value instanceof \MongoId) {
                //$fields['id'] = (string)$value;
                unset($fields['_id']);
            } else if (is_array($value)) {
                $fields[$name] = $this->format($value);
            }
        }

        ksort($fields);

        return $fields;
    }
}
