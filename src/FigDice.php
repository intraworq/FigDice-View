<?php

namespace Slim\Views;

use Psr\Http\Message\ResponseInterface;

class FigDice
{

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
    protected $templatesPath;

    protected $templateLoaded;


    /**
     * Create new FigDice view
     *
     * @param string $templatesPath Path to templates directory
     * @param array $settings Twig environment settings
     */
    public function __construct($templatesPath = '.', $settings = [])
    {
        $this->view = new \figdice\View();
        $this->settings = $settings;
        $this->templatesPath = $templatesPath;
        if (array_key_exists('cache.path', $settings)) {
            $this->view->setTempPath($settings['cache.path']);
        } else {
            $this->view->setTempPath(sys_get_temp_dir());
        }

    }

    /**
     * Returns the underlying FigDice View
     *
     * @return \figdice\View
     */
    puwblic function getView()
    {
        return $this->view;
    }

    /**
     * Binds data to placeholders in template
     *
     * @param $placeholder
     * @param $data
     */
    public function bind($placeholder, $data)
    {
        $this->view->mount($placeholder, $data);
    }

    /**
     * Renders template to the ResponseInterface stream
     *
     * @param ResponseInterface $response
     * @param $template
     * @param array $data
     * @return ResponseInterface
     */
    public function render(ResponseInterface $response, $template, array $data = [])
    {
        $this->view->loadFile($this->templatesPath . DIRECTORY_SEPARATOR . $template);
        foreach ($data as $key => $value) {
            $this->view->mount($key, $value);
        }
        $response->getBody()->write($this->view->render());
        return $response;
    }
}
