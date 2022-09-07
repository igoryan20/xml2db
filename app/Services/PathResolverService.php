<?php

declare(strict_types=1);

namespace App\Services;

class PathResolverService
{
    public function resolve(string $path): bool
    {
        if (is_file($path)) {
            if (pathinfo($path)['extension'] == 'xml') {
                return true;
            } else {
                echo 'Файл имеет другое расширение (не .xml)';
                return false;
            }
        } else {
            echo "Неверный путь к файлу";
            return false;
        }
    }
}
