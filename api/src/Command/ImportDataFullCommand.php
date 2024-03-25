<?php

namespace App\Command;

use App\Repository\ElasticsearchRepository\ConstellationRepository;
use App\Repository\ElasticsearchRepository\DsoRepository;
use App\Services\DeepUtf8Encoding;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'import:data:full',
    description: 'Create a bulk file',
)]
class ImportDataFullCommand extends Command
{
    use ImportData;

    protected static array $mapping = [
        'dso20' => DsoRepository::INDEX,
        'constellations' => ConstellationRepository::INDEX
    ];

    public const PATH_SOURCE = '/config/elasticsearch/sources/';
    public const BULK_SOURCE = '/config/elasticsearch/bulk/';

    private string $kernelRoute;

    public function __construct
    (
        private readonly KernelInterface $kernel,
    )
    {
        $this->kernelRoute = $this->kernel->getProjectDir();
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('type', null, InputArgument::REQUIRED, 'Option description: dso20 or constellations')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        if (!$input->getArgument('type')) {
            return Command::INVALID;
        }

        // Input/source file
        $typeData = $input->getArgument('type');
        $inputFile = $this->kernelRoute . self::PATH_SOURCE . sprintf('%s.src.json', $typeData);

        // Output file
        $outputFilename = $this->kernelRoute . self::BULK_SOURCE . sprintf('%s.bulk.json', $typeData);
        $outputDirName = dirname($outputFilename);

        if (!file_exists($outputDirName)) {
            if (!mkdir($concurrentDirectory = dirname($outputFilename), '0755') && !is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
        }

        if (!file_exists($outputFilename)) {
            $fp = fopen($outputFilename, 'w+');
            fclose($fp);
        }

        $handle = fopen($outputFilename, 'wb');
        $data = $this->openFile($inputFile);
        foreach ($data as $inputData) {
            if (!array_key_exists('id', $inputData)) {
                continue;
            }
            $id = $inputData['id'];

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

            $bulkLine = $this->buildCreateLine($typeData, $id);
            fwrite($handle, $bulkLine . PHP_EOL);
            fwrite($handle, mb_convert_encoding($lineReplace, 'ISO-8859-1') . PHP_EOL);
        }

        fclose($handle);

        $io->success(sprintf('Bulk data "%s" have been created', $outputFilename));
        return Command::SUCCESS;
    }

    public function buildCreateLine(string $type, string $id): string
    {
        return  sprintf('{"create": {"_index": "%s", "_type": "_doc", "_id": "%s"}}', self::$mapping[$type], self::md5ForId($id));
    }
}
