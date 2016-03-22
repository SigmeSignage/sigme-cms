<?php
/*
 * Spring Signage Ltd - http://www.springsignage.com
 * Copyright (C) 2015 Spring Signage Ltd
 * (LayoutTest.php)
 */


namespace Xibo\Tests\Integration;


use Xibo\Entity\Layout;
use Xibo\Tests\LocalWebTestCase;

/**
 * Class LayoutTest
 * @package Xibo\Tests
 */
class LayoutTest extends LocalWebTestCase
{
    /**
     * Test Layout Require
     * @return mixed
     */
    public function testRetire()
    {
        // Get any layout
        $layout = $this->container->layoutFactory->query(null, ['start' => 1, 'length' => 1])[0];

        // Call retire
        $this->client->put('/layout/retire/' . $layout->layoutId, [], ['CONTENT_TYPE' => 'application/x-www-form-urlencoded']);

        $this->assertSame(200, $this->client->response->status());

        // Get the same layout again and make sure its retired = 1
        $layout = $this->container->layoutFactory->getById($layout->layoutId);

        $this->assertSame(1, $layout->retired, 'Retired flag not updated');

        return $layout;
    }

    /**
     * @param Layout $layout
     * @depends testRetire
     */
    public function testUnretire($layout)
    {
        $layout->retired = 0;
        $this->client->put('/layout/' . $layout->layoutId, array_merge((array)$layout, ['name' => $layout->layout]), ['CONTENT_TYPE' => 'application/x-www-form-urlencoded']);

        $this->assertSame(200, $this->client->response->status(), $this->client->response->body());

        // Get the same layout again and make sure its retired = 1
        $layout = $this->container->layoutFactory->getById($layout->layoutId);

        $this->assertSame(0, $layout->retired, 'Retired flag not updated. ' . $this->client->response->body());
    }
}