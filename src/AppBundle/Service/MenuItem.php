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


class MenuItem
{
    public $route;
    public $title;
    public $active;

    /**
     * MenuItem constructor.
     * @param $route
     * @param $title
     * @param $active
     */
    public function __construct($route, $title, $active = false)
    {
        $this->route = $route;
        $this->title = $title;
        $this->active = $active;
    }

    public function toArray(){
        return (array)$this;
    }


    /**
     * @param mixed $route
     * @return MenuItem
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * @param mixed $title
     * @return MenuItem
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param mixed $active
     * @return MenuItem
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return $this->active;
    }
}