<?php

namespace App\Controller;

use App\Component\Validation\Exception\InvalidJsonFormatException;
use App\Component\Validation\Exception\MissingBodyException;
use Symfony\Component\HttpFoundation\Request;

trait ControllerHelper
{
    /**
     * @param Request $request
     * @return array
     * @throws InvalidJsonFormatException
     * @throws MissingBodyException
     */
    public function parseJsonFromRequest(Request $request): array
    {
        $content = $request->getContent();

        if (null === $request) {
            throw new MissingBodyException();
        }
        try {
            $parsed = json_decode($content, true);
        } catch (\Exception $exception) {
            throw new InvalidJsonFormatException($exception);
        }

        if (null === $parsed) {
            throw new MissingBodyException();
        }
        return $parsed;
    }
}
