<?php

namespace Drupal\varbase_bootstrap_paragraphs\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Unicode;

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
  
  /**
   * @inheritdoc
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    
    $values = self::optionsExtractAllowedListTextValues($form_state->getValue('background_colors'));

    if (!is_array($values)) {
      $form_state->setErrorByName('background_colors', t('Allowed values list: invalid input.'));
    }
    else {
      // Check that keys are valid for the field type.
      foreach ($values as $key => $value) {
        if (Unicode::strlen($key) > 255) {
          $form_state->setErrorByName('background_colors', t('Allowed values list: each key must be a string at most 255 characters long.'));
          break;
        }
      }
    }
  }
  
  /**
   * Parses a string of 'allowed values' into an array.
   *
   * @param $string
   *   The list of allowed values in string format described in
   *   optionsExtractAllowedValues().
   *
   * @return
   *   The array of extracted key/value pairs, or NULL if the string is invalid.
   *
   * @see optionsExtractAllowedListTextValues()
   */
  public function optionsExtractAllowedListTextValues($string) {
    $values = array();

    $list = explode("\n", $string);
    $list = array_map('trim', $list);
    $list = array_filter($list, 'strlen');

    foreach ($list as $position => $text) {
      $value = $key = FALSE;

      // Check for an explicit key.
      $matches = array();
      if (preg_match('/(.*)\|(.*)/', $text, $matches)) {
        // Trim key and value to avoid unwanted spaces issues.
        $key = trim($matches[1]);
        $value = trim($matches[2]);
        $values[$key] = $value;
      }
      else {
        return;
      }
    }

    return $values;
  }

}
