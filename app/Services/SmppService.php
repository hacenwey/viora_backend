<?php

namespace App\Services;

class SmppService {

    public function sendSMS($phone, $text)
        {
            try {
                $transport = new SocketTransport([env('SMPP_HOST')], env('SMPP_PORT'));
                $transport->setRecvTimeout(10000);
            
                $smpp = new SmppClient($transport);
                $smpp->connect();
            
                $encodedMessage = GsmEncoder::utf8_to_gsm0338($text);
                $from = new SmppAddress(env('SMPP_SOURCE_ADDRESS'), SMPP::TON_ALPHANUMERIC);
                $to = new SmppAddress($phone, SMPP::TON_INTERNATIONAL, SMPP::NPI_E164);
                $messageID = $smpp->sendSMS($from, $to, $encodedMessage);
            
                $smpp->disconnect();
            } catch (\Exception $e) {
                return [
                    'message' => $e->getMessage(),
                    'status' => false
                ];
            }
            return [
                'message' => 'Success',
                'status' => true
            ];
        }
}