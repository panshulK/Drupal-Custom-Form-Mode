<?php

namespace Drupal\display_custom_form_mode\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Entity\EntityFormBuilderInterface;
use Drupal\node\Entity\Node;

/**
 * Provides a block for the coach registration form.
 *
 * @Block(
 *   id = "manage_profile_company_form",
 *   admin_label = @Translation("Company Manage Profile"),
 *   category = @Translation("Forms")
 * )
 *
 * Note that we set module to contact so that blocks will be disabled correctly
 * when the module is disabled.
 */
class ManageProfileCompanyFormBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity manager
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface.
   */
  protected $entityManager;

  /**
   * The entity form builder
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface.
   */
  protected $entityFormBuilder;

  /**
   * Constructs a new UserRegisterBlock plugin
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityManagerInterface $entityManager
   *   The entity manager.
   * @param \Drupal\Core\Entity\EntityFormBuilderInterface $entityFormBuilder
   *   The entity form builder.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityManagerInterface $entityManager, EntityFormBuilderInterface $entityFormBuilder) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityManager = $entityManager;
    $this->entityFormBuilder = $entityFormBuilder;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity.manager'),
      $container->get('entity.form_builder')
    );
  }

  /**
   * Implements \Drupal\block\BlockBase::build().
   */
  public function build() {
    $build = array();
    $nids = $this->getNodeFromCurrentUserProfile();
    //Display Node Form.
    if (!empty($nids)) {
      $node_form = Node::load($nids);
      $build['form'] = $this->entityFormBuilder->getForm($node_form, 'company_form_mode');
    }

    return $build;
  }

  public static function getNodeFromCurrentUserProfile() {
    $nids = '';

    // Fetch Email ID
    $current = \Drupal::currentUser();
    if ($current->id()) {
      $account = \Drupal\user\Entity\User::load($current->id());
      if (!empty($account)) {
          $email =  $account->getEmail();
      }
    }

    //Fetch Company Id.
    if (!empty($email)) {
      $query = \Drupal::database()->select('node__field_mail', 'nce');
      $query = $query->fields('nce', array('entity_id'));
      $query = $query->condition('nce.bundle
', 'agency');
      $query = $query->condition('nce.field_mail_value
', $email);
      $nids = $query->execute()->fetchField();
    }

    return $nids;
  }
}
