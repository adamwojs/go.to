<?php

namespace AppBundle\UrlManager;

/**
 * Interface managera realizującego logikę związaną 
 * z skracaniem URLi oraz rozwiązywaniem ich.
 *
 * @author Adam Wójs <adam@wojs.pl>
 */
interface UrlManagerInterface 
{
    /**
     * Skraca adres URL.
     * 
     * @param string $url Adres URL
     * @return string
     */
    public function shorten($url);
    
    /**
     * Zwraca orygonalną postać adresu URL.
     * 
     * @param string $code
     * @return string
     */
    public function resolve($code);
}
