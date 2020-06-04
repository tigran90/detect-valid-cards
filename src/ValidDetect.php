<?php


namespace CardValidDetect;
use \Exception;
/**
 * Class ValidDetect
 * @package CardValidDetect
 */
class ValidDetect
{
    /**
     * @var array
     */
    private $card_types = [
        'MIR' => '/^2$|^2[0-9][0-9]{14}$/i',
        'DINERS_CLUB' => '/^3$|^3[068][0-9]{12}$/i',
        'JCB' => '/^(?:2131|1800|35[0-9]{3})[0-9]{3,}$/i',
        'VISA' => '/^4[0-9]{0,15}$/i',
        'MAESTRO' => '/^5[0|6-9][0-9]{14}$/i',
        'MASTERCARD' => '/^5[1-5][0-9]{5,}|222[1-9][0-9]{3,}|22[3-9][0-9]{4,}|2[3-6][0-9]{5,}|27[01][0-9]{4,}|2720[0-9]{3,}$/i',
        'AMEX' => '/^3$|^3[47][0-9]{0,13}$/i',
        'DISCOVER' => '/^6$|^6[05]$|^601[1]?$|^65[0-9][0-9]?$|^6(?:011|5[0-9]{2})[0-9]{0,12}$/i',
        'CHINA_UNION_PAY' => '/^6$|^6[2][0-9]{14}$/i',
        'UNKNOWN'=>'isUnknown',
    ];


    /**
     * @param string $card
     * @return bool
     */
    public function isUnknown(string $card): bool
    {
        return true;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    function __call($name, $arguments)
    {
        $callable_name = null;
        if( method_exists($this, $this->card_types[$name])  ){
            return call_user_func_array([$this, $this->card_types[$name]], $arguments);
        }
        return preg_match($this->card_types[$name], $arguments[0]);
    }

    /**
     * @param string $card_number
     * @return bool
     */
    private function valid(string $card_number) : bool
    {
        $card_number=preg_replace('/\D/', '', $card_number);
        $number_length=strlen($card_number);
        $parity=$number_length % 2;
        $total=0;
        for ($i=0; $i<$number_length; $i++) {
            $digit=$card_number[$i];
            if ($i % 2 == $parity) {
                $digit*=2;
                if ($digit > 9) {
                    $digit-=9;
                }
            }
            $total+=$digit;
        }
        return ($total % 10 == 0) ? TRUE : FALSE;
    }

    /**
     * @param string $card_number
     * @return string
     * @throws Exception
     */
    public function detect(string $card_number) : string
    {
        if(!$this->valid($card_number)){
            throw new Exception('Card number is invalid');
        }
        foreach ($this->card_types as $type=>$pattern) {
            if ($this->$type($card_number)) {
                return $type;
            }
        }
    }
}