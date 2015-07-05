<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Url;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * DefaultController.
 * 
 * @author Adam Wójs <adam@wojs.pl>
 */
class DefaultController extends Controller
{
    /**
     * Akacja wyświetlająca formularz skracania URLi.
     * 
     * @Route("/", name="homepage")
     * @Method({"GET"})
     * @Template("default/index.html.twig")
     */
    public function formAction()
    {
        $form = $this->createShortenForm();
        
        return [
            'form' => $form->createView()
        ];
    }

    /**
     * Akcja skrająca URL.
     * 
     * @Route("/", name="shorten")
     * @Method({"POST"})
     * @Template("default/index.html.twig")
     */    
    public function shortenAction(Request $request) 
    {
        $form = $this->createShortenForm();
        $form->handleRequest($request);
        if($form->isValid()) {
            $url = $this
                ->getUrlManager()
                ->shorten($form['url']->getData());
            
            return new JsonResponse([
                'url' => $url
            ]);
        }
        
        return [
            'form' => $form->createView()
        ];        
    }
    
    /**
     * Akcja przekierowująca do docelowego adresu.
     * 
     * @param string $id
     * 
     * @Route("/{id}", name="redirect")
     * @Method({"GET"})
     */
    public function redirectAction($id) 
    {
        $url = $this->getUrlManager()->resolve($id);
        
        if(!$url) {
            throw $this->createNotFoundException();
        }
        
        return $this->redirect($url);
    }    
    
    /**
     * Tworzy formularz skracania URLa.
     * 
     * @param Url $data
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createShortenForm(Url $data = null) 
    {
        return $this->createForm('url_shorten', $data, [
            'action' => $this->generateUrl('shorten'),
            'method' => 'POST'
        ]);
    }
    
    /**
     * @return \AppBundle\UrlManager\UrlManagerInterface
     */
    private function getUrlManager() {
        return $this->get('url.manager');
    }
}
