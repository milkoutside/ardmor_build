<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Jobber\Services\JobberApi;
use Zoho\Services\ZohoApi;

class JobberController extends Controller
{
    public function CreateRecord(Request $request)
    {
        $req = $request->all();
        $encodedItemId = $req['data']['webHookEvent']['itemId'];
        $jobberApi = JobberApi::getInstance();

        $responseArray = $jobberApi->getRecordById($encodedItemId);
        $firstName = $responseArray['data']['query']['firstName']??'';
        $lastName = $responseArray['data']['query']['lastName']??'';
        $emails = $responseArray['data']['query']['emails']??'';
        $billingAddress = $responseArray['data']['query']['billingAddress']??'';
        $properties = $responseArray['data']['query']['properties']??'';
        $phones = $responseArray['data']['query']['phones']??'';

// Извлечение данных из вложенных массивов

        $emailAddress = $emails[0]['address']??'';

        $billingAddressCity = $billingAddress['city']??'';
        $billingAddressCountry = $billingAddress['country']??'';
        $billingAddressPostalCode = $billingAddress['postalCode']??'';
        $billingAddressProvince = $billingAddress['province']??'';
        $billingAddressStreet1 = $billingAddress['street1']??'';
        $billingAddressStreet2 = $billingAddress['street2']??'';

        $propertiesAddressCity = $properties[0]['address']['city']??'';
        $propertiesAddressCountry = $properties[0]['address']['country']??'';
        $propertiesAddressPostalCode = $properties[0]['address']['postalCode']??'';
        $propertiesAddressProvince = $properties[0]['address']['province']??'';
        $propertiesAddressStreet1 = $properties[0]['address']['street1']??'';
        $propertiesAddressStreet2 = $properties[0]['address']['street2']??'';


        $phoneNumber = $phones[0]['number']??'';
        $data = [
            'data' => [
                'Full_Name' => $firstName . ' ' . $lastName,
                'Email' => $emailAddress,
                'Property_Details' => [
                    "address_line_1" => $propertiesAddressStreet1,
                    "address_line_2" => $propertiesAddressStreet2,
                    "district_city" => $propertiesAddressCity,
                    "state_province" => $propertiesAddressProvince,
                    "postal_Code" => $propertiesAddressPostalCode,
                    "country" => $propertiesAddressCountry
                ],
                'Billing_Address' => [
                    "address_line_1" => $billingAddressStreet1,
                    "address_line_2" => $billingAddressStreet2,
                    "district_city" => $billingAddressCity,
                    "state_province" => $billingAddressProvince,
                    "postal_Code" => $billingAddressPostalCode,
                    "country" => $billingAddressCountry
                ],
                'Contact_No' => $phoneNumber
            ]
        ];
        $zohoApi = ZohoApi::getInstance();
        $r = $zohoApi->createZohoCreatorRecord($data);
        Log::info($r);

    }
}
