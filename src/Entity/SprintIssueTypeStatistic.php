<?php

namespace App\Entity;

use App\Repository\SprintIssueTypeStatisticRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SprintIssueTypeStatisticRepository::class)
 */
class SprintIssueTypeStatistic
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
    private $IssueType;

    /**
     * @ORM\Column(type="integer")
     */
    private $Count;

    /**
     * @ORM\ManyToOne(targetEntity=Sprint::class, inversedBy="sprintIssueTypeStatistics")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Sprint;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $StoryPoints;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIssueType(): ?string
    {
        return $this->IssueType;
    }

    public function setIssueType(string $IssueType): self
    {
        $this->IssueType = $IssueType;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->Count;
    }

    public function setCount(int $Count): self
    {
        $this->Count = $Count;

        return $this;
    }

    public function getSprint(): ?Sprint
    {
        return $this->Sprint;
    }

    public function setSprint(?Sprint $Sprint): self
    {
        $this->Sprint = $Sprint;

        return $this;
    }

    public function getStoryPoints(): ?int
    {
        return $this->StoryPoints;
    }

    public function setStoryPoints(?int $StoryPoints): self
    {
        $this->StoryPoints = $StoryPoints;

        return $this;
    }
}
