<?php
namespace Merlin\IntrusionDetection\Api;

use Magento\Framework\App\RequestInterface;

interface DetectorInterface
{
    /**
     * Return [isHit, severity, details]
     * - isHit: bool
     * - severity: low|medium|high|critical
     * - details: string
     */
    public function inspect(RequestInterface $request): array;

    /** Unique machine name of detector */
    public function getName(): string;
}
