<?php


namespace CardValidDetect;
use \Exception;
use phpDocumentor\Reflection\Types\This;

/**
 * Class ValidDetect
 * @package CardValidDetect
 */
class ValidDetect
{
    /**
     * @var bool $is_valid
     */
    private bool $is_valid;

    /**
     * @var string $types
     */
    private string $type;

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
        return $this->is_valid = ($total % 10 == 0) ? TRUE : FALSE;
    }

    public function isValid(): bool
    {
        return $this->is_valid;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
    /**
     * @param string $card_number
     * @return $this|string
     */
    public function detect(string $card_number) : ValidDetect
    {
        if($this->valid($card_number)){
            foreach ($this->card_types as $type=>$pattern) {
                if ($this->$type($card_number)) {
                     $this->type = $type;
                     break;
                }
            }
        }
        return $this;
    }
}