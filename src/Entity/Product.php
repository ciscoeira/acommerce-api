<?php

namespace App\Entity;

use App\DBAL\Types\ProductCurrencyType;
use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Assert\Positive(message="Invalid id.")
     *
     * @OA\Property(type="integer", example=1)
     * @Groups({"product_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Name is required.")
     * @Assert\Length(max=255, maxMessage="Name is too long.")
     *
     * @OA\Property(type="string", maxLength=255, example="Foo")
     * @Groups({"product_write", "product_read"})
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     *
     * @OA\Property(ref=@Model(type=Category::class))
     * @Groups({"product_write", "product_read"})
     */
    private $category;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2)
     *
     * @Assert\NotNull(message="Price is required.")
     * @Assert\PositiveOrZero(message="Invalid price.")
     *
     * @OA\Property(type="double", example="105.00")
     * @Groups({"product_write", "product_read"})
     */
    private $price;

    /**
     * @ORM\Column(name="currency", type="ProductCurrencyType", nullable=false)
     * @DoctrineAssert\Enum(entity="App\DBAL\Types\ProductCurrencyType", message="Invalid currency.")
     *
     * @OA\Property(type="string", enum={"USD", "EUR"}, example="USD")
     * @Groups({"product_write", "product_read"})
     */
    protected $currency;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Assert\NotNull
     *
     * @OA\Property(type="boolean")
     * @Groups({"product_write", "product_read"})
     */
    private $featured;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getFeatured(): ?bool
    {
        return $this->featured;
    }

    public function setFeatured(bool $featured): self
    {
        $this->featured = $featured;

        return $this;
    }

    public function convertPrice(string $currency, float $exchangeRate)
    {
        if ($currency != $this->currency) {
            $this->currency = $currency;
            $this->price = $this->price * $exchangeRate;
        }

        return $this;
    }
}
