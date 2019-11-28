<?php
namespace EugenioBonifacio\XmlDSig\XAdES;

use DateTime;
use FR3D\XmlDSig\Adapter\XmlseclibsAdapter;
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;


class XAdES implements SignatureInterface
{
    const XML_C14N = 'http://www.w3.org/2001/10/xml-exc-c14n#';

    protected $adapter;

    /** @var string */
    protected $pbk;

    /** @var string */
    protected $pvk;

    /**
     * Signature constructor.
     * @param string $publicKey
     * @param string $privateKey
     */
    public function __construct($publicKey, $privateKey)
    {
        $this->adapter = new XmlseclibsAdapter();
        $this->pbk = $publicKey;
        $this->pvk = $privateKey;
    }

    /**
     * @param SignatureData $data
     * @return bool
     * @throws SignatureException
     */
    public function verify(SignatureData $data)
    {
        try {
            $doc = new \DOMDocument();
            $doc->loadXML($data->getContent());
            return $this->adapter->verify($doc);
        } catch (\Throwable $e) {
            throw new SignatureException("Signature verification failed", 0, $e);
        }
    }

    /**
     * @param \DOMDocument $xml
     * @param string $p12
     * @param string|null $password
     * @return SignatureData
     * @throws SignatureException
     */
    public function sign(SignatureData $data)
    {
        try {
            $doc = new \DOMDocument();
            $doc->loadXML($data->getContent());

            $objDSig = new XMLSecurityDSig();
            $objDSig->sigNode->setAttribute('Id', 'Signature1');
            $objDSig->appendSignature($doc->documentElement);
            $objDSig->sigNode = $objDSig->locateSignature($doc);

            $objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);

            // documento
            $objDSig->addReference(
                $doc,
                XMLSecurityDSig::SHA256,
                [
                    'http://www.w3.org/2002/06/xmldsig-filter2' => [
                        'query' => '/descendant::ds:Signature',
                        'attributes' => [
                            'Filter' => 'subtract'
                        ]
                    ],
                ],
                [
                    'reference_id' => 'reference-document',
                    'force_uri' => true,
                ]
            );

            $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, ['type'=>'private']);

            /* load private key */
            $objKey->loadKey($this->pvk);
            /* load public key */
            $objDSig->add509Cert($this->pbk, true, false, [
                'key_info_id' => 'KeyInfoId'
            ]);

            $objDSig->addReference(
                $doc,
                XMLSecurityDSig::SHA256,
                [],
                [
                    'reference_id' => 'reference-keyinfo',
                    'force_uri' => '#KeyInfoId',
                ]
            );

            // signedproperties
            $sp = 'http://uri.etsi.org/01903/v1.3.2#';
            $obj1 = $doc->createElementNS($sp, 'xades:QualifyingProperties');
            $obj1->setAttribute('Target', '#Signature1');

            $props = $doc->createElementNS($sp,'xades:SignedProperties');
            $props->setAttribute('Id', 'SignedProperties_1');
            $sigprops = $doc->createElementNS($sp,'xades:SignedSignatureProperties');
            $time = $doc->createElementNS($sp,'xades:SigningTime', (new DateTime())->format(DateTime::RFC3339));

            $sigprops->appendChild($time);
            $props->appendChild($sigprops);
            $obj1->appendChild($props);

            $objDSig->addObject($obj1);

            $objDSig->addReference(
                $doc,
                XMLSecurityDSig::SHA256,
                [],
                [
                    'reference_id' => 'reference-signedpropeties',
                    'force_uri' => '#SignedProperties_1',
                    'attributes' => [
                        'Type' => 'http://uri.etsi.org/01903#SignedProperties'
                    ]
                ]
            );

            $objDSig->sign($objKey);

            $signedData = new SignatureData();
            $signedData->setName($data->getName());
            $signedData->setContent($doc->saveXML());

            return $signedData;
        } catch (\Exception $e) {
            throw new SignatureException("Signature failed", 0, $e);
        }
    }
}