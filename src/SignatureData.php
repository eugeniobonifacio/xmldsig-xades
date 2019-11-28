<?php
namespace EugenioBonifacio\XmlDSig\XAdES;


class SignatureData
{
    /**
     * File name
     * @var string
     */
    protected $name;

    /**
     * Content
     *
     * @var string|resource
     */
    protected $content;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return SignatureData
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|resource
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string|resource $content
     * @return SignatureData
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
}