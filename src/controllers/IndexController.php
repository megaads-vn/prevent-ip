<?php
namespace Megaads\PreventIp\Controller;


use Illuminate\Routing\Controller as Controller;
use Illuminate\Support\Facades\Config as Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View as View;
use Illuminate\Support\Facades\Input as Input;
use Illuminate\Support\Facades\Response as Response;

class IndexController extends Controller
{



    public function index()
    {
        return View::make('prevent-ip::index');
    }
    public function sendRequest()
    {
        $logFilePath = app_path('storage') . '/logs/request-prevent-ip.log';
        Log::useDailyFiles($logFilePath);
        Log::info('params', Input::all());
        if (Input::get('name') && Input::get('email') && Input::get('ip') && Input::get('url')) {
            $content = '<p><b>Nhân viên yêu cầu truy cập trang web:</b></p>';
            $content .= '<p><b>- Họ tên:</b> ' . Input::get('name') . '</p>';
            $content .= '<p><b>- Email:</b> ' . Input::get('email') . '</p>';
            $content .= '<p><b>- Url:</b> ' . Input::get('url') . '</p>';
            $content .= '<p><b>- IP:</b> ' . Input::get('ip') . '</p>';
            $token = $this->getRefreshToken();
            if ($token) {
                $emailService = \Config::get('prevent-ip.email_service_url');
                $emailData['to'] = Config::get('prevent-ip.email_receive_request');
                $emailData['name'] = 'Prevent IP System';
                $emailData['subject'] = 'Prevent IP System: New request from staff';
                $emailData['content'] = $content;
                $emailData['token'] = $token;
                $this->callCronRequest($emailService . '/api/send-mail', "POST", $emailData);
            }
        }
        return Response::json(['status' => 'successful']);
    }

    private function getRefreshToken()
    {
        $emailService = \Config::get('prevent-ip.email_service_url');
        $options = array(
            'email' => \Config::get('prevent-ip.email_service_user'),
            'password' => \Config::get('prevent-ip.email_service_password')
        );
        $result = $this->sendRequestCurl($emailService . '/auth/login', $options, "POST");
        if (isset($result->token)) {
            return $result->token;
        }
        return null;
    }

    private function sendRequestCurl($url, $data = [], $method = "GET", $headers=[])
    {
        $channel = curl_init();
        curl_setopt($channel, CURLOPT_URL, $url);
        curl_setopt($channel, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($channel, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($channel, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($channel, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($channel, CURLOPT_MAXREDIRS, 3);
        curl_setopt($channel, CURLOPT_POSTREDIR, 1);
        if ( !empty($headers) ) {
            curl_setopt($channel, CURLOPT_HTTPHEADER, $headers);
        } else {
            curl_setopt($channel, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        }
        if (isset($data['timeout'])) {
            curl_setopt($channel, CURLOPT_TIMEOUT, $data['timeout']);
        } else {
            curl_setopt($channel, CURLOPT_TIMEOUT, 60);
        }

        curl_setopt($channel, CURLOPT_CONNECTTIMEOUT, 60);
        $response = curl_exec($channel);
        $responseInJson = json_decode($response);
        if (empty($responseInJson)) {
            return $response;
        }
        return isset($responseInJson->result) ? $responseInJson->result : $responseInJson;
    }

    private function callCronRequest($url, $method = "GET", $data = []) {
        $channel = curl_init();
        curl_setopt($channel, CURLOPT_URL, $url);
        curl_setopt($channel, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($channel, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($channel, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($channel, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        $response = curl_exec($channel);
        curl_close($channel);
        $responseInJson = json_decode($response);
        return isset($responseInJson->result) ? $responseInJson->result : $responseInJson;
    }
}

?>
