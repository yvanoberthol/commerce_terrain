<?php
namespace App\Notification;

use App\Entity\Contact;
use Twig\Environment;

class ContactNotification{

    /**
     * @var \Swift_Mailer
     */
    private $mailer;


    /**
     * @var Environment
     */
    private $renderer;


    /**
     * ContactNotification constructor.
     * @param \Swift_Mailer $mailer
     * @param Environment $environment
     */
    public function __construct(\Swift_Mailer $mailer,Environment $environment)
    {
        $this->mailer = $mailer;
        $this->renderer = $environment;
    }

    public function notify(Contact $contact){

        $message = new \Swift_Message('Agence: '.$contact->getProperty()->getTitle());
        try {
            $message->setFrom('yvanoberthol@gmail.com')
                ->setTo('marieemmagam@gmail.com')
                ->setReplyTo($contact->getEmail())
                ->setBody($this->renderer->render('emails/contact.html.twig', [
                    'contact' => $contact
                ]),'text/html');
        } catch (\Twig_Error_Loader $e) {
        } catch (\Twig_Error_Runtime $e) {
        } catch (\Twig_Error_Syntax $e) {
        }

        $this->mailer->send($message);

    }
}