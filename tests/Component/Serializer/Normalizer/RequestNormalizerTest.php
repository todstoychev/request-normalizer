<?php

namespace Todstoychev\RequestNormalizer\Tests\Component\Serializer\Normalizer;

use ArrayObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\LogicException;
use Todstoychev\RequestNormalizer\Component\Serializer\Normalizer\RequestNormalizer;
use PHPUnit\Framework\TestCase;

class RequestNormalizerTest extends TestCase
{
    /**
     * @var RequestNormalizer
     */
    private $normalizer;

    protected function setUp()
    {
        $this->normalizer = new RequestNormalizer();
    }

    public function testNormalize()
    {
        $request = new Request();
        $actual = $this->normalizer->normalize($request);
        $expected = [
            'query' => [],
            'request' => [],
            'attributes' => [],
            'headers' => [],
            'server' => [],
            'cookies' => [],
            'files' => [],
            'content' => '',
            'languages' => [],
            'charsets' => [],
            'encodings' => [],
            'acceptableContentTypes' => [],
            'pathInfo' => '/',
            'requestUri' => '',
            'baseUrl' => '',
            'basePath' => '',
            'method' => 'GET',
        ];

        static::assertEquals($expected, $actual);
    }

    public function testThrowsException()
    {
        static::expectException(LogicException::class);
        $this->normalizer->normalize(new ArrayObject());
    }

    public function testSupportsNormalizationPositive()
    {
        $request = new Request();
        $actual = $this->normalizer->supportsNormalization($request);

        static::assertTrue($actual);
    }

    public function testSupportsNormalizationNegative()
    {
        $actual = $this->normalizer->supportsNormalization(new ArrayObject());

        static::assertFalse($actual);
    }
}
