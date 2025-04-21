<?php

namespace App\Utils;

class Response
{
    public static function response(
        int $code = 200,
        ?string $title = '',
        ?string $message = '',
        array $data = [],
        array $meta = [],
        ?string $functionName = '',
        ?string $messageError = ''
    ): array {
        $type = self::resolveType($code);
        $status = $type === 'success';

        return [
            'status' => $status,
            'type' => $type,
            'code' => $code,
            'title' => $title,
            'message' => $message,
            'messageError' => $messageError,
            'functionName' => $functionName,
            'data' => $data,
            'meta' => $meta,
        ];
    }

    protected static function resolveType(int $code): string
    {
        return match (true) {
            $code >= 100 && $code < 200 => 'informational',
            $code >= 200 && $code < 300 => 'success',
            $code >= 300 && $code < 400 => 'redirect',
            $code >= 400 && $code < 500 => 'client_error',
            $code >= 500 && $code < 600 => 'server_error',
            default => 'unknown',
        };
    }
}
