<?php

namespace Steam\Runner;

use Mockery as M;

class GuzzleRunnerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GuzzleRunner
     */
    protected $instance;

    /**
     * @var M\MockInterface
     */
    protected $clientMock;

    /**
     * @var M\MockInterface
     */
    protected $urlBuilderMock;

    /**
     * @var M\MockInterface
     */
    protected $configMock;

    public function setUp()
    {
        $this->clientMock = M::mock('GuzzleHttp\ClientInterface');
        $this->urlBuilderMock = M::mock('Steam\Utility\UrlBuilderInterface');

        $this->instance = new GuzzleRunner($this->clientMock, $this->urlBuilderMock);

        $this->configMock = M::mock('Steam\Configuration', [
            'getBaseSteamApiUrl' => 'http://base.url.com',
        ]);

        $this->instance->setConfig($this->configMock);
    }

    public function testCallingRun()
    {
        $params = ['query' => ['a' => 'bc']];
        $url = 'http://base.url.com/built';

        $commandMock = M::mock('Steam\Command\CommandInterface', [
            'getParams' => $params['query'],
            'getRequestMethod' => 'GET',
        ]);

        $this->configMock->shouldReceive('getSteamKey')->andReturn('');

        $this->urlBuilderMock->shouldReceive('setBaseUrl')->with('http://base.url.com');
        $this->urlBuilderMock->shouldReceive('build')->andReturn($url);

        $response = M::mock('Psr\Http\Message\ResponseInterface');

        $promise = M::mock('GuzzleHttp\Promise\PromiseInterface');
        $promise->shouldReceive('wait')->andReturn($response);

        $this->clientMock->shouldReceive('sendAsync')->with(M::type('Psr\Http\Message\RequestInterface'), $params)->andReturn($promise)->once();

        $this->assertEquals($response, $this->instance->run($commandMock));
    }

    public function testCallingRunThatWillIncludeSteamKey()
    {
        $commandParams = ['a' => 'bc'];
        $url = 'http://base.url.com/built';
        $steamKey = 'test_steam_key';

        $options = ['query' => array_merge($commandParams, [
            'key' => $steamKey,
        ])];

        $commandMock = M::mock('Steam\Command\CommandInterface', [
                'getParams' => $commandParams,
                'getRequestMethod' => 'GET',
            ]);

        $this->configMock->shouldReceive('getSteamKey')->andReturn($steamKey);

        $this->urlBuilderMock->shouldReceive('setBaseUrl')->with('http://base.url.com');
        $this->urlBuilderMock->shouldReceive('build')->andReturn($url);

        $response = M::mock('Psr\Http\Message\ResponseInterface');

        $promise = M::mock('GuzzleHttp\Promise\PromiseInterface');
        $promise->shouldReceive('wait')->andReturn($response);

        $this->clientMock->shouldReceive('sendAsync')->with(M::type('Psr\Http\Message\RequestInterface'), $options)->andReturn($promise)->once();

        $this->assertEquals($response, $this->instance->run($commandMock));
    }
}
 