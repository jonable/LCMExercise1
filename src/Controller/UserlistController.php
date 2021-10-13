<?php
/**
 * @file
 * Contains \Drupal\exercise1\Controller\UserlistController.
 */
namespace Drupal\exercise1\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Core\Link;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Cache\CacheableJsonResponse;
use Drupal\Core\Cache\CacheableResponse;
use Drupal\Core\Cache\CacheableMetadata;


use GuzzleHttp\Exception\RequestException;
use Symfony\Component\HttpFoundation\Response;


class UserlistController extends ControllerBase{
    
    /**
     * URL endpoint to fetch user list.
     *
     * TODO: Move to settings
     * @var string
     */
    // private static $end_point = "http://docker.for.mac.localhost:8111/";
    private static $end_point = "https://deelay.me/3000/https://jsonplaceholder.typicode.com/users";

    /**
     * Max age of cache
     *
     * TODO: Move to settings
     * @var integer
     */
    // private static $max_age = 60; // 1 minute
    private static $max_age = 3600; // 1 hour


    /**
    * {@inheritdoc}
    */  
    public static function content(){
        // Fetch the user list from the end point and generate the render array
        $body = [];
        $client = \Drupal::httpClient();
        
        try {
            $response = $client->request('GET', self::$end_point);
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

        $build['#cache']['max-age'] = self::$max_age;

        return $build;
    }

    /**
     * View the userlist  without using javascript fetch api.
     *
     * @return void
     */
    public static function userlistNoFetch(){
        $build = self::content();
        $url = Url::fromRoute("exercise1.userlistv2");
        $build['#context']['link_to_fetch_version'] = $url->toString();
        return $build;
    }

    /**
     * View the userlist using javascript fetch api.
     * 
     * I could not get this to cache with out issue, 
     * see fetchUserlist() for more info.
     *
     * @return void
     */
    public static function userlistWithFetch(){
        // This does not cache the fetch request response below and will always hit the end point.
        // Leaving it in for show.
        $container_prefix = \Drupal\Component\Utility\Html::getUniqueId("exercise1_userlist");
        // $container_prefix = 'exercise1__';
        $build = [];
        $build['#theme'] = 'exercise1_fetchuserlist';
        $build['#context']['end_point'] = 'exercise1.fetchuserlist';
        $build['#context']['userlist_container_id'] = $container_prefix;
        $build['#cache']['max-age'] = self::$max_age;
        return $build;
    }

    /**
     * Render the user list and return it to the front end.
     * 
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function fetchUserlist(){
        // Drupal did not like rendering early using Response(render($build))
        // More info: https://www.lullabot.com/articles/early-rendering-a-lesson-in-debugging-drupal-8

        // Get the render array.
        // $build = $this->content();
        // redfine the cache 
        // $build['#cache'] = [
        //     'max-age' => 60, 
        //     'contexts' => [
        //         'url',
        //     ],
        // ];
        // Try either json response or plain text response.
        // $response = new CacheableJsonResponse($data);
        // $response->addCacheableDependency(CacheableMetadata::createFromRenderArray($build));
        // return $response;

        $build = self::content();
        return new Response(render($build));
    }
}