<?php namespace Moon\Properties;

class Property
{
    public $id;
    public $index_id;
    public $key;
    public $value;
    public $type;

    public function __construct($values)
    {
        $this->id       = $values->id;
        $this->index_id = $values->index_id;
        $this->key      = $values->key;
        $this->value    = $values->value;

        if (isset($values->type)) {
            $this->type = $values->type;
            switch ($values->type) {
                case 'Integer':
                    $this->value = intval($values->value);
                    break;

                case 'Decimal':
                    /**
                     * Note that using floatval() drops the precisions if you have .00
                     */
                    $this->value = floatval($values->value);
                    break;
            }
        }

        if ($this->type === 'Php' && $this->id && is_string($values->value)) {
            $this->value = unserialize($values->value);
        }

    }
}
