<?php

namespace SaintSystems\OData;

class ODataResponseFactory
{
    public static function create(IODataRequest $request, string $body = null, ?string $statusCode = null, array $headers = array())
    {
        $contentType = self::getContentType($headers);

        if (
            $contentType !== null &&
            preg_match('/^multipart\/mixed;\s*boundary=(["\']?)([^"\';]+)\1$/', $contentType)
        ) {
            return new ODataBatchResponse($request, $body, $statusCode, $headers);
        }

        return new ODataResponse($request, $body, $statusCode, $headers);
    }

    private static function getContentType(array $headers): ?string
    {
        foreach ($headers as $key => $value) {
            if ($value !== null && strtolower($key) === 'content-type') {
                return is_array($value) ? $value[0] : $value;
            }
        }

        return null;
    }
}