<?php

namespace Jobber\Services;

use Illuminate\Support\Facades\Http;

class JobberApi
{
    private static $instance = null;
    private $accessToken;

    private function __construct()
    {
        $this->accessToken = $this->fetchAccessToken();
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function fetchAccessToken()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded'
        ])->asForm()->post('https://api.getjobber.com/api/oauth/token', [
            'client_id' => '59395785-ace7-4539-ae5f-f26250d5b735',
            'client_secret' => '2c49d7b4c0e9226322c4bbdde99b890f259a5141e0ac016c2c7c6d1d17bd62b3',
            'grant_type' => 'refresh_token',
            'refresh_token' => 'b6c722c78335e7591edde4aebbbbb2ff'
        ]);

        $responseData = $response->json();
        $accessToken = $responseData['access_token'];

        return $accessToken;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }


    public function createClient($params)
    {
        $query = 'mutation CreateClient(
          $firstName: String!
          $lastName: String!
          $emailDescription: EmailDescription!
          $emailPrimary: Boolean!
          $emailAddress: String!
          $billingCity: String!
          $billingCountry: String!
          $billingPostalCode: String!
          $billingProvince: String!
          $billingStreet1: String!
          $billingStreet2: String!
          $phoneDescription: PhoneNumberDescription!
          $phoneNumber: String!
          $phoneSMSAllowed: Boolean!
          $phonePrimary: Boolean!
        ) {
          clientCreate(
            input: {
              firstName: $firstName
              lastName: $lastName
              emails: [
                {
                  description: $emailDescription
                  primary: $emailPrimary
                  address: $emailAddress
                }
              ]

              properties: [{
                address:{
                city: $billingCity
                country: $billingCountry
                postalCode: $billingPostalCode
                province: $billingProvince
                street1: $billingStreet1
                street2: $billingStreet2
                }
              }]
              phones: [
                {
                  description: $phoneDescription
                  number: $phoneNumber
                  smsAllowed: $phoneSMSAllowed
                  primary: $phonePrimary
                }
              ]
            }
          ) {
            client {
              id
              firstName
              lastName
            }
            userErrors {
              message
              path
            }
          }
        }

        ';


        $data = [
            'query' => $query,
            'variables' => $params
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
            'X-JOBBER-GRAPHQL-VERSION' => '2023-05-05'
        ])->post('https://api.getjobber.com/api/graphql', $data);

        return $response->json();
    }

    public function getRecordById($id)
    {
        $query = '{
        query:
            client(id:"' . $id . '") {
                firstName
                lastName
                emails {
                    description
                    primary
                    address
                }
                billingAddress {
                    city
                    country
                    postalCode
                    province
                    street1
                    street2
                }
                properties {
                    address {
                        city
                        country
                        postalCode
                        province
                        street1
                        street2
                    }
                }
                phones {
                    description
                    number
                    smsAllowed
                    primary
                }
            }
    }';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->accessToken,
            'X-JOBBER-GRAPHQL-VERSION' => '2023-05-05'
        ])->post('https://api.getjobber.com/api/graphql', ['query' => $query]);

        return $response->json();
    }
}
