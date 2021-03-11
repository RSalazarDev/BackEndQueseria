<?php

namespace App\Entity;

use App\Repository\ProductoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductoRepository::class)
 */
class Producto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */
    private $precio;

    /**
     * @ORM\OneToMany(targetEntity=LineaPedido::class, mappedBy="producto")
     */
    private $lineaPedidos;

    /**
     * @ORM\ManyToMany(targetEntity=Carrito::class, mappedBy="productos")
     */
    private $carritos;

    public function __construct()
    {
        $this->lineaPedidos = new ArrayCollection();
        $this->carritos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getPrecio(): ?string
    {
        return $this->precio;
    }

    public function setPrecio(string $precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * @return Collection|LineaPedido[]
     */
    public function getLineaPedidos(): Collection
    {
        return $this->lineaPedidos;
    }

    public function addLineaPedido(LineaPedido $lineaPedido): self
    {
        if (!$this->lineaPedidos->contains($lineaPedido)) {
            $this->lineaPedidos[] = $lineaPedido;
            $lineaPedido->setProducto($this);
        }

        return $this;
    }

    public function removeLineaPedido(LineaPedido $lineaPedido): self
    {
        if ($this->lineaPedidos->removeElement($lineaPedido)) {
            // set the owning side to null (unless already changed)
            if ($lineaPedido->getProducto() === $this) {
                $lineaPedido->setProducto(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Carrito[]
     */
    public function getCarritos(): Collection
    {
        return $this->carritos;
    }

    public function addCarrito(Carrito $carrito): self
    {
        if (!$this->carritos->contains($carrito)) {
            $this->carritos[] = $carrito;
            $carrito->addProducto($this);
        }

        return $this;
    }

    public function removeCarrito(Carrito $carrito): self
    {
        if ($this->carritos->removeElement($carrito)) {
            $carrito->removeProducto($this);
        }

        return $this;
    }
}
