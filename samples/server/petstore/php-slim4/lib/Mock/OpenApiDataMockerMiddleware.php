<?php

/**
 * OpenAPI Petstore
 * PHP version 7.2
 *
 * @package OpenAPIServer
 * @author  OpenAPI Generator team
 * @link    https://github.com/openapitools/openapi-generator
 */

/**
 * This spec is mainly for testing Petstore server and contains fake endpoints, models. Please do not use this for any other purpose. Special characters: \" \\
 * The version of the OpenAPI document: 1.0.0
 * Generated by: https://github.com/openapitools/openapi-generator.git
 */

/**
 * NOTE: This class is auto generated by the openapi generator program.
 * https://github.com/openapitools/openapi-generator
 * Do not edit the class manually.
 */
namespace OpenAPIServer\Mock;

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use OpenAPIServer\Mock\OpenApiDataMockerInterface;
use InvalidArgumentException;

/**
 * OpenApiDataMockerMiddleware Class Doc Comment
 *
 * @package OpenAPIServer\Mock
 * @author  OpenAPI Generator team
 * @link    https://github.com/openapitools/openapi-generator
 */
final class OpenApiDataMockerMiddleware implements MiddlewareInterface
{
    /**
     * @var OpenApiDataMockerInterface DataMocker.
     */
    private $mocker;

    /**
     * @var array Array of responses schemas.
     */
    private $responses;

    /**
     * @var callable|null Custom callback to select mocked response.
     */
    private $getMockResponseCallback;

    /**
     * @var callable|null Custom after callback.
     */
    private $afterCallback;

    /**
     * Class constructor.
     *
     * @param OpenApiDataMockerInterface $mocker                  DataMocker.
     * @param array                      $responses               Array of responses schemas.
     * @param callable|null              $getMockResponseCallback Custom callback to select mocked response.
     * Mock feature is disabled when this argument is null.
     * @example $getMockResponseCallback = function (ServerRequestInterface $request, array $responses) {
     *     // check if client clearly asks for mocked response
     *     if (
     *         $request->hasHeader('X-OpenAPIServer-Mock')
     *         && $request->header('X-OpenAPIServer-Mock')[0] === 'ping'
     *     ) {
     *         return $responses[array_key_first($responses)];
     *     }
     *     return false;
     * };
     * @param callable|null              $afterCallback           After callback.
     * Function must return response instance.
     * @example $afterCallback = function (ServerRequestInterface $request, ResponseInterface $response) {
     *     // mark mocked response to distinguish real and fake responses
     *     return $response->withHeader('X-OpenAPIServer-Mock', 'pong');
     * };
     */
    public function __construct(
        OpenApiDataMockerInterface $mocker,
        array $responses,
        $getMockResponseCallback = null,
        $afterCallback = null
    ) {
        $this->mocker = $mocker;
        $this->responses = $responses;
        if (is_callable($getMockResponseCallback)) {
            $this->getMockResponseCallback = $getMockResponseCallback;
        } elseif ($getMockResponseCallback !== null) {
            // wrong argument type
            throw new InvalidArgumentException('\$getMockResponseCallback must be closure or null');
        }

        if (is_callable($afterCallback)) {
            $this->afterCallback = $afterCallback;
        } elseif ($afterCallback !== null) {
            // wrong argument type
            throw new InvalidArgumentException('\$afterCallback must be closure or null');
        }
    }

    /**
     * Parse incoming JSON input into a native PHP format
     *
     * @param ServerRequestInterface  $request HTTP request
     * @param RequestHandlerInterface $handler Request handler
     *
     * @return ResponseInterface HTTP response
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $customCallback = $this->getMockResponseCallback;
        $customAfterCallback = $this->afterCallback;
        $mockedResponse = (is_callable($customCallback)) ? $customCallback($request, $this->responses) : null;
        if (
            is_array($mockedResponse)
            && array_key_exists('code', $mockedResponse)
            && array_key_exists('jsonSchema', $mockedResponse)
        ) {
            // response schema succesfully selected, we can mock it now
            $statusCode = ($mockedResponse['code'] === 0) ? 200 : $mockedResponse['code'];
            $contentType = '*/*';
            $response = AppFactory::determineResponseFactory()->createResponse($statusCode);
            $responseSchema = json_decode($mockedResponse['jsonSchema'], true);

            if (is_array($responseSchema) && array_key_exists('headers', $responseSchema)) {
                // response schema contains headers definitions, apply them one by one
                foreach ($responseSchema['headers'] as $headerName => $headerDefinition) {
                    $response = $response->withHeader($headerName, $this->mocker->mockFromSchema($headerDefinition['schema']));
                }
            }

            if (
                is_array($responseSchema)
                && array_key_exists('content', $responseSchema)
                && !empty($responseSchema['content'])
            ) {
                // response schema contains body definition
                $responseContentSchema = null;
                foreach ($responseSchema['content'] as $schemaContentType => $schemaDefinition) {
                    // we can respond in JSON format when any(*/*) content-type allowed
                    // or JSON(application/json) content-type specifically defined
                    if (
                        $schemaContentType === '*/*'
                        || strtolower(substr($schemaContentType, 0, 16)) === 'application/json'
                    ) {
                        $contentType = 'application/json';
                        $responseContentSchema = $schemaDefinition['schema'];
                    }
                }

                if ($contentType === 'application/json') {
                    $responseBody = $this->mocker->mockFromSchema($responseContentSchema);
                    $response->getBody()->write(json_encode($responseBody));
                } else {
                    // notify developer that only application/json response supported so far
                    $response->getBody()->write('Mock feature supports only "application/json" content-type!');
                }
            }

            // after callback applied only when mocked response schema has been selected
            if (is_callable($customAfterCallback)) {
                $response = $customAfterCallback($request, $response);
            }

            // no reason to execute following middlewares (auth, validation etc.)
            // return mocked response and end connection
            return $response
                ->withHeader('Content-Type', $contentType);
        }

        // no response selected, mock feature disabled
        // execute following middlewares
        return $handler->handle($request);
    }
}
