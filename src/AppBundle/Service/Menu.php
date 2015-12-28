<?php
/**
 * Created by Alexander 'unglued' Matrosov.
 * Company: Apus Agency
 * Site: http://www.apus.ag
 * E-mail: alex@apus.ag
 * Date: 28/12/15
 * Copyright (c) 2006-2015 Apus Agency
 */

namespace AppBundle\Service;


use Iterator;

class Menu// implements Iterator
{
    public $items;

    /**
     * Menu constructor.
     * @param array $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
        /*foreach($items as $item)
            $this->items[] = $item->toArray();*/
    }

    /**
     * Menu constructor.
     * @param MenuItem $item
     * @internal param $items
     * @return $this
     */
    public function addItem(MenuItem $item)
    {
        array_merge($this->items, $item);

        return $this;
    }

    public function getItems()
    {
        return $this->items;
    }

}