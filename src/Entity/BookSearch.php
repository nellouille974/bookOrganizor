<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

class BookSearch {

    /**
     * @var int|null
     * @Assert\Range(min=5, max=1500)
     */
    private $minPage;

    /**
     * @var ArrayCollection
     */
    private $options;

    public function __construct()
    {
        $this->options = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getMinPage(): ?int
    {
        return $this->minPage;
    }

    /**
     * @param int|null $minPage
     * @return BookSearch
     */
    public function setMinPage(int $minPage): BookSearch
    {
        $this->minPage = $minPage;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getOptions(): ArrayCollection
    {
        return $this->options;
    }

    /**
     * @param ArrayCollection $options
     */
    public function setOptions(ArrayCollection $options): void
    {
        $this->options = $options;
    }


}