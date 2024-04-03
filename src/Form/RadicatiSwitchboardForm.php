<?php
namespace Drupal\radicati_views_switchboard\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\RedirectCommand;

/**
 * Implements a simple form.
 */
class RadicatiSwitchboardForm extends FormBase {
  public function getFormId() {
    return 'radicati_switchboard_form';
  }

  /**
   * Build the simple form.
   */
  public function buildForm(array $form, FormStateInterface $form_state, $extra = []) {

    if(!empty($extra['default_value_label'])) {
      $extra['items'] = ['' => $extra['default_value_label'] ] + $extra['items'];
    }

    $form['item'] = [
      '#type' => 'select',
      '#title' => $extra['label'] ?? $this->t('Jump Menu'),
      '#options' => $extra['items'],
      '#required' => TRUE,
      '#default_value' => !empty($extra['default_value_label']) ? '|default|' : FALSE
    ];

    if($extra['auto_submit']) {
      $form['item']['#ajax'] = [
        'callback' => [$this, 'ajaxOnSubmit'],
        'event' => 'change'
      ];
    }

    if(!$extra['show_label']) {
      $form['item'] += [
        '#title_display' => 'invisible'
      ];
    }

    $form['actions'] = [
      '#type' => 'actions',
    ];

    // Add a submit button that handles the submission of the form.
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Go to Page'),
    ];

    if($extra['hide_submit_button']) {
      $form['actions']['submit']['#attributes'] = [
        'class' => ['visually-hidden']
      ];
    }

    return $form;
  }



  /**
   * For now, make sure this is a published node.
   *
   * TODO: For proper validation we should make sure this is a node that's actually in the view.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $item = $form_state->getValue('item');
//    $node = \Drupal\node\Entity\Node::load($item);
//
//    if(!$node || $node->isPublished() == false) {
//      return FALSE;
//    }

    return TRUE;
  }


  public function submitForm(array &$form, FormStateInterface $form_state) {
    $item = $form_state->getValue('item');
    //$url = \Drupal\Core\Url::fromRoute('entity.node.canonical', ['node' => $item]);
    return $form_state->setRedirectUrl($item);
  }

  public function ajaxOnSubmit(array &$form, FormStateInterface $form_state) {
    if($this->validateForm($form, $form_state)) {
      $item = $form_state->getValue('item');
      
      if($item == '') {
        return null;
      }
      //$url = \Drupal\Core\Url::fromRoute('entity.node.canonical', ['node' => $item]);

      $response = new AjaxResponse();
      $response->addCommand(new RedirectCommand($item));
      return $response;
    }
  }
}
