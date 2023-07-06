<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Jobber\Services\JobberApi;
use Zoho\Services\ZohoApi;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

       /* $j = JobberApi::getInstance();

        $accessToken = $j->getAccessToken();

        $z = new ZohoApi();
        $resp = $z->getContact();
        $output = json_decode($resp['details']['output'], true);
        Log::info($output);
        dd($output);
        $firstName = $output['First_Name']??'';
        $lastName = $output['Last_Name']??'';
        $email = $output['Email']??'';
        $mailingStreet = $output['Mailing_Street']??'';
        $mailingStreet2 = $output['Mailing_Street2']??'';
        $city = $output['Mailing_City']??'';
        $mailingState = $output['Mailing_State']??'';
        $mailingCounty = $output['Mailing_Country']??'';
        $mailingZip = $output['Mailing_Zip']??'';
        $phone = $output['Phone']??'';

        $variables = [
            "firstName" => "kurwa",
            "lastName" => "bober",
            "emailDescription" => "MAIN",
            "emailPrimary" => true,
            "emailAddress" => "yake.doe@example.com",
            "billingCity" => "bidlo",
            "billingCountry" => "perviy",
            "billingPostalCode" => "123",
            "billingProvince" => "raz",
            "billingStreet1" => "bachit 1",
            "billingStreet2" => "bobra 2",
            "phoneDescription" => "MOBILE",
            "phoneNumber" => "099795372",
            "phoneSMSAllowed" => true,
            "phonePrimary" => true
        ];


    $resp = $j->createClient($variables);*/


        $responseData = ZohoApi::getInstance();
        $updateData = [

            'Jobber_ID' => '5026476000000446501',
            'Jobber_Link' => 'https://secure.getjobber.com/clients/5026476000000446501'

];


        $rr = $responseData->getRecordById('Contacts','5026476000000446501');
        dd($rr);
    }
}
