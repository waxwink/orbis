<?php

namespace Waxwink\Orbis\Queue;

use RedBeanPHP\OODBBean;
use RedBeanPHP\R;

class JobRepository
{
    public function add(string $job, string $input)
    {
        $item = R::dispense('jobs');
        $item->job = $job;
        $item->input = $input;
        R::store($item);

        return $item->getProperties();
    }

    public function shift()
    {
        /** @var OODBBean[] $items */
        $items= R::findFromSQL('jobs', 'SELECT * FROM jobs LIMIT 1');
        if (!$items) {
            return [];
        }
        $firstItem = array_values($items)[0];
        $data= $firstItem?->getProperties();

        $data && R::trash($firstItem);

        return $data;
    }
}
