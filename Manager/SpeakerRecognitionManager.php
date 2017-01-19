<?php
namespace Onekit\SpeakerRecognitionBundle\Manager;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\File\File;

class SpeakerRecognitionManager
{

    /**
     * @var string
     */
    protected $ocpApimSubscriptionKey1;

    /**
     * @var string
     */
    protected $endpoint;


    public function __construct($endpoint, $ocpApimSubscriptionKey1)
    {
        $this->endpoint = $endpoint;
        $this->ocpApimSubscriptionKey1 = $ocpApimSubscriptionKey1;
    }



    /* Identification Profile */

    public function createEnrollment(File $file, $identificationProfileId, $shortAudio = 'true')
    {
        $response = $this->send('/identificationProfiles/'.$identificationProfileId.'/enroll', 'POST', ['shortAudio' => $shortAudio], $file);
        return $response;
    }

    public function createProfile()
    {
        $content = '{ "locale":"en-us" }';
        $contentType = 'application/json';
        $response = $this->send('/identificationProfiles', 'POST', [], $content, $contentType);
        return $response;
    }

    public function deleteProfile($identificationProfileId)
    {
        $response = $this->send('/identificationProfiles/'.$identificationProfileId, 'DELETE');
        return $response;
    }

    public function getAllProfiles()
    {
        $response = $this->send('/identificationProfiles');
        return $response;
    }

    public function getProfile($identificationProfileId)
    {
        $contentType = 'application/json';
        $response = $this->send('/identificationProfiles/'.$identificationProfileId, 'GET', [], null, $contentType);
        return $response;
    }

    public function resetEnrollments($identificationProfileId)
    {
        $response = $this->send('/identificationProfiles/'.$identificationProfileId.'/reset', 'POST');
        return $response;
    }

    /* Speaker Recognition */

    public function getOperationStatus($operationId)
    {
        $response = $this->send('/operations/'.$operationId);
        return $response;
    }

    public function identification(File $file, $identificationProfileIds = null)
    {
        $parameters = ['shortAudio' => 'true', 'identificationProfileIds' => $identificationProfileIds];
        $response = $this->send('/identify', 'POST', $parameters, $file);

        return $response;
    }

    public function verification($file, $verificationProfileId = null)
    {
        $parameters = ['verificationProfileId' => $verificationProfileId];
        $response = $this->send('/verify', 'POST', $parameters, $file);
        return $response;
    }

    /* Verification Phrase */

    public function listAllSupportedVerificationPhrases()
    {
        $parameters = ['locale' => 'en-us'];
        $response = $this->send('/verificationPhrases', 'GET', $parameters);
        return $response;
    }


    /* Verification Profile */

    public function verificationCreateEnrollment(File $file, $verificationProfileId)
    {
        $response = $this->send('/verificationProfiles/'.$verificationProfileId.'/enroll', 'POST', [], $file);
        return $response;
    }

    public function verificationCreateProfile()
    {
        $content = '{ "locale":"en-us" }';
        $contentType = 'application/json';
        $response = $this->send('/verificationProfiles', 'POST', [], $content, $contentType);
        return $response;
    }

    public function verificationDeleteProfile($verificationProfileId)
    {
        $response = $this->send('/verificationProfiles/'.$verificationProfileId, 'DELETE');
        return $response;
    }

    public function verificationGetAllProfiles()
    {
        $parameters = ['locale'=>'en-us'];
        $response = $this->send('/verificationProfiles','GET', $parameters);
        return $response;
    }

    public function verificationGetProfile($verificationProfileId)
    {
        $contentType = 'application/json';
        $response = $this->send('/verificationProfiles/'.$verificationProfileId, 'GET', [], null, $contentType);
        return $response;
    }

    public function verificationResetEnrollments($identificationProfileId)
    {
        $response = $this->send('/verificationProfiles/'.$identificationProfileId.'/reset', 'POST');
        return $response;
    }

    /* send request to speaker recognition API (Microsoft Cognitive Services) */
    private function send($endpoint, $method = 'GET', $parameters = [], $file = null, $contentType = 'application/octet-stream') {

        $base_uri = $this->endpoint.$endpoint;
        $client = new Client();
        $config['headers'] = [
            'Content-Type' => $contentType,
            'Ocp-Apim-Subscription-Key' => $this->ocpApimSubscriptionKey1
        ];
        $config['query'] = $parameters;
        if (!is_null($file) && $contentType == 'application/octet-stream') {
            $config['body'] = file_get_contents($file->getPathName());
        } else {
            $config['body'] = $file;
        }

        $response = $client->request($method, $base_uri, $config);
        $answer = $response->getBody();

        if (!strlen($answer)) { //HTTP CODE 202
            $headers = $response->getHeaders();
            if (isset($headers['Operation-Location'])) {
                $operationUrl = $response->getHeaders()['Operation-Location'][0];
                if ($operationUrl) {
                    $operationIdArray = explode('/',$operationUrl);
                    $operationId = $operationIdArray[6];
                    $answer = json_encode(['operationId' => $operationId]);
                }
            }
        }

        $json = json_decode($answer, true);

        return $json;
    }

}