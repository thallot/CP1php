<?php

class Parser
{

  public $argv;

  public $numbers = [];

  public function __construct(array $argv)
  {
    $this->argv = $argv;
  }

  public function checkError($valid, $message = 'Wrong equation')
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

  public function getPower($value)
  {
    $this->checkError( $value[0] == 'X' || $value[0] == 'x', 'Numbers must have X variable');
    $this->checkError( $value[1] == '^', 'Numbers must have X^');
    $power = $this->getValue(substr($value, 2));
    $this->checkError( $this->isNumber($power), 'Power must be a number');
    return $power;
  }


  public function getNumbers($equation, $reverseSign = false)
  {
    $sign = 1;
    $expectedNumbers = preg_split( "/(\+|\-)/", $equation, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

    foreach ($expectedNumbers as $idx => $value) {
      if ($value == '+' || $value == '-') {
        $sign = $value == '+' ? 1 : -1;
      }
      else {
        $sign = $reverseSign ? $sign * -1 : $sign;
        $part = explode('*', $value);
        $number = $this->getValue( trim($part[0]) );
        $this->checkError( $this->isNumber($number), 'Wrong nmber');
        $power = $this->getPower(trim($part[1]));
        $this->numbers[$power] += $number * $sign;
      }
    }
  }

  public function parse()
  {
    $this->checkError(count($this->argv) == 2, 'Please enter one argument');

    $equations = explode('=', $this->argv[1]);
    $this->getNumbers($equations[0]);
    $this->getNumbers($equations[1], true);
  }
}
