<?php namespace Moon\Properties\Properties;

class PropertyFactory
{

    /**
     * Determines a data type for a given value
     * @param  mixed $value
     * @return Varchar|Text|Php|Decimal
     */
    protected function getDataType($value)
    {
        $type = gettype($value);

        if ($type === 'string') {
            $type = 'varchar';
            if (strlen($value) >= 255) {
                $type = 'text';
            }
        } elseif ($type === 'array' || $type === 'object') {
            $type = 'php';
        } elseif ($type === 'double') {
            /**
             * The table structure only allows two precisions
             */
            $value = (string) $value;
            $explodedValue = explode(".", $value);
            if (count($explodedValue) !== 1 ) {
                if (strlen($explodedValue[1]) > 2) {
                    throw new UnknownValueTypeException("You can only set a double/float with two precisions or less.");
                }
            }
            $type = 'decimal';
        }

        return $type;
    }

	public function createWithValues($values)
	{
        if (isset($values->type)) {
            $type = $values->type;
        } else {
            $type = ucfirst($this->getDataType($values->value));
        }

		$classPath = "Moon\\Properties\\Properties\\" . ucfirst($type) . "Property";
		return new $classPath($values);
	}
}
