<?php

namespace Drupal\exercise1\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides Userlist as a block.
 *
 * @Block(
 *   id = "userlist_block",
 *   admin_label = @Translation("Exercise1 Userlist Block")
 * )
 */
class UserlistBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return ['label_display' => FALSE];
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
        return \Drupal\exercise1\Controller\UserlistController::content();
  }

}