<?php

namespace App\Component\Common\Service;

use Ramsey\Uuid\Uuid;

class UuidGenerator
{
    /**
     * @return string
     * @throws \Exception
     */
    public function generateV4(): string
    {
        return Uuid::uuid4()->toString();
    }
}
