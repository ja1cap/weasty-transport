<?php

namespace Weasty\Bundle\TransportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 * @package Weasty\Bundle\TransportBundle\Controller
 */
class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('WeastyTransportBundle:Default:index.html.twig');
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function itemsAction( Request $request ){

        $repository = $this->getRepository();

        $criteria = $request->get('criteria', []);
        $orderBy = $request->get('orderBy', ['dateCreated' => 'DESC']);
        $limit = $request->get('limit', 10);
        $offset = $request->get('offset', 0);

        $entities = $repository->findBy($criteria, $orderBy, $limit, $offset);

        return new JsonResponse($entities);

    }

    /**
     * @param $type
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function itemsByTypeAction( $type, Request $request ){

        /**
         * @var $classMetadata \Doctrine\ORM\Mapping\ClassMetadataInfo
         */
        $classMetadata = $this->getDoctrine()->getManager()->getClassMetadata('WeastyTransportBundle:TransportInfo');
        if(!isset($classMetadata->discriminatorMap[$type])){
            throw $this->createNotFoundException(sprintf('type[%s] not found'));
        }

        /**
         * @var $repository \Weasty\Bundle\TransportBundle\Entity\TransportInfoRepository
         */
        $repository = $this->getDoctrine()->getRepository($classMetadata->discriminatorMap[$type]);

        $criteria = $request->get('criteria', []);
        $orderBy = $request->get('orderBy', ['dateCreated' => 'DESC']);
        $limit = $request->get('limit', 10);
        $offset = $request->get('offset', 0);

        $entities = $repository->findBy($criteria, $orderBy, $limit, $offset);

        return new JsonResponse($entities);

    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function searchAction( Request $request ){

        $query = (string)$request->get('query');
        $types = $request->get('types');
        $limit = (int)$request->get('limit', 10);
        $offset = (int)$request->get('offset', 0);

        /**
         * @var $repository \Weasty\Bundle\TransportBundle\Entity\TransportInfoRepository
         */
        $repository = $this->getDoctrine()->getRepository('WeastyTransportBundle:TransportInfo');
        $entities = $repository->search($query, $types, $limit, $offset);

        return new JsonResponse($entities);

    }

    /**
     * @return JsonResponse
     */
    public function launchParserAction(){

        $parsers = [
          'city_routes_info',
          'village_routes_info',
          'intercity_routes_info',
          'international_routes_info',
          'holiday_transport_info',
          'operative_info',
        ];

        $responseData = [];

        foreach($parsers as $parserName){

            $serviceName = sprintf('weasty_transport.%s.feed.parser', $parserName);
            /**
             * @var $parser \Weasty\Bundle\TransportBundle\Parser\TransportInfoFeedParser
             */
            $parser = $this->get($serviceName);
            $entities = $parser->parse();
            $responseData[$parserName] = sprintf('%s - posts parsed', count($entities));

        }

        return new JsonResponse($responseData);

    }

    /**
     * @return \Weasty\Bundle\TransportBundle\Entity\OperativeInfoRepository
     */
    public function getRepository() {
        return $this->get('weasty_transport.operative_info.repository');
    }

}
