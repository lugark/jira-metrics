<?php

namespace App\Entity;

use App\Repository\SprintRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SprintRepository::class)
 */
class Sprint
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $title = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $goal = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $startDate = null;

    /**
     * @ORM\OneToMany(targetEntity=SprintIssueTypeStatistic::class, mappedBy="Sprint", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private \Doctrine\Common\Collections\ArrayCollection|array $sprintIssueTypeStatistics;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $IssueCount = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $StoryPoints = null;

    public function __construct()
    {
        $this->sprintIssueTypeStatistics = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getGoal(): ?string
    {
        return $this->goal;
    }

    public function setGoal(string $goal): self
    {
        $this->goal = $goal;

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


    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Collection|SprintIssueTypeStatistic[]
     */
    public function getSprintIssueTypeStatistics(): Collection
    {
        return $this->sprintIssueTypeStatistics;
    }

    public function addSprintIssueTypeStatistic(SprintIssueTypeStatistic $sprintIssueTypeStatistic): self
    {
        if (!$this->sprintIssueTypeStatistics->contains($sprintIssueTypeStatistic)) {
            $this->sprintIssueTypeStatistics[] = $sprintIssueTypeStatistic;
            $sprintIssueTypeStatistic->setSprint($this);
        }

        return $this;
    }

    public function removeSprintIssueTypeStatistic(SprintIssueTypeStatistic $sprintIssueTypeStatistic): self
    {
        if ($this->sprintIssueTypeStatistics->contains($sprintIssueTypeStatistic)) {
            $this->sprintIssueTypeStatistics->removeElement($sprintIssueTypeStatistic);
            // set the owning side to null (unless already changed)
            if ($sprintIssueTypeStatistic->getSprint() === $this) {
                $sprintIssueTypeStatistic->setSprint(null);
            }
        }

        return $this;
    }

    public function getIssueCount(): ?int
    {
        return $this->IssueCount;
    }

    public function setIssueCount(?int $IssueCount): self
    {
        $this->IssueCount = $IssueCount;

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
