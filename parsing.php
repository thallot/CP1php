<?php

class Parser
{

  public $argv;

  public $numbers = [
    ['number' => 0, 'power' => 0],
    ['number' => 0, 'power' => 0],
    ['number' => 0, 'power' => 0]
  ];

  public function __construct(array $argv)
  {
    $this->argv = $argv;
  }

  public function checkError($valid, $message = '')
  {
    if ($valid)
      return ;

    echo $message . PHP_EOL;
    echo 'Usage: php main.php ["Equation"]' . PHP_EOL;
    exit;
  }

  public function getValue($val) {
    if (is_numeric($val)) {
      return $val + 0;
    }
    return null;
  }

  public function isNumber($number)
  {
    if (is_numeric($number) || is_float($number))
      return true;
    return false;
  }

  public function getNumbers($equation)
  {
    $sign = 1;
    $expectedNumbers = preg_split( "/(\+|\-)/", $equation, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

    foreach ($expectedNumbers as $value) {
      if ($value == '+' || $value == '-'){
        $sign = $value == '+' ? 1 : -1;
      } else {
        $part = explode('*', $value);
        $this->checkError( count($part) == 2 );
        $number = $this->getValue( trim($part[0]) );
        $this->checkError( $this->isNumber($number) );

        var_dump($this->isNumber($number), $number);
      }
    }
    var_dump($expectedNumbers);
  }

  public function parse()
  {
    $this->checkError(count($this->argv) == 2);

    $equations = explode('=', $this->argv[1]);
    $this->getNumbers($equations[0]);
  }


}

$parser = new Parser($argv);
$parser->parse();
