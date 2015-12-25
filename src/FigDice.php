<?php

namespace Slim\Views;

use Psr\Http\Message\ResponseInterface;

class FigDice {

	/**
     * Default view variables
     *
     * @var array
     */
    protected $defaultVariables = [];

    /**
     * FigDice view instance
     * 
     * @var \figdice\View
     */
    protected $view;

    /**
     * FigDice settings
     * 
     * @var array
     */
    protected $settings;

    /**
     * Templates directory path
     *
     * @var string
     */
    protected $path;


    /**
     * Create new FigDice view
     *
     * @param string $path     Path to templates directory
     * @param array  $settings Twig environment settings
     */
    public function __construct($path = '.', $settings = [])
    {
        $this->view = new \figdice\View();
        $this->settings = $settings;
        $this->path = $path;
    }


    public function fetch($template)
    {
    	$this->view->loadFile($this->path . DIRECTORY_SEPARATOR . $template);
    }

    public function bind($placeholder, $data)
    {
    	$this->view->mount($placeholder, $data);
    }

    public function render(ResponseInterface $response, $template)
    {
        $response->getBody()->write($this->view->render());
        return $response;
    }
}
