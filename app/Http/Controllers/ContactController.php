<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Jobber\Services\JobberApi;
use Zoho\Services\ZohoApi;

class ContactController extends Controller
{
    public function CreateContact(Request $request)
    {

        $output = $request->all();

        $zohoId = strval($output['id']);

        $variables = $this->getVariables($output);

        $jobber = JobberApi::getInstance();
        $zoho = ZohoApi::getInstance();
        $response = $jobber->createClient($variables);

        $clientID = $response['data']['clientCreate']['client']['id'];
        $decodedClientId = base64_decode($clientID);

        $numericClientId = preg_replace('/\D/', '', $decodedClientId);

        $updateData = [
            'Jobber_ID' =>strval($numericClientId),
            'Jobber_Link' => 'https://secure.getjobber.com/clients/' . $numericClientId
        ];



        $rr = $zoho->updateRecordById('Contacts',$zohoId,$updateData);

    }
    private function getVariables($output)
    {
        $firstName = $output['First_Name']??'';
        $lastName = $output['Last_Name']??'';
        $email = $output['Email']??'';
        $mailingStreet = $output['Mailing_Street']??'';
        $mailingStreet2 = $output['Mailing_Street2']??'';
        $city = $output['Mailing_City']??'';
        $mailingState = $output['Mailing_State']??'';
        $mailingCountry = $output['Mailing_Country']??'';
        $mailingZip = $output['Mailing_Zip']??'';
        $phone = $output['Phone']??'';

        if($phone != null || $phone != '')
        {
            $phone = substr($phone, 0, 3) . '-' . substr($phone, 3, 3) . '-' . substr($phone, 6);
        }
        $variables = [
            "firstName" => $firstName,
            "lastName" => $lastName,
            "emailDescription" => "MAIN",
            "emailPrimary" => true,
            "emailAddress" => $email,
            "billingCity" => $city,
            "billingCountry" => strval($mailingCountry),
            "billingPostalCode" =>$mailingZip,
            "billingProvince" =>  $mailingState,
            "billingStreet1" => $mailingStreet,
            "billingStreet2" => $mailingStreet2,
            "phoneDescription" => "MOBILE",
            "phoneNumber" =>  $phone,
            "phoneSMSAllowed" => true,
            "phonePrimary" => true,
        ];

        return $variables;
    }
}
