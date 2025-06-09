<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require './vendor/autoload.php';

use AmoCRM\Models\LeadModel;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Models\ContactModel;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Client\LongLivedAccessToken;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Models\CustomFieldsValues\TextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\TextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\TextCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\NumericCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\NumericCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\NumericCustomFieldValueModel;

require 'config.php';

$apiClient = new AmoCRMApiClient();

$longLivedAccessToken = new LongLivedAccessToken($accessToken);

$apiClient->setAccessToken($longLivedAccessToken)
    ->setAccountBaseDomain($subdomain);

$leadsService = $apiClient->leads();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $phone = htmlspecialchars($_POST['phone'] ?? '');
    $price = intval($_POST['price'] ?? 0);
    $isLongVisit = intval($_POST['is_long_visit'] ?? 0);

    $contact = new ContactModel();
    $contact->setName($name);

    $contactCustomFieldsValues = new CustomFieldsValuesCollection();

    /* Номер телефона - Кастомное поле */

    $phoneCustomFieldValuesModel = new TextCustomFieldValuesModel();
    $phoneCustomFieldValuesModel->setFieldId(2223029);
    $phoneCustomFieldValuesModel->setValues(
        (new TextCustomFieldValueCollection())
            ->add((new TextCustomFieldValueModel())->setValue($phone))
    );
    $contactCustomFieldsValues->add($phoneCustomFieldValuesModel);

    /* Электронная почта - Кастомное поле */

    $emailCustomFieldValuesModel = new TextCustomFieldValuesModel();
    $emailCustomFieldValuesModel->setFieldId(2223031);
    $emailCustomFieldValuesModel->setValues(
        (new TextCustomFieldValueCollection())
            ->add((new TextCustomFieldValueModel())->setValue($email))
    );
    $contactCustomFieldsValues->add($emailCustomFieldValuesModel);

    $contact->setCustomFieldsValues($contactCustomFieldsValues);

    try {
        $contactModel = $apiClient->contacts()->addOne($contact);
    } catch (AmoCRMApiException $e) {
        print_r($e);
        die;
    }

    $lead = new LeadModel();
    $lead
        ->setPrice($price);

    $leadCustomFieldsValues = new CustomFieldsValuesCollection();

    /* Время пребывания на сайте - Кастомное поле */

    $isLongCustomFieldValuesModel = new NumericCustomFieldValuesModel();
    $isLongCustomFieldValuesModel->setFieldId(2224001);
    $isLongCustomFieldValuesModel->setValues(
        (new NumericCustomFieldValueCollection())
            ->add((new NumericCustomFieldValueModel())->setValue($isLongVisit))
    );
    $leadCustomFieldsValues->add($isLongCustomFieldValuesModel);
    
    $lead->setCustomFieldsValues($leadCustomFieldsValues);
    $lead = $leadsService->addOne($lead);

    try {
        $contact_received = $apiClient->contacts()->getOne($contactModel->getId());
    } catch (AmoCRMApiException $e) {
        print_r($e);
        die;
    }

    $customFields = $contact_received->getCustomFieldsValues();

    $links = new LinksCollection();
    $links->add($contact_received);
    try {
        $apiClient->leads()->link($lead, $links);
    } catch (AmoCRMApiException $e) {
        print_r($e);
        die;
    }


    

}

?>