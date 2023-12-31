<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    public const ETAT_EN_COURS_PREPARATION = 0;
    public const ETAT_EN_COURS_LIVRAISON = 1;
    public const ETAT_LIVREE = 2;
    public const ETAT_ANNULEE = 3;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_commande = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2)]
    private ?string $total = null;

    #[ORM\Column]
    private ?int $etat = null; //0- En cours de preparaton; 1- en cours de livraison; 2- livrée ; 3- annulé

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: Detail::class)]
    private Collection $details;


    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(name: 'utilisateur_id', referencedColumnName: 'id')]
    private ?Utilisateur $utilisateur = null;

    public function __construct()
    {
        $this->details = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }
    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->date_commande;
    }

    public function setDateCommande(\DateTimeInterface $date_commande): static
    {
        $this->date_commande = $date_commande;

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(string $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(int $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return Collection<int, Detail>
     */
    public function getDetails(): Collection
    {
        return $this->details;
    }

    public function addDetail(Detail $detail): static
    {
        if (!$this->details->contains($detail)) {
            $this->details->add($detail);
            $detail->setCommande($this);
        }
    
        return $this;
    }
    
    public function removeDetail(Detail $detail): static
    {
        if ($this->details->removeElement($detail)) {
            // set the owning side to null (unless already changed)
            if ($detail->getCommande() === $this) {
                $detail->setCommande(null);
            }
        }
    
        return $this;
    }

    public function getUtilisateur(): ?utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?utilisateur $Utilisateur): static
    {
        $this->utilisateur = $Utilisateur;

        return $this;
    }
}
