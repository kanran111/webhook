<?php
defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Api extends REST_Controller
{
    private $errors = [];
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }
        parent::__construct();
    }
    public function test_get()
    {
        $this->response(['error' => 'test api'], 200);
    }
    public function facebook_post()
    {
        $verify_token = '278FeGqbmEelIGX';
        $body = json_decode(file_get_contents('php://input'), true);
        // print_r($body); die;
        if ($body['object'] == 'page') {
            foreach ($body['entry'] as $rowEntry) {
                $message = $rowEntry['messaging'][0];
            }
            $this->response(['mess' => $message], 200);
        } else {
            $this->response([], 404);
        }
    }
    public function facebook_get()
    {
        $verify_token = '278FeGqbmEelIGX';

        $mode = $this->get('hub_mode') ;
        $token = $this->get('hub_verify_token');
        $challenge = $this->get('hub_challenge');
        if( empty($mode) || empty($token) ){
            $this->response('mode hoặc token rỗng',404);
        }
        if ( $mode === 'subscribe' && $token === $verify_token ) {
            die(json_encode($challenge));
            //$this->response($challenge,200);
        }
        // $input = json_decode(file_get_contents('php://input'), true);
        // // Get the Senders Graph ID
        // $sender = $input['entry'][0]['messaging'][0]['sender']['id'];
        // // Get the Senders Graph ID
        // $sender = $input['entry'][0]['messaging'][0]['sender']['id'];
        // // Get the returned message
        // $message = $input['entry'][0]['messaging'][0]['message']['text'];
        // //API Url and Access Token, generate this token value on your Facebook App Page
        // $url = 'https://graph.facebook.com/v2.6/me/messages?access_token=<ACCESS-TOKEN-VALUE>';
        // //Initiate cURL.
        // $ch = curl_init($url);
        // //The JSON data.
        // $jsonData = '{
        //     "recipient":{
        //         "id":"' . $sender . '"
        //     },
        //     "message":{
        //         "text":"The message you want to return"
        //     }
        // }';
        // //Tell cURL that we want to send a POST request.
        // curl_setopt($ch, CURLOPT_POST, 1);
        // //Attach our encoded JSON string to the POST fields.
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        // //Set the content type to application/json
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        // //Execute the request but first check if the message is not empty.
        // if (!empty($input['entry'][0]['messaging'][0]['message'])) {
        //     $result = curl_exec($ch);
        // }
    }
}

/* End of file Api.php */
/* Location: ./application/controllers/Api.php */
