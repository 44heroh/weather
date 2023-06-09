<?php


namespace App\Validator;


use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class WeatherValidator
{
    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;
    /**
     * @var Assert\Collection
     */
    private Assert\Collection $constraints;

    /**
     * WeatherValidator constructor.
     * @param $validator
     * @param $constraints
     */
    public function __construct()
    {
        $this->validator = Validation::createValidator();;
        $this->constraints = new Assert\Collection([
            'fields' => [
                'cod' => new Assert\EqualTo('200'),
                'list' => new Assert\NotNull(),
            ],
            'allowExtraFields' => true,
            'allowMissingFields' => false
        ]);
    }

    /**
     * @return \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    public function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }

    /**
     * @return Assert\Collection
     */
    public function getConstraints(): Assert\Collection
    {
        return $this->constraints;
    }

    /**
     * @param array $content
     * @return ConstraintViolationListInterface
     */
    public function validate(array $content): ConstraintViolationListInterface
    {
        return $this->validator->validate($content, $this->getConstraints());
    }
}