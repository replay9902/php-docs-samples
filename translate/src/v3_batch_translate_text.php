<?php
/*
 * Copyright 2019 Google LLC
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/*
 * DO NOT EDIT! This is a generated sample ("LongRunningRequest",  "translate_v3_batch_translate_text")
 */

// sample-metadata
//   title: Batch translate text
//   description: Batch translate text
//   usage: php v3_batch_translate_text.php [--input_uri "gs://cloud-samples-data/text.txt"] [--output_uri "gs://YOUR_BUCKET_ID/path_to_store_results/"] [--project_id "[Google Cloud Project ID]"] [--location "us-central1"] [--source_lang en] [--target_lang ja]
require_once __DIR__ . '/../vendor/autoload.php';

if (count($argv) < 7 || count($argv) > 7) {
    return printf("Usage: php %s INPUT_URI OUTPUT_URI PROJECT_ID LOCATION SOURCE_LANGUAGE TARGET_LANGUAGE\n", __FILE__);
}
list($_, $inputUri, $outputUri, $projectId, $location, $sourceLang, $targetLang) = $argv;

// [START translate_v3_batch_translate_text]
use Google\Cloud\Translate\V3\TranslationServiceClient;
use Google\Cloud\Translate\V3\GcsDestination;
use Google\Cloud\Translate\V3\GcsSource;
use Google\Cloud\Translate\V3\InputConfig;
use Google\Cloud\Translate\V3\OutputConfig;

/** Batch translate text */
$translationServiceClient = new TranslationServiceClient();

// $inputUri = 'gs://cloud-samples-data/text.txt';
// $outputUri = 'gs://YOUR_BUCKET_ID/path_to_store_results/';
// $projectId = '[Google Cloud Project ID]';
// $location = 'us-central1';
// $sourceLang = 'en';
// $targetLang = 'ja';
$targetLanguageCodes = [$targetLang];
$gcsSource = new GcsSource();
$gcsSource->setInputUri($inputUri);

// Optional. Can be "text/plain" or "text/html".
$mimeType = 'text/plain';
$inputConfigsElement = new InputConfig();
$inputConfigsElement->setGcsSource($gcsSource);
$inputConfigsElement->setMimeType($mimeType);
$inputConfigs = [$inputConfigsElement];
$gcsDestination = new GcsDestination();
$gcsDestination->setOutputUriPrefix($outputUri);
$outputConfig = new OutputConfig();
$outputConfig->setGcsDestination($gcsDestination);
$formattedParent = $translationServiceClient->locationName($projectId, $location);

try {
    $operationResponse = $translationServiceClient->batchTranslateText($formattedParent, $sourceLang, $targetLanguageCodes, $inputConfigs, $outputConfig);
    $operationResponse->pollUntilComplete();
    if ($operationResponse->operationSucceeded()) {
        $response = $operationResponse->getResult();
        printf('Total Characters: %s' . PHP_EOL, $response->getTotalCharacters());
        printf('Translated Characters: %s' . PHP_EOL, $response->getTranslatedCharacters());
    } else {
        $error = $operationResponse->getError();
        print($error);
    }
} finally {
    $translationServiceClient->close();
}
// [END translate_v3_batch_translate_text]
