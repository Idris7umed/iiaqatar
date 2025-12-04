<?php

namespace App\Traits;

trait SkipCashPaymentTrait
{
    /**
     * Generate Payment Link
     * @param array $postData
     * @return string JSON response
     */
    public function generatePaymentLinkSkipcash($postData)
    {
        try {
            $postData['Amount'] = strval($postData['Amount']);
            $data_string = json_encode($postData);
            $resultheader = '';
            
            foreach ($postData as $key => $value) {
                $resultheader .= $key.'='.$value.',';
            }
            
            $resultheader = rtrim($resultheader, ',');
            $s = hash_hmac('sha256', $resultheader, config('skipcash.key_secret'), true);
            $authorisationheader = base64_encode($s);
            
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => config('skipcash.url').'/api/v1/payments',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $data_string,
                CURLOPT_HTTPHEADER => [
                    'Content-Type:application/json',
                    'Authorization:'.$authorisationheader,
                ],
            ]);
            
            $response = curl_exec($curl);
            
            if ($response === false) {
                $error_message = curl_error($curl);
                $error_code = curl_errno($curl);
                curl_close($curl);
                
                return json_encode([
                    'success' => false,
                    'error' => "cURL error (code $error_code): $error_message",
                ]);
            }
            
            curl_close($curl);
            
            return $response;
        } catch (\Throwable $e) {
            return json_encode([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Validate payment
     * @param string $payment_id
     * @return string JSON response
     */
    public function validatePaymentSkipcash($payment_id)
    {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => config('skipcash.url').'/api/v1/payments/'.$payment_id,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => [
                    'Content-Type:application/json',
                    'Accept: application/json',
                    'Authorization: '.config('skipcash.client_id'),
                ],
            ]);
            
            $response = curl_exec($curl);
            
            if ($response === false) {
                $error_message = curl_error($curl);
                $error_code = curl_errno($curl);
                curl_close($curl);
                
                return json_encode([
                    'success' => false,
                    'error' => "cURL error (code $error_code): $error_message",
                ]);
            }
            
            curl_close($curl);
            
            return $response;
        } catch (\Throwable $e) {
            return json_encode([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
