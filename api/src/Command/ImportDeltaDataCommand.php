<?php

namespace App\Command;

use App\Entity\UpdateData;
use App\Repository\ElasticsearchRepository\ConstellationRepository;
use App\Repository\ElasticsearchRepository\DsoRepository;
use App\Services\DateSanitization;
use App\Services\DeepUtf8Encoding;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'import:data:delta',
    description: 'Insert or update documents',
)]
class ImportDeltaDataCommand extends Command
{
    use ImportData;

    protected static array $mapping = [
        'dso20' => DsoRepository::INDEX,
        'constellations' => ConstellationRepository::INDEX
    ];

    private \DateTimeInterface $lastImportDate;
    private string $kernelRoute;
    public const PATH_SOURCE = '/config/elasticsearch/sources/';
    public const BULK_SOURCE = '/config/elasticsearch/bulk/';

    public const MODE_CREATE_DOCUMENT = 'create_document';
    public const  MODE_UPDATE_DOCUMENT = 'update_document';

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly KernelInterface $kernel,
        private readonly DsoRepository $dsoRepository
    )
    {
        $this->kernelRoute = $this->kernel->getProjectDir();
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('type', 'i', InputArgument::REQUIRED, 'Option description: dso20 or constellations')
        ;
    }

    public function getLastImportDate(): \DateTimeInterface
    {
        return $this->lastImportDate;
    }

    public function setLastImportDate(\DateTimeInterface $lastImportDate): ImportDeltaDataCommand
    {
        $this->lastImportDate = $lastImportDate;
        return $this;
    }

    /**
     * @throws \JsonException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Check last import date
        $lastDateImport = $this->em->getRepository(UpdateData::class)->findOneBy([], ['date' => 'desc']);
        $lastImportDate = $lastDateImport->getDate() ?? new \DateTime('now');
        $lastImportDate->setTimezone(new \DateTimeZone('Europe/Paris'));
        $this->setLastImportDate($lastImportDate);

        if (!$input->getArgument('type')) {
            return Command::INVALID;
        }

        // Input/source file
        $typeData = $input->getArgument('type');
        $inputFile = $this->kernelRoute . self::PATH_SOURCE . sprintf('%s.src.json', $typeData);

        if (!is_readable($inputFile)) {
            return Command::INVALID;
        }

        $data = $this->openFile($inputFile);
        if (JSON_ERROR_NONE !== json_last_error()) {
            return Command::INVALID;
        }

        /**
         * STEP 1 : build data bulk
         */
        $bulk = $this->buildBulkData($data);

        /**
         * STEP 2: import data
         */
        if (is_array($bulk)) {
            dump($bulk);
            foreach ($bulk as $document) {
                $this->dsoRepository->insertDocument($document);
            }

        }
        return Command::SUCCESS;
    }

    /**
     * @throws \JsonException
     */


    /**
     * @throws \JsonException
     */
    private function buildBulkData(array $data)
    {
        $bulkData = [];
        foreach ($data as $inputData) {
            if (!array_key_exists('id', $inputData)) {
                continue;
            }
            $id = $inputData['id'];

            if (array_key_exists('updated_at', $inputData) && !is_null($inputData['updated_at'])) {
                $mode = self::MODE_UPDATE_DOCUMENT;
            } else {
                $newUpdatedAt = new \DateTime();
                $mode = self::MODE_CREATE_DOCUMENT;
                $inputData['updated_at'] = $newUpdatedAt->format(DateSanitization::FORMAT_DATE_ES);
            }

            $deepUtf8Encoding = new DeepUtf8Encoding;
            $line = json_encode($deepUtf8Encoding($inputData), JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

            $mapping = [
                'randId' => 'md5ForId',
                'catalog' => 'getCatalog',
                'order' => 'getItemOrder'
            ];
            $lineReplace = preg_replace_callback('#%(.*?)%#', static function($match) use ($mapping, $id) {
                $findKey = $match[1];
                if (array_key_exists($findKey, $mapping)) {
                    $method = $mapping[$findKey];
                    return self::$method($id);
                }

                return "%s" . $findKey . "%s"; // why %s ?
            }, $line);

            if (self::MODE_UPDATE_DOCUMENT === $mode) {
                $lastUpdateData = \DateTime::createFromFormat(DateSanitization::FORMAT_DATE_ES, $inputData['updated_at']);
                if (0 !== $this->getLastImportDate()->diff($lastUpdateData)->invert) {
                    continue;
                }
            }
            $bulkData[] = [
                'idDoc' => self::md5ForId($id),
                'mode' => $mode,
                'data' => json_decode(mb_convert_encoding($lineReplace, 'ISO-8859-1'), true, 512, JSON_THROW_ON_ERROR)
            ];
        }

        return $bulkData;
    }



}
