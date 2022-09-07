<?php

declare(strict_types=1);

namespace App\Services;

class Xml2ArrayService
{
    /**
     * Get array from xml file located in @path
     *
     * @param string $path
     * @return array
     */
    public function getArrayFromXml(string $path): array
    {
        $xmlFileContent = file_get_contents($path);

        // Convert string to SimpleXmlObject and via json manipulation create array
        $simpleXml = simplexml_load_string($xmlFileContent);
        $jsonConverted = json_encode($simpleXml);

        return json_decode($jsonConverted, true);
    }
}
