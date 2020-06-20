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
 */
namespace OpenAPIServer\Model;

use OpenAPIServer\Interfaces\ModelInterface;

/**
 * Name
 *
 * @package OpenAPIServer\Model
 * @author  OpenAPI Generator team
 * @link    https://github.com/openapitools/openapi-generator
 */
class Name implements ModelInterface
{
    private const MODEL_SCHEMA = <<<'SCHEMA'
{
  "required" : [ "name" ],
  "type" : "object",
  "properties" : {
    "name" : {
      "type" : "integer",
      "format" : "int32"
    },
    "snake_case" : {
      "type" : "integer",
      "format" : "int32",
      "readOnly" : true
    },
    "property" : {
      "type" : "string"
    },
    "123Number" : {
      "type" : "integer",
      "readOnly" : true
    }
  },
  "description" : "Model for testing model name same as property name",
  "xml" : {
    "name" : "Name"
  }
}
SCHEMA;

    /** @var int $name */
    private $name;

    /** @var int $snakeCase */
    private $snakeCase;

    /** @var string $property */
    private $property;

    /** @var int $_123number */
    private $_123number;

    /**
     * Returns model schema.
     *
     * @param bool $assoc When TRUE, returned objects will be converted into associative arrays. Default FALSE.
     *
     * @return array
     */
    public static function getOpenApiSchema($assoc = false)
    {
        return json_decode(static::MODEL_SCHEMA, $assoc);
    }
}
