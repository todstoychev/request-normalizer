<?php

declare(strict_types=1);

namespace Todstoychev\RequestNormalizer\Component\Serializer\Normalizer;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use function get_class;

class RequestNormalizer implements NormalizerInterface
{
    /**
     * Normalizes an object into a set of arrays/scalars.
     *
     * @param mixed  $object  Object to normalize
     * @param string $format  Format the normalization result will be encoded as
     * @param array  $context Context options for the normalizer
     *
     * @throws InvalidArgumentException   Occurs when the object given is not an attempted type for the normalizer
     * @throws CircularReferenceException Occurs when the normalizer detects a circular reference when no circular
     *                                    reference handler can fix it
     * @throws LogicException             Occurs when the normalizer is not called in an expected context
     * @throws ExceptionInterface         Occurs for all the other cases of errors
     *
     * @return array|bool|float|int|string
     */
    public function normalize($object, $format = null, array $context = [])
    {
        if (!$this->supportsNormalization($object)) {
            $type = get_class($object);
            $supported = Request::class;
            $msg = "Object of type {$type} does not support normalization!"
                ."Supported type {$supported}.";

            throw new LogicException($msg);
        }

        return [
            'query' => $object->query->all(),
            'request' => $object->request->all(),
            'attributes' => $object->attributes->all(),
            'headers' => $object->headers->all(),
            'server' => $object->server->all(),
            'cookies' => $object->cookies->all(),
            'files' => $object->files->all(),
            'content' => $object->getContent(),
            'languages' => $object->getLanguages(),
            'charsets' => $object->getCharsets(),
            'encodings' => $object->getEncodings(),
            'acceptableContentTypes' => $object->getAcceptableContentTypes(),
            'pathInfo' => $object->getPathInfo(),
            'requestUri' => $object->getRequestUri(),
            'baseUrl' => $object->getBaseUrl(),
            'basePath' => $object->getBasePath(),
            'method' => $object->getMethod()
        ];
    }

    /**
     * Checks whether the given class is supported for normalization by this normalizer.
     *
     * @param mixed  $data   Data to normalize
     * @param string $format The format being (de-)serialized from or into
     *
     * @return bool
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Request;
    }
}
