<?php

namespace Controller\CsvController;

use Controller\CsvController\helpers\Total;
use Core\Database\Database;
use Exception;

class CsvService
{

    public function __construct(private Database $databaseService) {}

    public function read_csv(string $csv)
    {
        try {
            $fileName = __DIR__ . "/assets" . '/' . $csv;
            if (!file_exists($fileName)) {
                throw new Exception('File not found.');
            }

            $handle = fopen(__DIR__ . "/assets" . '/' . $csv,  "r");
        } catch (Exception $e) {
            return [
                "view" => "error.php",
                "fileName" => '/' . $csv,
                "message" => $e->getMessage(),
            ];
        }
        $csv = [];
        while (($row = fgetcsv($handle)) !== FALSE) {
            array_push($csv, $row);
        }

        $headers = $csv[0];

        array_shift($csv);

        fclose($handle);

        foreach ($csv as $key => $value) {
            $csv[$key][4] = $value[3][0] === '-' ? 'negative' : 'positive';
        }

        $total_class = $this->total($csv);

        return [
            "view" => "transactions.blade.php",
            "message" => "User created successfully",
            'headers' => $headers,
            'csv' => $csv,
            'total' => $total_class->get_total(),
            'total_positive' => $total_class->get_total_positive(),
            'total_negative' => $total_class->get_total_negative(),
        ];
    }

    /** 
     * @param string[] $csv
     * 
     * @return Total 
     */
    public function total(array $csv): Total
    {

        $totals = [
            'total' => 0,
            'total_positive' => 0,
            'total_negative' => 0
        ];

        foreach ($csv as $value) {
            $amount = explode('$', $value[3]);
            $sign = $amount[0];
            $num = $amount[1];
            $num_formatted = (float) str_replace(',', '', $num);

            if ($sign === '-') {
                $amount = explode('$', $value[3])[1];
                $num_formatted = -1 * abs($num_formatted);
            }

            $totals['total'] += $num_formatted;

            if ($num_formatted > 0) {
                $totals['total_positive'] += $num_formatted;
            } else {
                $totals['total_negative'] += $num_formatted;
            }
        }
        return new Total($totals['total'], $totals['total_positive'], $totals['total_negative']);
    }
}
