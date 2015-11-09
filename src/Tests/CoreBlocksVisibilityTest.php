<?php
/**
 * @file
 * Contains \Drupal\block_tests\Tests\CoreBlocksVisibilityTest.
 */


namespace Drupal\block_tests\Tests;


use Drupal\simpletest\WebTestBase;

/**
 * Class CoreBlocksVisibilityTest
 *
 * Drupal core does not provide test for some block functionality.
 * Test it to make sure it works.
 *
 * @group block_tests
 */
class CoreBlocksVisibilityTest extends WebTestBase{
  /**
   * Modules to enable.
   *
   * var array
   */
  public static $modules = ['block', 'node', 'system'];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    // Create and login with user who can administer blocks.
    $this->drupalLogin($this->drupalCreateUser([
      'administer blocks',
    ]));

    // Create Basic page and Article node types.
    if ($this->profile != 'standard') {
      $this->drupalCreateContentType(array(
        'type' => 'page',
        'name' => 'Basic page',
        'display_submitted' => FALSE,
      ));
      $this->drupalCreateContentType(array('type' => 'article', 'name' => 'Article'));
    }
  }

  public function testNodeType() {
    $settings['visibility']['node_type']['bundles']['page'] = 'page';
    $settings['label_display'] = 'visible';
    $block = $this->drupalPlaceBlock('system_powered_by_block', $settings);
    $node = $this->drupalCreateNode();
    $this->drupalGet('node/' . $node->id());
    $this->assertText($block->label(),'block shows node page');
  }

  public function testRequest() {
    $settings['visibility']['request_path'] = [
      'pages' => '/node/*',
    ];
    $settings['label_display'] = 'visible';
    $block = $this->drupalPlaceBlock('system_powered_by_block', $settings);
    $node = $this->drupalCreateNode();
    $this->drupalGet('node/' . $node->id());
    $this->assertText($block->label(),'block shows on path "/node/*');
    $this->drupalGet('node');
    $this->assertNoText($block->label(),'block DOES NOT show on path "/node');
  }


}
