<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use ApiPlatform\State\ApiResource\Error;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;


#[AsAlias('api_platform.state.error_provider')]
#[AsTaggedItem('api_platform.state.error_provider')]
class ErrorProvider implements ProviderInterface
{
    /**
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return object|array|null
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $request = $context['request'];
        $format = $request->getRequestFormat();
        $exception = $request->attributes->get('exception');
        $status = $operation->getStatus() ?? 500;

        $error = Error::createFromException($exception, $status);
        $error->setDetail(sprintf('Problem on Astro-Otter API : %s', $exception->getMessage()));

        return $error;
    }


}
