<?php
/**
 * Created by Alexander 'unglued' Matrosov.
 * Company: Apus Agency
 * Site: http://www.apus.ag
 * E-mail: alex@apus.ag
 * Date: 29/12/15
 * Copyright (c) 2006-2015 Apus Agency
 */

namespace tests\AppBundle\Service;


use AppBundle\Service\MenuItem;

class MenuItemTest extends \PHPUnit_Framework_TestCase
{

    public function test_should_transform_to_array ()
    {
        $menu = new MenuItem('test_route', 'title', true);

        $this->assertInternalType('array', $menu->toArray());
    }

    public function test_must_create_form_attribute()
    {
        $menu = new MenuItem(['test'=>'test_route'], 'title', true);

        $this->assertAttributeEquals(true,'form',$menu);

    }


}
