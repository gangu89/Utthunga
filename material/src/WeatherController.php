<?php

namespace Drupal\material;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Entity\Query\QueryFactory;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
/**
 * Controller to get Weather results.
 */
class WeatherController extends ControllerBase {
  /**
   * {@inheritdoc}
   */
  protected $entityTypeManager;
 
  /**
   * {@inheritdoc}
   */
  protected $entityQuery;

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, QueryFactory $entityQuery) {
    $this->entityTypeManager = $entityTypeManager;
    $this->entityQuery = $entityQuery;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.manager'),     
      $container->get('entity.query')
    );
  }

  
  public function getWeatherData() {
  
    $base_url = Request::createFromGlobals()->getSchemeAndHttpHost();  

    $client = \Drupal::httpClient();
    $request = $client->get('http://api.openweathermap.org/data/2.5/weather?q=Bengaluru,29,91&appid=3892cb433ba2bcbd975acb2a8b131101');
    $content = json_decode($request->getBody()->getContents());
    // dsm($content);

    if($content) {
      $data = [
         '#theme' => 'weather_api_content',
         '#items' => $content,
         ];
      return $data;
      }else{
      throw new NotFoundHttpExceptuion();
      }


  } 

}
