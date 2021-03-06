<?php

declare(strict_types=1);

namespace EonX\EasyErrorHandler\Builders;

use EonX\EasyErrorHandler\Helpers\ErrorDetailsHelper;
use EonX\EasyErrorHandler\Interfaces\ErrorHandlerAwareInterface;
use EonX\EasyErrorHandler\Interfaces\Exceptions\TranslatableExceptionInterface;
use EonX\EasyErrorHandler\Interfaces\TranslatorInterface;
use EonX\EasyErrorHandler\Traits\ErrorHandlerAwareTrait;
use Throwable;

final class ExtendedExceptionBuilder extends AbstractErrorResponseBuilder implements ErrorHandlerAwareInterface
{
    use ErrorHandlerAwareTrait;

    /**
     * @var string
     */
    private $exceptionKey;

    /**
     * @var string[]
     */
    private $keys;

    /**
     * @var \EonX\EasyErrorHandler\Interfaces\TranslatorInterface
     */
    private $translator;

    /**
     * @param null|string[] $keys
     */
    public function __construct(
        TranslatorInterface $translator,
        ?string $exceptionKey = null,
        ?array $keys = null,
        ?int $priority = null
    ) {
        $this->translator = $translator;
        $this->exceptionKey = $exceptionKey ?? 'exception';
        $this->keys = $keys ?? [];

        parent::__construct($priority);
    }

    public function buildData(Throwable $throwable, array $data): array
    {
        // Skip if not verbose
        if ($this->errorHandler->isVerbose() === false) {
            return parent::buildData($throwable, $data);
        }

        $details = ErrorDetailsHelper::getDetails($throwable);

        $exception = [
            $this->getKey('class') => $details['class'],
            $this->getKey('file') => $details['file'],
            $this->getKey('line') => $details['line'],
            $this->getKey('message') => $this->getMessage($throwable),
            $this->getKey('trace') => $details['trace'],
        ];

        $data[$this->exceptionKey] = $exception;

        return parent::buildData($throwable, $data);
    }

    private function getKey(string $name): string
    {
        return $this->keys[$name] ?? $name;
    }

    private function getMessage(Throwable $throwable): string
    {
        if (($throwable instanceof TranslatableExceptionInterface) === false) {
            return $throwable->getMessage();
        }

        return $this->translator->trans($throwable->getMessage(), $throwable->getMessageParams());
    }
}
