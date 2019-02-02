<?php namespace ss\cart\Svc;

// todo del

class Mailer extends \ewma\Service\Service
{
    protected $services = ['svc'];

    /**
     * @var \ss\cart\Svc
     */
    public $svc = \ss\cart\Svc::class;

    private $instance;

    public function boot()
    {
        $this->instance = $this->svc->instance;

    }

    //
    //
    //


    public function get($sender = false)
    {
        $mailer = $this->getDefaultMailer();

//        $mailerData = cart($this->instance)->settings('mailer');
//
//        if (!$sender) {
//            $sender = $mailerData['default_sender'];
//        }
//
//        $senderData = cart($this->instance)->settings('mailer/senders/' . $sender);
//
//        $mailer->isSMTP();
//        $mailer->SMTPAuth = true;
//
//        \ewma\Data\Data::extract($mailer, $senderData, '
//            Host        host,
//            Port        port,
//            Username    user,
//            Password    pass,
//            SMTPSecure  smtp_secure,
//            From        user,
//            FromName    from_name
//        ');
//
//        if ($debug = cart($this->instance)->settings('mailer/debug')) {
//            $mailer->SMTPDebug = 2;
//        }
//
//        $mailer->IsHTML();
//
//        if ($bccs = l2a($mailerData['bcc_recipients'])) {
//            foreach ($bccs as $bcc) {
//                $mailer->addBCC($bcc);
//            }
//        }

        return $mailer;
    }

    /**
     * @return \std\mailer\Mailer
     */
    private function getDefaultMailer()
    {
        return mailer('tdui/mailer:');
//        return appc('\std\mailer~:get');
    }
}
