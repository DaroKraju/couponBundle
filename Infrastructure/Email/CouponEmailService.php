<?php


namespace Noclegowo\Editpanel\Core\Module\Coupon\Infrastructure\Email;


use IturDev\CQRSBundle\Event\EventListener;
use IturDev\Domain\Identity\Email;
use IturDev\Query\QueryRegistry;
use IturDev\Task\EmsMailer;
use IturDev\ViewModel\ViewModel;
use Noclegowo\Editpanel\Core\Module\Coupon\Domain\Order\CouponPaymentFinalizedEvent;
use Noclegowo\Editpanel\Core\Module\Coupon\Domain\Order\CouponPdfService;
use Noclegowo\Editpanel\Core\Module\Email\MailerInterface;

class CouponEmailService implements EventListener
{
    /**
     * @var QueryRegistry
     */
    private $query;

    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var CouponPdfService
     */
    private $couponPdfService;


    /**
     * CouponEmailService constructor.
     * @param QueryRegistry $query
     * @param MailerInterface $mailer
     * @param EmsMailer $emsMailer
     */
    public function __construct(QueryRegistry $query, MailerInterface $mailer, CouponPdfService $couponPdfService)
    {
        $this->query = $query;
        $this->mailer = $mailer;
        $this->couponPdfService = $couponPdfService;
    }

    public function onCouponPaymentFinalizedEvent(CouponPaymentFinalizedEvent $event){
        list($couponId,$hash) = [$event->getCouponId(), $event->getHash()];
        $couponOrder = $this->findCoupon($couponId);
        $isPdfFile = $this->couponPdfService->generateCouponFile($couponOrder);
        if(!$isPdfFile){
            //send info to daro
        }
        $toEmail = $couponOrder->getString("email");
        $templateName = "Email/Coupon/confirmPayMessage.html.twig";
        $context = [
            'couponOrder' =>$couponOrder
        ];
        $bccEmails = $this->getBccEmails();
        $senderEmail = $this->getSenderReservationEmail();
        $attachFile = $this->couponPdfService->getPathFilename($couponOrder->getString("hash"));
        $this->mailer->sendMessage($templateName, $context, new Email($toEmail), $bccEmails, false, $senderEmail, false, false, false, false, $attachFile);
    }

    /**
     * @param $conversationId
     * @return \IturDev\ViewModel\ViewModel
     * @throws \Exception
     */
    protected function findCoupon($couponId): ViewModel
    {
        $couponOrder = $this->query->from("CouponOrder")->select("preview")->filter("byId", $couponId)->findOne();
        if (!$couponOrder) {
            throw new \InvalidArgumentException("Coupon $couponId not found");
        }
        return $couponOrder;
    }

    /**
     * @return array
     */
    protected function getBccEmails(): array
    {
        $bcc=[];
        $bcc[]='rezerwacje@itur.pl';
        return $bcc;
    }

    protected function getSenderReservationEmail(){
        return "rezerwacja@noclegowo.pl";
    }

}