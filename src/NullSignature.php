<?php
namespace EugenioBonifacio\XmlDSig\XAdES;


class NullSignature implements SignatureInterface
{

    /**
     * @param SignatureData $data
     * @return boolean
     *
     * @throws SignatureException
     */
    public function verify(SignatureData $data)
    {
        return true;
    }

    /**
     * @param SignatureData $data
     * @return SignatureData
     *
     * @throws SignatureException
     */
    public function sign(SignatureData $data)
    {
        return $data;
    }
}