<?php
/**
 * Created by PhpStorm.
 * User: Eugenio Bonifacio
 * Date: 28/11/19
 * Time: 10:32
 */

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