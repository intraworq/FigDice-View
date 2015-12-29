<?php

//namespace Slim\Tests\Views;

use Slim\Views\FigDice;

require 'DummyStream.php';

class FigDiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FigDice
     */
    protected $view;

    public function setUp()
    {
        $this->view = new FigDice(__DIR__ . DIRECTORY_SEPARATOR . 'templates');
    }

    public function testRenderWithoutVariables()
    {
        $mockResponse = $this->getMockBuilder('Psr\Http\Message\ResponseInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $body = new DummyStream();
        $mockResponse->expects($this->once())
            ->method('getBody')
            ->willReturn($body);
        $response = $this->view->render($mockResponse, 'template1.html');
        $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $response);
        $expected = <<<EOT
        <!doctype html>
        <html lang="en">
	    <head>
	    </head>
		<body>
		    <div>
			    Welcome, .
			</div>
			<span class="myclass"></span>
	 	</body>
	    </html>
EOT;

        $this->assertEquals(preg_replace('/\s+/', '', $expected), preg_replace('/\s+/', '', $body->__toString()));
    }

    public function testRenderWithVariablesBinding()
    {
        $mockResponse = $this->getMockBuilder('Psr\Http\Message\ResponseInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $body = new DummyStream();
        $mockResponse->expects($this->once())
            ->method('getBody')
            ->willReturn($body);
        $user = ['user' => [
            'name' => 'Bolek',
            'email' => 'bolek@vault13.pl'
        ]
        ];
        $response = $this->view->render($mockResponse, 'template1.html', $user);
        $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $response);
        $expected = <<<EOT
        <!doctype html>
        <html lang="en">
	    <head>
	    </head>
		<body>
		    <div>
			    Welcome, Bolek.
			</div>
			<span class="myclass">bolek@vault13.pl</span>
	 	</body>
	    </html>
EOT;

        $this->assertEquals(preg_replace('/\s+/', '', $expected), preg_replace('/\s+/', '', $body->__toString()));
    }

    public function testRenderingIncludedTemplates()
    {
        $mockResponse = $this->getMockBuilder('Psr\Http\Message\ResponseInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $body = new DummyStream();
        $mockResponse->expects($this->once())
            ->method('getBody')
            ->willReturn($body);
        $response = $this->view->render($mockResponse, 'template2.html');
        $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $response);
        $this->assertEquals(1, preg_match("/Contact Us/", $body->__toString(), $matches, PREG_OFFSET_CAPTURE), "Template should include footer section.");
    }

    public function testConditionalRendering()
    {
        $mockResponse = $this->getMockBuilder('Psr\Http\Message\ResponseInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $body = new DummyStream();
        $mockResponse->expects($this->any())
            ->method('getBody')
            ->willReturn($body);
        $response = $this->view->render($mockResponse, 'template2.html', ['isLogged' => false]);
        $this->assertInstanceOf('Psr\Http\Message\ResponseInterface', $response);
        $this->assertEquals(1, preg_match("/sign in/", $body->__toString(), $matches, PREG_OFFSET_CAPTURE), "Template should include 'sign in' for not logged in users.");
        $body->rewind();
        $this->view->bind('isLogged', true);
        $this->view->render($mockResponse, 'template2.html');
        $this->assertEquals(1, preg_match("/Welcome back/", $body->__toString(), $matches, PREG_OFFSET_CAPTURE), "Template should include 'Welcome back' for logged in users.");
    }

    public function testPluggingContentIntoSlots()
    {
        $mockResponse = $this->getMockBuilder('Psr\Http\Message\ResponseInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $body = new DummyStream();
        $mockResponse->expects($this->once())
            ->method('getBody')
            ->willReturn($body);
        $this->view->render($mockResponse, 'content.html');
        $this->assertEquals(1, preg_match("/Actual title/", $body->__toString(), $matches, PREG_OFFSET_CAPTURE), "Template should include 'Welcome back' for logged in users.");
    }

    public function testRenderTemplateFromString()
    {
        $mockResponse = $this->getMockBuilder('Psr\Http\Message\ResponseInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $body = new DummyStream();
        $mockResponse->expects($this->once())
            ->method('getBody')
            ->willReturn($body);
        $template = <<<TEMPLATE
<fig:template>
<slot fig:slot="myslot">slot, would get removed</slot>
<fig:mute fig:plug="myslot">Plugged!</fig:mute>
</fig:template>
TEMPLATE;

        $this->view->renderFromString($mockResponse, $template);
        $this->assertEquals(1, preg_match("/Plugged!/", $body->__toString(), $matches, PREG_OFFSET_CAPTURE), "Template should correctly render from string");
    }
}

