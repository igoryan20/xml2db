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

        $xmlArray = json_decode($jsonConverted, true);

        return $this->fieldArrayToNull($xmlArray);
    }

    private function fieldArrayToNull(array $xmlArray): array
    {
        foreach ($xmlArray['offers'] as $offer) {
            foreach ($offer as $index => $car) {
                foreach ($car as $field => $value)
                {
                    if (is_array($value)) {
                        $xmlArray['offers']['offer'][$index][$field] = null;
                    }
                }
            }
        }

        return $xmlArray;
    }
}
