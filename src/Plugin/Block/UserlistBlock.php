<?php

namespace Drupal\exercise1\Plugin\Block;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Block\BlockBase;

/**
 * Provides the Userlist as a block.
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
    return [
        'label_display' => FALSE, 
        'max_cache_age' => 60
    ];
  }

  public function fetch_userlist(){
        $body = [];
        $client = \Drupal::httpClient();
        $config = $this->getConfiguration();

        $end_point = ! empty($config['endpoint']) ? $config['endpoint'] : '';
        
        if(empty($end_point)){
          return $body;
        }

        try {
            $response = $client->request('GET', $end_point);
            if($response->getStatusCode() == 200){
                $body = json_decode($response->getBody()->getContents());
            }            
        }catch (RequestException $e) {
            \Drupal::logger('exercise1')->error($e->getMessage());
        }  
        return $body;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
      $config = $this->getConfiguration();
        // Fetch the user list from the end point and generate the render array
        $body = $this->fetch_userlist();        

      // #context variable accessible from template exercise1-userlist.html
      $build = [
          '#theme' => 'exercise1_userlist',
          '#context' => [
              'timestamp' => date('m/d/Y h:i:s a', \Drupal::time()->getCurrentTime()),
              'userlist' => $body
          ],
      ];    

      $build['#cache']['max-age'] = $config['max_cache_age'];
  
      return $build;

  }

 /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $config = $this->getConfiguration();

    $form['endpoint'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Endpoint Url'),
      '#description' => $this->t('Url to fetch user list from.'),
      '#default_value' => $config['endpoint'] ?? '',
    ];
    $form['max_cache_age'] = [
      '#type' => "number",
      '#title' => $this->t('Max Cache Age'),
      '#description' => $this->t('Time in Milliseconds'),
      '#default_value' => $config['max_cache_age'] ?? '0',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $values = $form_state->getValues();
    $this->configuration['max_cache_age'] = $values['max_cache_age'];
    $this->configuration['endpoint'] = $values['endpoint'];
  }

}