<?php

namespace Controller\CsvController\helpers;

class Total
{
    public function __construct(private float $total, private float $total_positive, private float $total_negative)
    {
    }

    public function get_total(): float
    {
        return $this->total;
    }

    public function get_total_positive(): float
    {
        return $this->total_positive;
    }

    public function get_total_negative(): float
    {
        return $this->total_negative;
    }
}
