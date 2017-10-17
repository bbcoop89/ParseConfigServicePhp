<?php
/**
 * Created by PhpStorm.
 * User: brittanyreves
 * Date: 9/18/16
 * Time: 7:27 PM
 */

namespace Brit\ParseConfigService\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class ConfigurationFile
 * @package Brit\ParseConfigService\Entities
 *
 * @ORM\Table(name="config_file")
 * @ORM\Entity(repositoryClass="Brit\ParseConfigService\Repositories\ConfigurationFileRepository")
 */
class ConfigurationFile implements \JsonSerializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="config_file_id", type="integer", unique=true, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="config_file_path", type="string", length=100, nullable=false)
     */
    private $path;

    /**
     * @var Configuration[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="Brit\ParseConfigService\Entities\Configuration", mappedBy="configurationFiles")
     */
    private $configurations;

    public function __construct()
    {
        $this->configurations = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setPath(string $path)
    {
        $this->path = $path;

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
     * @param Configuration $configuration
     */
    public function removeConfiguration(Configuration $configuration)
    {
        if($this->configurations->contains($configuration)) {
            $this->configurations->removeElement($configuration);
        }
    }

    function jsonSerialize()
    {
        $file = new \stdClass();

        $file->id = $this->id;
        $file->path = $this->path;

        foreach($this->configurations as $configuration) {
            $file->configurations[] = $configuration->getId();
        }

        return $file;
    }


}