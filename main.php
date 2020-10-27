<?php

include 'parsing.php';

class Computor
{
    public $maxDegree;

    public $numbers;

    public $parser;

    public function __construct($argv)
    {
        $this->parser = new Parser($argv);
        $this->parser->parse();
        $this->numbers = $this->parser->numbers;
    }

    public function getReducedForm()
    {
        $reduced = 'Reduced form:';
        foreach ($this->numbers as $key => $value) {
            if ($value != 0) {
                $reduced .= sprintf(' %s %s * X^%s', $value > 0 && $key > 0 ? '+' : '', $value, $key);
            }
        }
        $reduced .= ' = 0';

        return $reduced . PHP_EOL;
    }

    public function getMaxDegree()
    {
        if (!isset($this->maxDegree)) {
            $this->maxDegree = max(array_keys($this->numbers));
        }
        return $this->maxDegree;
    }

    public function printInfo()
    {
        if ($this->getMaxDegree() == 0) {
            echo "Not a valid equation";
            exit();
        }

        echo $this->getReducedForm();
        echo 'Polynomial degree: ' . $this->getMaxDegree() . PHP_EOL;

        if ($this->getMaxDegree() > 2) {
            echo "The polynomial degree is stricly greater than 2, I can't solve." . PHP_EOL;
            exit();
        }
    }

    public function calcPolynome()
    {
        $delta = ($this->numbers[1] * $this->numbers[1]) - (4 * $this->numbers[0] * $this->numbers[2]);

        if ($delta > 0) {
            $x = ($this->numbers[1] * -1 - sqrt($delta)) / (2 * $this->numbers[2]);
            $y = ($this->numbers[1] * -1 + sqrt($delta)) / (2 * $this->numbers[2]);
        } elseif ($delta == 0) {
            $x = (-$this->numbers[1]) / (2 * $this->numbers[2]);
        }
        if ($delta < 0) {
            printf("Discriminant is strickly negativ (%d), no real solution \n", $delta);
        }
        if ($delta > 0) {
            printf("Discriminant is strickly positiv (%d), there are two solutions \n", $delta);
            printf("X1: %s \n", $x);
            printf("X2: %s", $y);
        } else {
            printf("Discriminant is null, there are one solution \n");
            printf("X: %s", $x);
        }
    }

    public function calcEquation()
    {
        $result = $this->numbers[0] / $this->numbers[1];
        printf("Result : %s", $result);
    }

    public function printResult()
    {
        if ($this->getMaxDegree() == 2) {
            $this->calcPolynome();
        }
        if ($this->getMaxDegree() == 1) {
            $this->calcEquation();
        }
    }
}


$computor = new Computor($argv);
$computor->printInfo();
$computor->printResult();
