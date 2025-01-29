<?php

declare(strict_types=1);

namespace App;

class CsvReader
{
    public static function readCsv(string $file): array
    {
        if (!file_exists($file)) {
            throw new \Exception("File not found : $file");
        }
        $csv = array_map('str_getcsv', file($file));
        $header = array_shift($csv);
        $data = [];
        foreach ($csv as $row) {
            $data[] = array_combine($header, $row);
        }

        return $data;
    }
}
