<?php

class Parser
{

  public $argv;

  public $numbers = [];

  public function __construct(array $argv)
  {
    $this->argv = $argv;
  }

  public function checkError($valid, $message = 'Wrong equation', $messageParam = '')
  {
    if ($valid)
      return ;

    echo $message . $messageParam . PHP_EOL;
    echo 'Usage: php main.php ["Equation"]' . PHP_EOL;
    exit;
  }

  public function getValue($val) {
    $val = trim($val);
    if (is_numeric($val)) {
      return $val + 0.0;
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
    $value = trim($value);
    $this->checkError( $value[0] == 'X' || $value[0] == 'x', 'Numbers must have X variable');
    $this->checkError( $value[1] == '^', 'Numbers must have X^');
    $power = $this->getValue(substr($value, 2));
    $this->checkError( $this->isNumber($power), 'Power must be a number');
    return $power;
  }

  public function getNumber($value)
  {
    $value = str_replace(' ', '', $value);
    return $this->getValue($value);

  }

  public function extract($value, $reverse)
  {
    $sign = $reverse ? -1 : 1;
    $value = trim($value);
    if ($value[0] == 'X') {
      $number = 1;
      $power = $this->getPower($value);
    } else {
      $part = explode('*', $value);
      $number = $this->getNumber($part[0]);
      $power = $this->getPower($part[1]);
    }

    $this->numbers[$power] += $number * $sign;
  }

  public function getByRegex($equation, $reverse = false)
  {
    $result = [];
    /* Get All +/- digit X^ digit */
    $regex = "/((( *[+-]? *([0-9]*[.])?[0-9]+)? *(\*) *)?(X\^[0-9]+))/";
    preg_match_all($regex, $equation, $result);
    $result = current($result);
    foreach ($result as $key => $value) {
      $equation = str_replace($value, '', $equation);
      $this->extract($value, $reverse);
    }
    $this->checkError(str_replace(' ', '', $equation) == '', 'Unknown element : ', sprintf('[%s]', trim($equation)));
    var_dump($result);
  }


  public function parse()
  {
    $this->checkError(count($this->argv) == 2, 'Please enter one argument');

    $equations = explode('=', $this->argv[1]);
    $this->getByRegex($equations[0]);
    $this->getByRegex($equations[1], true);
  }
}
