<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UrlShortenType.
 *
 * @author Adam Wójs <adam@wojs.pl>
 */
class UrlShortenType extends AbstractType 
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) 
    {
        $builder->add('url', 'url', [
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Url()
            ]
        ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName() 
    {
        return 'url_shorten';
    }
}
