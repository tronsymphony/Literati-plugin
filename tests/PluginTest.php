<?php

namespace Literati\Example\Tests;

use Literati\Example\Plugin;
use WP_Mock\Tools\TestCase as TestCase;
use WP_Mock;

final class PluginTest extends TestCase {
  public function setUp(): void {
    WP_Mock::setUp();

    /** Setup mocks */
    WP_Mock::userFunction('get_option', [
      'return' => function ($key) {
        switch ($key) {
          case 'LITERATI_EXAMPLE_VERSION':
            return null;
        }
      },
    ]);

    WP_Mock::userFunction('remove_action', [
      'return' => true,
    ]);

    WP_Mock::userFunction('plugin_dir_path', [
      'return' => function ($dir) {
        return $dir . '/../';
      },
    ]);

    WP_Mock::userFunction('trailingslashit', [
      'return' => function ($path) {
        return rtrim($path, '/') . '/';
      },
    ]);
  }

  public function tearDown(): void {
    WP_Mock::tearDown();
  }

  public function test_happy() {
    $plugin = Plugin::instance();

    $this->assertSame($plugin->get_plugin_version(), '1.0.0');
    $this->assertSame($plugin->is_plugin_initialized(), true);
  }

  public function test_create_promotion_post_type()
  {
      $plugin = Plugin::instance();
      $plugin->create_promotion_post_type();

      $this->assertTrue(post_type_exists('promotion'));

      $postTypeObject = get_post_type_object('promotion');
      $this->assertEquals('Promotions', $postTypeObject->labels->name);
      $this->assertTrue($postTypeObject->public);
      $this->assertTrue($postTypeObject->show_ui);
      $this->assertTrue($postTypeObject->show_in_rest);
  }
}
