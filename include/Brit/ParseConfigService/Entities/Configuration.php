<?php
/**
 * Created by PhpStorm.
 * User: brittanyreves
 * Date: 9/18/16
 * Time: 7:24 PM
 */

namespace Brit\ParseConfigService\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
/**
 * Class Configuration
 * @package Brit\ParseConfigService\Entities
 *
 * @ORM\Table(name="config")
 * @ORM\Entity(repositoryClass="Brit\ParseConfigService\Repositories\ConfigurationRepository")
 */
class Configuration implements \JsonSerializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="config_id", type="integer", nullable=false, unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var ConfigurationType
     *
     * @ORM\ManyToOne(targetEntity="Brit\ParseConfigService\Entities\ConfigurationType", inversedBy="configurations")
     * @ORM\JoinColumn(name="config_type_id", referencedColumnName="config_type_id")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="config_key", type="string", length=45, nullable=false)
     */
    private $key;

    /**
     * @var string
     *
     * @ORM\Column(name="config_value", type="string", length=255, nullable=true, options={"default" : NULL})
     */
    private $value;

    /**
     * @var ConfigurationFile[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="Brit\ParseConfigService\Entities\ConfigurationFile", inversedBy="configurations")
     * @ORM\JoinTable(name="config_to_file",
     *   joinColumns={@ORM\JoinColumn(name="config_id", referencedColumnName="config_id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="config_file_id", referencedColumnName="config_file_id")}
     * )
     */
    private $configurationFiles;

    public function __construct()
    {
        $this->configurationFiles = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return ConfigurationType
     */
    public function getType(): ConfigurationType
    {
        return $this->type;
    }

    /**
     * @param ConfigurationType $type
     * @return $this
     */
    public function setType(ConfigurationType $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function setKey(string $key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue(string $value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return ConfigurationFile[]|Collection
     */
    public function getConfigurationFiles() : Collection
    {
        return $this->configurationFiles;
    }

    /**
     * @param ConfigurationFile[] $configurationFiles
     * @return $this
     */
    public function setConfigurationFiles(array $configurationFiles)
    {
        foreach($configurationFiles as $configurationFile) {
            $configurationFile->addConfiguration($configurationFile);
        }

        $this->configurationFiles = $configurationFiles;

        return $this;
    }

    /**
     * @param ConfigurationFile $configurationFile
     */
    public function addConfigurationFile(ConfigurationFile $configurationFile)
    {
        $configurationFile->addConfiguration($this);

        if(!$this->configurationFiles->contains($configurationFile)) {
            $this->configurationFiles->add($configurationFile);
        }
    }

    /**
     * @param ConfigurationFile $configurationFile
     */
    public function removeConfigurationFile(ConfigurationFile $configurationFile)
    {
        $configurationFile->removeConfiguration($this);

        if($this->configurationFiles->contains($configurationFile)) {
            $this->configurationFiles->removeElement($configurationFile);
        }
    }

    /**
     * @return \stdClass
     */
    function jsonSerialize()
    {
        $config = new \stdClass();

        $config->id = $this->id;
        $config->type = $this->type;
        $config->key = $this->key;
        $config->value = $this->value;

        foreach($this->configurationFiles as $configurationFile) {
            $config->files[] = $configurationFile->getId();
        }


        return $config;
    }


}