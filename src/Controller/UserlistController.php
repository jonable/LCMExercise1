<?php
/**
 * @file
 * Contains \Drupal\exercise1\Controller\UserlistController.
 */
namespace Drupal\exercise1\Controller;

use Drupal\Core\Controller\ControllerBase;

use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Response;


class UserlistController extends ControllerBase{
    
    /**
    * {@inheritdoc}
    */  
    public function content(){
        // Demo view of user lists.
        // See Plugin/Block/UserlistBlock for implementation.

        // Fetch the user list from the end point and generate the render array
        $body = [];
        $client = \Drupal::httpClient();
        $max_age = 60;
        try {
            $response = $client->request('GET', 'https://deelay.me/3000/https://jsonplaceholder.typicode.com/users');
            if($response->getStatusCode() == 200){        
                $body = json_decode($response->getBody()->getContents());
            }            
        }catch (RequestException $e) {
            \Drupal::logger('exercise1')->error($e->getMessage());
        }
        // #context variable accessible from template exercise1-userlist.html
        $build = [
            '#theme' => 'exercise1_userlist',
            '#context' => [
                'timestamp' => date('m/d/Y h:i:s a', \Drupal::time()->getCurrentTime()),
                'userlist' => $body
            ],
        ];    

        $build['#cache']['max-age'] = $max_age;

        return $build;
    }

}