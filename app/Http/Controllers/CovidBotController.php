<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use GuzzleHttp;

class CovidBotController extends Controller
{

    /**
     * Get Covid cases by country
     * @param Request $request
     * @return JsonResponse
     * @throws GuzzleHttp\Exception\GuzzleException
     * @throws \Twilio\Exceptions\ConfigurationException
     * @throws \Twilio\Exceptions\TwilioException
     */
    public function countryCasesSummary(Request $request)
    {
        $from = $request->get('From');
        $body = $request->get('Body');


        $httpClient = new GuzzleHttp\Client();
        $response = $httpClient->request("GET", "https://covid19.mathdro.id/api/countries/$body");

        if ($response->getStatusCode() === 200) {
            $trackerResult = json_decode($response->getBody()->getContents());

            $confirmed = $trackerResult->confirmed->value;
            $recovered = $trackerResult->recovered->value;
            $deaths = $trackerResult->deaths->value;
            $lastUpdate = Carbon::parse($trackerResult->lastUpdate)->diffForHumans();

            $message = "Here is the summary of the COVID-19 cases in " . '*' . $body . '*' . " as at " . $lastUpdate . "\n\n";
            $message .= "*Confirmed Cases:* $confirmed \n";
            $message .= "*Recovered Cases:* $recovered \n";
            $message .= "*Deaths Recorded:* $deaths \n";
            $message .= "*Last Update:* $lastUpdate \n";

            $this->postMessageToWhatsApp($message, $from);
            return new JsonResponse([
                'success' => true,
            ]);
        } else {
            $this->postMessageToWhatsApp("Country *$body* not found or doesn't have any cases", $from);
            return new JsonResponse([
                'success' => false,
            ]);
        }
    }


    /**
     * Response message to Twilio
     *
     * @param string $message
     * @param string $recipient
     * @return \Twilio\Rest\Api\V2010\Account\MessageInstance
     * @throws \Twilio\Exceptions\ConfigurationException
     * @throws \Twilio\Exceptions\TwilioException
     */
    public function postMessageToWhatsApp(string $message, string $recipient)
    {
        $twilio_whatsapp_number = env('TWILIO_WHATSAPP_NUMBER');
        $account_sid = env("TWILIO_ACCOUNT_SID");
        $auth_token = env("TWILIO_AUTH_TOKEN");

        $client = new Client($account_sid, $auth_token);
        return $client->messages->create($recipient, array('from' => "whatsapp:$twilio_whatsapp_number", 'body' => $message));
    }

    /**
     * @param string $country
     * @return JsonResponse
     * @throws GuzzleHttp\Exception\GuzzleException
     */
    public function getByCountry(string $country)
    {
        $httpClient = new GuzzleHttp\Client();

        try {
            $response = $httpClient->request("GET", "https://covid19.mathdro.id/api/countries/$country");

            return new JsonResponse([
                json_decode($response->getBody()->getContents())
            ]);
        } catch (\Exception $exception) {
            return $this->response('Country not found', 404);
        }
    }
}
