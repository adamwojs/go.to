<?php

namespace AppBundle\UrlManager;

use AppBundle\Entity\Url;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Domyślna impl. managera URL
 *
 * @author Adam Wójs <adam@wojs.pl>
 */
class UrlManager implements UrlManagerInterface 
{   
    // Maksymalna ilość prób wygenerowania unikatowego
    // identyfikatora dla adresu URL 
    const MAX_ITERATIONS = 16;
    
    // Długość generowanego identyfikatora
    const URL_CODE_LENGTH = 12;
    
    /** @var \Doctrine\ORM\EntityManagerInterface */
    private $em;
    
    /** @var RouterInterface */
    private $router;

    /** @var string */
    private $redirectRoute;
    
    public function __construct(
        EntityManagerInterface $em, 
        RouterInterface $router,
        $redirectRoute) 
    {
        $this->em = $em;
        $this->router = $router;
        $this->redirectRoute = $redirectRoute;
    }
    
    /**
     * {@inheritdoc}
     */
    public function shorten($url) 
    {
        $entity = $this->em
            ->getRepository('AppBundle:Url')
            ->findOneByTarget($url);
        
        if(!$entity)  {
            $entity = new Url();
            $entity->setId($this->generateId($url));   
            $entity->setTarget($url);
            
            $this->em->persist($entity);
            $this->em->flush();
        }
        
        return $this->router->generate($this->redirectRoute, [
            'id' => $entity->getId() 
        ], true);
    }
    
    /**
     * {@inheritdoc}
     */
    public function resolve($id) 
    {
        $url = $this->em
            ->getRepository('AppBundle:Url')
            ->find($id);        
        
        return $url ? $url->getTarget() : null;
    }
    
    /**
     * Zwraca nazwę routa obsługującego przekierowanie 
     * do docelowego adresu.
     * 
     * @return string
     */
    public function getRedirectRoute() {
        return $this->redirectRoute;
    }
    
    /**
     * Generuje unikatowy identyfiator URLa.
     * 
     * @param string $url
     * @return string
     * @throws UrlManagerException
     */
    private function generateId($url) 
    {
        $repository = $this->em->getRepository('AppBundle:Url');
        
        for($i = 0; $i < self::MAX_ITERATIONS; $i++) {
            $hash = md5(uniqid($url, true)); 
            $id = base64_encode(substr($hash, 0, self::URL_CODE_LENGTH));
            
            if(!$repository->find($id)) {
                return $id;
            }
        }
        
        throw new UrlManagerException("Unable to generate unique URL code.");
    }    
}
