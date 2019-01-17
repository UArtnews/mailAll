<?php

class SwitchMail {
    static public $passwords = array(
        'uazipmail' => 'nhriuxis3911',
        'uamemo' => 'cyawljne76',
        'uamail' => 'bwzrshsf89'
    );

    static public function gmail($username){
        if(array_key_exists($username, SwitchMail::$passwords)){
            $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl');
            $transport->setUsername($username.'@zips.uakron.edu');
            $transport->setPassword(SwitchMail::$passwords[$username]);
            $gmail = new Swift_Mailer($transport);
            Mail::setSwiftMailer($gmail);
        }else{
            return Redirect::back()->withError('No Email Address Configured for this publication!');
        }
    }

    static public function getAddresses(){
        $addresses = array();
        foreach(SwitchMail::$passwords as $address => $password){
            array_push($addresses, $address);
        }

        return $addresses;
    }
}