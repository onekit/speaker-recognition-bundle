Getting Started With OnekitSpeakerRecognitionBundle
==================================

## Installation

Hope you already have account on Microsoft Azure and active subscription of Speaker Recognition API.  
If you didn't, follow this link: https://portal.azure.com/#create/Microsoft.CognitiveServices  
It's free for trial.

1. Download OnekitSpeakerRecognitionBundle using composer
2. Enable the bundle
3. Configure bundle
4. Call Microsoft Speaker Recognition API endpoints from your own Controller

### Step 1: Download OnekitSpeakerRecognitionBundle using composer
Type in directory with your project:

``` bash
$ php composer.phar require onekit/speaker-recognition-bundle
```

Composer will install the bundle to your project's 'vendor/onekit' directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Onekit\SpeakerRecognitionBundle\OnekitSpeakerRecognitionBundle()
    );
}
```

### Step 3: Configure the bundle in your config file

Add the following in your parameters.yml.dist file

``` yaml
# app/config/parameters.yml.dist
parameters:
    recognition_api_endpoint: https://api.projectoxford.ai/spid/v1.0
    ocp_apim_subscription_key_1: 00000000000000000000000000000000
```

![Image of Yaktocat](https://octodex.github.com/images/yaktocat.png)
![Copy OCP APIM SUBSCRIPTION KEY 1 from your Azure Portal account](https://raw.githubusercontent.com/onekit/speaker-recognition-bundle/master/Resources/public/img/key.png)



Then type:  
``` bash
php composer.phar install
```  
to copy parameters from parameters.yml.dist to parameters.yml and enter correct Subscription Key.

### Step 4: Call Microsoft Speaker Recognition API endpoints from your own Controller

To show your breadcrumbs on page simply add next in the template of you page:

``` php
/**
 * @var SpeakerRecognitionManager
 */
$speakerRecognitionManager = $this->get('manager.speaker_recognition');
$response = $speakerRecognitionManager->getAllProfiles();
```

**Speaker Recognition API Reference:** 
https://dev.projectoxford.ai/docs/services/563309b6778daf02acc0a508/operations/5645c3271984551c84ec6797

###Trademarks notice
*Microsoft Azure, Microsoft Cognitive Services are either registered trademarks or trademarks of Microsoft Corporation in the United States and other countries.*