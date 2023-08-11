<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use function ApiPlatform\JsonSchema\Tests\Fixtures\Enum\getId;

#[ORM\Entity(repositoryClass: CityRepository::class)]
#[ApiResource(normalizationContext: ['groups' => ['city:read']],denormalizationContext: ['groups' => ['city:write']])]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['weather:read', 'city:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['weather:read', 'city:read'])]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'city', targetEntity: Weather::class)]
    #[Groups(['city:read'])]
    private Collection $weather;

    #[ORM\Column(nullable: true)]
    #[Groups(['city:read'])]
    private ?float $lat = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['city:read'])]
    private ?float $lon = null;

    public function __construct()
    {
        $this->weather = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Weather>
     */
    public function getWeather(): Collection
    {
        return $this->weather;
    }

    public function addWeather(Weather $weather): self
    {
        if (!$this->weather->contains($weather)) {
            $this->weather->add($weather);
            $weather->setCity($this);
        }

        return $this;
    }

    public function removeWeather(Weather $weather): self
    {
        if ($this->weather->removeElement($weather)) {
            // set the owning side to null (unless already changed)
            if ($weather->getCity() === $this) {
                $weather->setCity(null);
            }
        }

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(?float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLon(): ?float
    {
        return $this->lon;
    }

    public function setLon(?float $lon): self
    {
        $this->lon = $lon;

        return $this;
    }
}
