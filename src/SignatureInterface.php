<?php
namespace EugenioBonifacio\XmlDSig\XAdES;


interface SignatureInterface
{
    /**
     * @param SignatureData $data
     * @return boolean
     *
     * @throws SignatureException
     */
    public function verify(SignatureData $data);

    /**
     * @param SignatureData $data
     * @return SignatureData
     *
     * @throws SignatureException
     */
    public function sign(SignatureData $data);
}