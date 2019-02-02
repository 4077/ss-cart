<?php namespace ss\cart\sessionEvents\triggers\controllers;

class Notify extends \Controller
{
    public function cartPageOpen()
    {
        $mailer = mailer('mailers:dev');

        $user = $this->_user();
        $sessionKey = $this->app->session->getKey();

        $subject = 'Перешел на страницу корзины';

        /**
         * @var $svc \ss\sessionsLog\ui\controllers\Svc
         */
        $svc = $this->c('\ss\sessionsLog\ui svc');

        $avatarSrc = $svc->getAvatarSrc($sessionKey);

        if ($user) {
            $userType = 'Пользователь';
            $userName = $user->model->login;
        } else {
            $userType = 'Посетитель';
            $userName = $sessionKey;
        }

        $body[] = dt();
        $body[] = $this->userDescriptionView($userType, $userName, $avatarSrc);
        $body[] = $this->c('>orderDataLetter:view', [], true)->render();

        if (is_array($body)) {
            $body = implode("<br>", $body);
        }

        $recipients = handlers()->render('tdui/mail-recipients:all-events'); // hardcode

        foreach ($recipients as $recipient) {
            $mailer->addAddress($recipient);
        }

        $mailer->Subject = $subject;
        $mailer->Body = $body;

        $mailer->queue();
    }

    private function userDescriptionView($userType, $userName, $avatarSrc)
    {
        return
            '<table><tr>' .
            '<td valign="middle">' . $userType . '</td>' .
            '<td valign="middle"><img style="display: block;" src="' . $avatarSrc . '"></td>' .
            '<td valign="middle" style="font-weight: bold;">' . $userName . '</td>' .
            '<td valign="middle">перешел на страницу корзины</td>' .
            '</tr></table>';
    }

    public function createOrder()
    {

    }
}
