<?php

namespace Drupal\informaid_mailjet\Form;

/**
 * @todo: Implement Mailjet client and ressources in Constructor.
 */

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Mailjet\Resources;
use Mailjet\Client;

/**
 * Class SettingsForm.
 */
class SettingsForm extends ConfigFormBase
{

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames()
  {
    return [
      'informaid_mailjet.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $config = $this->config('informaid_mailjet.settings');
    $form['mailjet_api_key_public'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Mailjet API key public'),
      '#description' => $this->t('Api key displayed in your account on https://app.mailjet.com/account/api_keys'),
      '#maxlength' => 33,
      '#size' => 33,
      '#default_value' => $config->get('mailjet_api_key_public'),
      '#required' => true,
    ];
    $form['mailjet_api_key_private'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Mailjet API key private'),
      '#description' => $this->t('Secret key displayed in your account on https://app.mailjet.com/account/api_keys'),
      '#maxlength' => 33,
      '#size' => 33,
      '#default_value' => $config->get('mailjet_api_key_private'),
      '#required' => true,
    ];
    $form['email_from_test'] = [
      '#type' => 'email',
      '#title' => $this->t('Sender e-mail'),
      '#description' => $this->t('Sender e-mail for tests.'),
      '#default_value' => $config->get('email_from_test'),
      '#required' => true,
    ];
    $form['email_to_test'] = [
      '#type' => 'email',
      '#title' => $this->t('Receipe e-mail'),
      '#description' => $this->t('Receipe e-mail for tests.'),
      '#default_value' => $config->get('email_to_test'),
      '#required' => true,
    ];
    return parent::buildForm($form, $form_state);
  }

  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    $mj = new Client(
      $form_state->getValue('mailjet_api_key_public'),
      $form_state->getValue('mailjet_api_key_private'),
      true,
      ['version' => 'v3.1']
    );
    // $httpClient = \Drupal::httpClient();
    // $uri = 'https://api.mailjet.com/v3.1/send';
    $body = [
      'Messages' => [
        [
          'From' => [
            'email' => $form_state->getValue('email_from_test'),
            'name' => 'Fabienne',
          ],
          'To' => [
            [
              'email' => $form_state->getValue('email_to_test'),
              'name' => 'You'
            ]
          ],
          'Subject' => 'Test from Informaid Mailjet!',
          'TextPart' => 'Greetings from Mailjet!',
          'HTMLPart' => "<h3>Dear passenger 1, welcome to <a href=\"https://www.mailjet.com/\">Mailjet</a>!</h3><br />May the delivery force be with you!"
        ]
      ]
    ];
    $response = $mj->post(Resources::$Email, ['body' => $body]);

    if (null === $response || !$response->success()) {
      $form_state->setErrorByName('mailjet_api_key_public', 'One of the informations is KO.');
    } else {
      \Drupal::logger('informaid_mailjet')->info('Mailjet test is OK, mail sent to frondeau@outlook.fr.');
    }
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    parent::submitForm($form, $form_state);

    $this->config('informaid_mailjet.settings')
      ->set('mailjet_api_key_public', $form_state->getValue('mailjet_api_key_public'))
      ->set('mailjet_api_key_private', $form_state->getValue('mailjet_api_key_private'))
      ->set('email_from_test', $form_state->getValue('email_from_test'))
      ->set('email_to_test', $form_state->getValue('email_to_test'))
      ->save();
  }
}
