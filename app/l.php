<?php
#echo strtotime((new DateTime(date('Y-m-d')))->modify('first day of this month')->format('Y-m-d'));
#echo ((new DateTime(date('Y-m-d')))->modify('last day of next month')->format('Y-m-d'));


function curl_json_post($url, $post)
    {
        $data = json_encode($post);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Accept: application/json',
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
        );
            if( ! $result = curl_exec($ch))
            {
                return curl_error($ch);
            }

       curl_close($ch);
           return $result;
    }

$postdata = array('username' => '12956', 
                 'password' => 'ghsghs', 
                  'passwordagain' => 'ghsghs', 
                  'email' => '12956@cch.gh.com',
                  'firstname' => 'Diana',
                  'lastname'  => 'Emefa Fiamegu');

                $url = 'http://localhost/cch/oppia/api/v1/register/';
                $response = curl_json_post($url, $postdata);
print $response;
?>
