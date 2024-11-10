<?php

namespace App\Entity;

use App\Enum\ParkingSpotStatusEnum;
use App\Enum\ParkingSpotTypeEnum;
use App\Repository\ParkingSpotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParkingSpotRepository::class)]
#[ORM\HasLifecycleCallbacks]
class ParkingSpot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $spot_number = null;

    #[ORM\Column(length: 255)]
    private ?string $location = null;

    #[ORM\Column(enumType: ParkingSpotTypeEnum::class)]
    private ?ParkingSpotTypeEnum $type = null;

    #[ORM\Column(enumType: ParkingSpotStatusEnum::class)]
    private ?ParkingSpotStatusEnum $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    /**
     * @var Collection<int, Booking>
     */
    #[ORM\OneToMany(targetEntity: Booking::class, mappedBy: 'spot_id')]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private Collection $bookings;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpotNumber(): ?int
    {
        return $this->spot_number;
    }

    public function setSpotNumber(int $spot_number): static
    {
        $this->spot_number = $spot_number;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getType(): ?ParkingSpotTypeEnum
    {
        return $this->type;
    }

    public function setType(ParkingSpotTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus(): ?ParkingSpotStatusEnum
    {
        return $this->status;
    }

    public function setStatus(ParkingSpotStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): static
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings->add($booking);
            $booking->setSpot($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getSpot() === $this) {
                $booking->setSpot(null);
            }
        }

        return $this;
    }

    #[ORM\PrePersist]
    public function onPrePersist()
    {
        $this->created_at = new \DateTimeImmutable("now");
        $this->updated_at = new \DateTimeImmutable("now");
    }

    #[ORM\PreUpdate]
    public function onPreUpdate()
    {
        $this->updated_at = new \DateTimeImmutable("now");
    }
}
