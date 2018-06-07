<?php
namespace Stario\Iwrench\Reaction;

use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Used to return a uniform API response format
 */
class Reaction
{

    private static $statusCode;

    public static function withNoContent()
    {
        return static::setStatusCode(
            HttpResponse::HTTP_NO_CONTENT
        )->json();
    }

    public static function withSuccess($message = '操作成功')
    {
        return static::setStatusCode(
            HttpResponse::HTTP_OK
        )->withMessage($message);
    }

    public static function withNotFound($message = 'Not Found')
    {
        return static::setStatusCode(
            HttpResponse::HTTP_NOT_FOUND
        )->withMessage($message);
    }

    public static function withSeeOther($message = '验证失败，请核对相关凭据')
    {
        return static::setStatusCode(
            HttpResponse::HTTP_SEE_OTHER
        )->withMessage($message);
    }

    public static function withBadRequest($message = 'Bad Request')
    {
        return static::setStatusCode(
            HttpResponse::HTTP_BAD_REQUEST
        )->withMessage($message);
    }

    public static function withUnauthorized($message = '验证失败，请核对相关凭据')
    {
        return static::setStatusCode(
            HttpResponse::HTTP_UNAUTHORIZED
        )->withMessage($message);
    }

    public static function withRefreshTokenFailed($message = '刷新token失败')
    {
        return static::setStatusCode(
            HttpResponse::HTTP_NON_AUTHORITATIVE_INFORMATION
        )->withMessage($message);
    }

    public static function withForbidden($message = '操作被禁止')
    {
        return static::setStatusCode(
            HttpResponse::HTTP_FORBIDDEN
        )->withMessage($message);
    }

    public static function withMessage($message)
    {
        return static::json([
            'messages' => $message,
        ]);
    }

    public static function withInternalServer($message = 'Internal Server Error')
    {
        return static::setStatusCode(
            HttpResponse::HTTP_INTERNAL_SERVER_ERROR
        )->withMessage($message);
    }

    public static function withUnprocessableEntity($message = '验证失败，请核对相关凭据')
    {
        return static::setStatusCode(
            HttpResponse::HTTP_UNPROCESSABLE_ENTITY
        )->withMessage($message);
    }

    protected static function setStatusCode($statusCode)
    {
        static::$statusCode = $statusCode;
        return new static();
    }

    protected static function getStatusCode()
    {
        return static::statusCode;
    }

    protected static function json($data = [])
    {
        return response()->json($data, static::$statusCode);
    }
}
