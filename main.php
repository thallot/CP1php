<?php

include 'parsing.php';

class Calc
{

  public $maxDegree;

  public $number;

  public function __construct($parser)
  {
    $this->numbers = $parser->numbers;
  }

  public function getReducedForm()
  {
    $reduced = 'Reduced form:';
    foreach ($this->numbers as $key => $value) {
      if ($value != 0)
        $reduced .= sprintf(' %s * X^%s', $value, $key);
    }

    return $reduced . PHP_EOL;
  }

  public function getMaxDegree()
  {
    if (!isset($this->maxDegree))
      $this->maxDegree = count($this->numbers) - 1;
    return $this->maxDegree;
  }

  public function printInfo()
  {
    echo $this->getReducedForm();
    echo 'Polynomial degree: ' . $this->getMaxDegree() . PHP_EOL;

    if ($this->getMaxDegree() > 2) {
      echo "The polynomial degree is stricly greater than 2, I can't solve." . PHP_EOL;
      exit();
    }
  }

  public function calcDelta()
  {
    return ($this->numbers[1] * $this->numbers[1]) - (4 * $this->numbers[0] * $this->numbers[2]);
  }

}

$parser = new Parser($argv);
$parser->parse();
$calc = new Calc($parser);
$calc->printInfo();
var_dump($calc->calcDelta());
