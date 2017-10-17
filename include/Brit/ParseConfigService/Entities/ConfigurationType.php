<?php
/**
 * Created by PhpStorm.
 * User: brittanyreves
 * Date: 9/18/16
 * Time: 7:29 PM
 */

namespace Brit\ParseConfigService\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ConfigurationType
 * @package Brit\ParseConfigService\Entities
 *
 * @ORM\Table(name="config_type")
 * @ORM\Entity(repositoryClass="Brit\ParseConfigService\Repositories\ConfigurationTypeRepository")
 */
class ConfigurationType implements \JsonSerializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="config_type_id", type="integer", nullable=false, unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="config_type_name", type="string", length=45, nullable=false)
     */
    private $type;

    /**
     * @var Configuration[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Brit\ParseConfigService\Entities\Configuration", mappedBy="type")
     */
    private $configurations;

    public function __construct()
    {
        $this->configurations = new ArrayCollection();
    }

    /**
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType(string $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Configuration[]|Collection
     */
    public function getConfigurations(): Collection
    {
        return $this->configurations;
    }

    /**
     * @param Configuration[] $configurations
     * @return $this
     */
    public function setConfigurations(array $configurations)
    {
        $this->configurations->clear();
        $this->configurations = $configurations;

        return $this;
    }

    /**
     * @param Configuration $configuration
     */
    public function addConfiguration(Configuration $configuration)
    {
        if(!$this->configurations->contains($configuration)) {
            $this->configurations->add($configuration);
        }
    }

    /**
     * @return \stdClass
     */
    function jsonSerialize()
    {
        $type = new \stdClass();
        $type->id = $this->id;
        $type->type = $this->type;

        foreach($this->configurations as $configuration) {
            $type->configurations[] = $configuration->getId();
        }

        return $type;
    }
}