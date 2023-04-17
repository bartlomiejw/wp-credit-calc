<?php

namespace Tests;

use nbpdefined('ABSPATH') or die();

class AdminLoaderTests extends PluginTestCase
{
    public function test_construct()
    {
        new \CreditCalc\AdminLoader('test');

        $this->assertTrue(has_action('admin_menu', '\CreditCalc\AdminLoader->admin_menu()') > 0);
    }
}
