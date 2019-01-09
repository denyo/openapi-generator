# Dockerfile for running OpenApi Generator via CLI

## Build
```
docker build -t denyo/openapi-generator-cli .
docker push denyo/openapi-generator-cli
```

## Usage
```
docker run -it denyo/openapi-generator-cli config-help -g csharp
```

```
docker run --rm -v ${PWD}/src/proxy:/swagger-api/out denyo/openapi-generator-cli generate \
    -i https://test.skillmatching.cloud.audi/v2/api-docs \
    -g typescript-fetch --additional-properties supportsES6=true \
    -o /swagger-api/out
```