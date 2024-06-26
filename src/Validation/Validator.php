<?php

namespace App\Validation;

use App\Validation\Rules\ValidationRuleInterface;
use ReflectionAttribute;
use ReflectionClass;

class Validator
{
    private array $errors = [];

    public function validate(object $object): void
    {
        // Instantiate a $reflector using new ReflectionClass($object);
        $reflector = new ReflectionClass($object);

        // Loop over the reflector properties
        foreach ($reflector->getProperties() as $property) {

            // Get the Attributes using $property->getAttributes(); (only ValidationRuleInterface)
            $attributes = $property->getAttributes(
                ValidationRuleInterface::class, 
                ReflectionAttribute::IS_INSTANCEOF
            );

            
            // Loop over the Attributes
            foreach ($attributes as $attribute) {

                // Get the validator using $attribute->getValidator();
                $validator = $attribute->newInstance()->getValidator();

                // Ask IF the property does not validate
                if (!$validator->validate($property->getValue($object))) {

                    $this->errors[$property->getName()][] = sprintf(
                        "Ivalid value for'%s' using '%s' validation.",
                        $property->getName(),
                        $attribute->getName()
                    );
                }
            }
        dd($this->errors);

        }
    }
}
