<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Search;
use App\Repository\ElasticsearchRepository\ConstellationRepository;
use App\Repository\ElasticsearchRepository\DsoRepository;
use App\Services\Factory\ConstellationFactory;
use App\Services\Factory\DsoFactory;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

readonly class SearchProcessor implements ProcessorInterface
{
    public function __construct(
        private DsoRepository           $dsoRepository,
        private DsoFactory $dsoFactory,
        private ConstellationRepository $constellationRepository,
        private ConstellationFactory $constellationFactory
    ) { }

    /**
     * @param mixed $data
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return \Generator
     * @throws ExceptionInterface
     * @throws InvalidArgumentException
     * @throws \JsonException
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): \Generator
    {
        if (!$data instanceof Search) {
            throw new \Error();
        }

        $classMetadataFactory = new ClassMetadataFactory(new AttributeLoader(/*new AnnotationReader()*/));
        $normalizers = [new ObjectNormalizer($classMetadataFactory)];
        $encoders = [new JsonEncoder()];
        $serializer = new Serializer($normalizers, $encoders);

        $dsoDocuments = $this->dsoRepository->findBySearchTerms($data->getTerms());
        foreach ($dsoDocuments as $document) {
            $dsoFunc = fn () => yield from $this->dsoFactory->buildDto($document);
            $dso = $dsoFunc()->current();
            $dso->type = 'dso';
            yield $serializer->normalize($dso, null, ['groups' => 'search']);
        }

        $constDocuments = $this->constellationRepository->findBySearchTerms($data->getTerms());
        foreach ($constDocuments as $document) {
            $constFunc = fn () => yield from $this->constellationFactory->buildDto($document);
            $constellation = $constFunc()->current();
            $constellation->type = 'constellation';
            yield $serializer->normalize($constellation, null, ['groups' => 'search']);
        }
    }
}
