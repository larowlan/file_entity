<?php

/**
 * @file
 * Contains \Drupal\file_entity\Plugin\Action\FileDelete.
 */

namespace Drupal\file_entity\Plugin\Action;

use Drupal\Core\Action\ActionBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\user\TempStoreFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Delete a file.
 *
 * @Action(
 *   id = "file_delete_action",
 *   label = @Translation("Delete file"),
 *   type = "file",
 *   confirm_form_route_name = "file_entity.multiple_delete_confirm",
 * )
 */
class FileDelete extends ActionBase implements ContainerFactoryPluginInterface {

  /**
   * The temp store.
   *
   * @var \Drupal\user\TempStore
   */
  protected $tempStore;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, TempStoreFactory $temp_store_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->tempStore = $temp_store_factory->get('file_multiple_delete_confirm');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition, $container->get('user.tempstore'));
  }

  /**
   * {@inheritdoc}
   */
  public function execute($entity = NULL) {
    $this->executeMultiple(array($entity));
  }

  /**
   * {@inheritdoc}
   */
  public function executeMultiple(array $entities) {
    // Just save in temp store for now, delete after confirmation.
    $this->tempStore->set('delete', $entities);
  }
}
