<?php

namespace Drupal\radicati_views_switchboard\Plugin\views\style;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\style\StylePluginBase;
use Drupal\Component\Utility\Html;

/**
 * Renders a view as a select menu that routes the user to a node or url.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "views_radicati_switchboard",
 *   title = @Translation("Radicati Switchboard"),
 *   help = @Translation("Displays content as a dropdown and redirects users to selected content."),
 *   theme="views_radicati_switchboard",
 *   display_types = {"normal"}
 * )
 */
class RadicatiViewsSwitchboard extends StylePluginBase {
  /**
   * Overrides \Drupal\views\Plugin\views\style\StylePluginBase::usesRowPlugin.
   *
   * @var bool
   */
  protected $usesRowPlugin = FALSE;

  /**
   * Overrides \Drupal\views\Plugin\views\style\StylePluginBase::usesRowClass.
   *
   * @var bool
   */
  protected $usesRowClass = FALSE;
  protected $usesFields = TRUE;
  protected $defaultFieldLabels = FALSE;

  /**
   * Definition.
   */
  protected function defineOptions() {
    $options = parent::defineOptions();

    $options['label'] = ['default' => $this->t('Jump Menu')];
    $options['show_label'] = ['default' => TRUE];
    $options['auto_submit'] = ['default' => TRUE];
    $options['hide_submit_button'] = ['default' => TRUE];
    $options['default_value_label'] = ['default' => ''];

    $options['label_field'] = ['default' => ''];
    $options['url_field'] = ['default' => ''];

    $options['description'] = ['default' => ''];
    $options['show_description'] = ['default' => FALSE];

    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);

    $form['default_value_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Default Value Label'),
      '#description' => $this->t('Sets the default value label, which is displayed when nothing is selected.'),
      '#default_value' => $this->options['default_value_label'] ?? $this->t('Select a Thing'),
      '#required' => FALSE,
    ];

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#description' => $this->t('Even if the label is hidden, it will be used by screen readers. Make sure this has a descriptive label so people know what it is.'),
      '#default_value' => $this->options['label'] ?? $this->t('Jump Menu'),
      '#required' => TRUE,
    ];

    $form['show_label'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show Label'),
      '#description' => $this->t('If unchecked, the label will be hidden from display and only used by screen reader software.'),
      '#default_value' => $this->options['show_label'] ?? TRUE,
    ];

    $form['auto_submit'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Auto Submit'),
      '#description' => $this->t('If checked, the page will automatically redirect when an option is selected.'),
      '#default_value' => $this->options['auto_submit'] ?? TRUE,
    ];

    $form['hide_submit_button'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Hide Submit Button'),
      '#description' => $this->t('If checked, the submit button will be visually hidden.'),
      '#default_value' => $this->options['hide_submit_button'] ?? TRUE,
    ];

    // Get a list of field that can be used for the label and url.
    $fields = $this->displayHandler->getFieldLabels(TRUE);
    $form['label_field'] = [
      '#type' => 'select',
      '#title' => $this->t('Label Field'),
      '#description' => $this->t('Select the field that will be used for the label.'),
      '#options' => $fields,
      '#default_value' => $this->options['label_field'],
      '#required' => TRUE,
    ];

    $form['url_field'] = [
      '#type' => 'select',
      '#title' => $this->t('URL Field'),
      '#description' => $this->t('Select the field that will be used for the URL.'),
      '#options' => $fields,
      '#default_value' => $this->options['url_field'],
      '#required' => TRUE,
    ];

    return $form;
  }
}
