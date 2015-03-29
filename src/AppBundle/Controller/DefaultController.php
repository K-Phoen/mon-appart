<?php

namespace AppBundle\Controller;

use Symfony\Component\Templating\EngineInterface as Templating;

class DefaultController
{
    /**
     * @var Templating $templating
     */
    protected $templating;

    public function __construct(Templating $templating)
    {
        $this->templating = $templating;
    }

    public function indexAction()
    {
        return $this->templating->renderResponse('AppBundle:Default:index.html.twig');
    }
}
