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
        $orderBy = $request->get('orderBy', ['id' => 'DESC']);
        $limit = $request->get('limit', 10);
        $offset = $request->get('offset', 0);

        $entities = $repository->findBy($criteria, $orderBy, $limit, $offset);

        return new JsonResponse($entities);

    }

    /**
     * @return JsonResponse
     */
    public function launchParserAction(){
        /**
         * @var $parser \Weasty\Bundle\TransportBundle\OperativeInfo\OperativeInfoFeedParser
         */
        $parser = $this->get('weasty_transport.operative_info.feed.parser');
        $entities = $parser->parse();

        return new JsonResponse([
            'info' => sprintf('%s - operations information posts parsed', count($entities))
        ]);

    }

    /**
     * @return \Weasty\Bundle\TransportBundle\Entity\OperativeInfoRepository
     */
    public function getRepository() {
        return $this->get('weasty_transport.operative_info.repository');
    }

}
