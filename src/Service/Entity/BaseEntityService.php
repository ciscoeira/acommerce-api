<?php
namespace App\Service\Entity;

use App\Service\ValidationService;
use Doctrine\Persistence\ObjectManager;

abstract class BaseEntityService
{
    protected $entityClass;
    protected $om;
    protected $validator;
    protected $errors = [];
    protected $entity;

    public function __construct(ObjectManager $om, ValidationService $validator)
    {
        $this->om = $om;
        $this->validator = $validator;

        if(empty($this->entityClass)) {
            throw new \Exception("Missing entity class.");
        }
    }

    public function create(array $properties = []): self
    {
        $this->setEntity(new $this->entityClass());

        return $this->setProperties($properties);
    }

    public function update($entity, array $properties = []): self
    {
        $this->setEntity($entity);

        return $this->setProperties($properties);
    }

    public function delete($entity): bool
    {
        $this->errors = [];

        try {
            $this->om->remove($entity);
            $this->om->flush();
        } catch (\Exception $ex) {
            $this->errors[] = "Unable to remove item.";

            return false;
        }

        return true;
    }

    public function setProperties(array $properties = []): self
    {
        if ($this->getEntity()) {
            foreach ($properties as $property => $value) {
                if (strtolower($property) === "id") {
                    continue;
                }

                $setterMethod = "set" . $property;

                if (method_exists($this->getEntity(), $setterMethod)) {
                    $this->getEntity()->$setterMethod($value);
                }
            }
        }

        return $this;
    }

    public function save(): bool
    {
        $this->errors = [];

        if (empty($this->entity)) {
            $this->errors[] = "Empty entity.";

            return false;
        }

        $isValid = !$this->errors && $this->validator->validate($this->entity);

        if (!$isValid) {
            $this->errors = array_merge($this->errors, $this->validator->getErrors());

            return false;
        }

        try {
            $this->om->persist($this->entity);
            $this->om->flush();
        } catch (\Exception $ex) {
            throw $ex;
            $this->errors[] = "Unable to save entity.";

            return false;
        }

        return true;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function setEntity($entity)
    {
        if (!is_a($entity, $this->entityClass)) {
            throw new \Exception("Invalid entity. Expected type: " . $this->entityClass);
        }

        $this->entity = $entity;
    }

    public function getEntity()
    {
        return $this->entity;
    }
}