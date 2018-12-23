<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Booking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ad", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="Attention, la date doit être au bon format")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="Attention, la date doit être au bon format")
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * Callback appelé à chaque reservation
     *
     * @ORM\PrePersist
     * @return void
     */
    public function prePresist(){
        if(empty($this->createdAt)){
            $this->createdAt = new \DateTime();
        }
        if (empty($this->amount)) {
            //Prix de l'annonce * nb de jour
            $this->amount = $this->ad->getPrice() * $this->getDuration();
        }
    }

    public function isBookableDates(){
        // Il faut connaitre les dates qui sont impossibles pour lannonce
        $notAvailableDays = $this->ad->getNotAvailableDays();
        // Récupérer les dates saisie pour la réservation
        $bookingDays = $this->getDays();

        //Transformation des deux tb datetime en string pour faciliter la comparaison
         $days = array_map(function($day){
             return $day->format('Y-m-d');
         }, $bookingDays);

         $notAvailable = array_map(function($day){
            return $day->format('Y-m-d');
        }, $notAvailableDays);

        // Comparer les deux tb: si la meme journée s'y trouve: c'est un pb
        foreach ($days as $day) {
            //Chercher au sein d'un tb une information : premier paramètre, l'information . 2e : le tb de recherche
            if ( array_search($day, $notAvailable) !== false) {
                return false;
            }
            return true;
        }
    }

    /**
     * Récupère les journées correspondant à la reservation
     *
     * @return array tb d'objet dateTime
     */
    public function getDays(){

       $resultat = range(
           $this->getStartDate()->getTimestamp(), 
           $this->getEndDate()->getTimestamp(),
           24 * 60 * 60);
        
           // Transformer le tb $resultat de timestamp en tb de datetime object
           $days = array_map(function($dayTimestamp){
               return new \DateTime(date('Y-m-d', $dayTimestamp)); 
            }, $resultat);

           return $days;
    }

    public function getDuration(){
        $diff = $this->endDate->diff($this->startDate);
        return $diff->days;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
