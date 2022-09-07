<?php

namespace App\Console\Commands;

use App\Services\PathResolverService;
use App\Services\Xml2ArrayService;
use App\Services\Array2DbService;
use Illuminate\Console\Command;

class Xml2DbCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xml2db:send {path=./files/data.xml}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Xml file data to db';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle (
        Array2DbService $array2Db,
        PathResolverService $pathResolver,
        Xml2ArrayService $xml2Array
    ) :int
    {
        $path = $this->argument('path');

        if(!$pathResolver->resolve($path)) {
            return 0;
        }
        $dataArray = $xml2Array->getArrayFromXml($path);
        $array2Db->sendArray2Db($dataArray);

        return 1;
    }
}
