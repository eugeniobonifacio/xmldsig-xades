<?php
/**
 * Created by PhpStorm.
 * User: Eugenio Bonifacio
 * Date: 28/11/19
 * Time: 10:33
 */

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