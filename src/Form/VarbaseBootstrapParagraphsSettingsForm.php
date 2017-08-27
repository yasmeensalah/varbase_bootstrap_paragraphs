<?php

namespace Drupal\varbase_bootstrap_paragraphs\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides form for managing module settings.
 */
class VarbaseBootstrapParagraphsSettingsForm extends ConfigFormBase {

  /**
   * @inheritdoc
   */
  public function getFormId() {
    return 'varbase_bootstrap_paragraphs_settings';
  }

  /**
   * @inheritdoc
   */
  protected function getEditableConfigNames() {
    return ['varbase_bootstrap_paragraphs.settings'];
  }

  /**
   * @inheritdoc
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('varbase_bootstrap_paragraphs.settings');
    $form['settings']['background_colors'] = [
      '#type' => 'textarea',
      '#default_value' => $config->get('background_colors'),
      '#title' => t('Available background colors for Varbase Bootstrap Paragraphs'),
      '#description' => t("Add the list of general colors (usually the brand colors) for the site, that the editors will be able to use in Varbase Bootstrap Paragraphs. Refer to your designer to get the list."),
      '#cols' => 60,
      '#rows' => 10,
    ];


    return parent::buildForm($form, $form_state);
  }

  /**
   * @inheritdoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('varbase_bootstrap_paragraphs.settings');
    $config->set('background_colors', $form_state->getValue('background_colors'));
    $config->save();

    parent::submitForm($form, $form_state);
  }

}
