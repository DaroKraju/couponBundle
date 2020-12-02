<?php


namespace Noclegowo\Editpanel\Core\Module\Coupon\Application;


use Dompdf\Dompdf;
use Dompdf\Options;
use IturDev\CQRSBundle\Command\CommandHandler;
use IturDev\Domain\Identity\Email;
use IturDev\Domain\Money\Price;
use IturDev\Query\QueryRegistry;
use Noclegowo\Editpanel\Core\Module\Coupon\Domain\CouponOrderRepository;
use Noclegowo\Editpanel\Core\Module\Coupon\Domain\Order\CouponOrder;
use Noclegowo\Editpanel\Core\Module\Coupon\Domain\Order\CouponPdfService;
use Noclegowo\Editpanel\Core\Module\Coupon\Domain\Order\PersonalData;
use Noclegowo\Editpanel\Core\Module\Reservation\Application\RejectReservationOrderBySecurityErrorCommand;
use Noclegowo\Editpanel\Ui\UiBundle\Payment\BluemediaService;
use Psr\Log\LoggerInterface;

class CouponApplicationService implements CommandHandler
{
    /**
     * @var QueryRegistry
     */
    private $query;

    /**
     * @var CouponOrderRepository
     */
    private $couponOrderRepository;


    /**
     * @var BluemediaService
     */
    private $bluemediaService;


    /**
     * @var LoggerInterface
     */
    private $bluemediaLogger;

    /**
     * CouponApplicationService constructor.
     * @param QueryRegistry $query
     * @param CouponOrderRepository $couponOrderRepository
     */
    public function __construct(CouponOrderRepository $couponOrderRepository, QueryRegistry $query, BluemediaService $bluemediaService, LoggerInterface $bluemediaLogger)
    {
        $this->query = $query;
        $this->couponOrderRepository = $couponOrderRepository;
        $this->bluemediaService = $bluemediaService;
        $this->bluemediaLogger = $bluemediaLogger;
    }

    public function addCouponOrder(AddCouponOrderCommand $command)
    {
        $userProfileId = $command->user_profile_id;
        $personalData = new PersonalData(new Email($command->email), $userProfileId, $command->session_user_id );
        $price = new Price($command->price, "PLN");

        $couponOrder = new CouponOrder($price, $personalData, new \DateTime(date("c", $command->time)));
        $this->couponOrderRepository->save($couponOrder);

        $dateTime = $couponOrder->getCreatedAt();
        $paymentTimeout = new \DateTime( $dateTime->format("Y-m-d H:i:s") .' +1 day');
        $paymentUrl = $this->bluemediaService->generateCouponPaymentLink($couponOrder->getHash(), '', 0);

        $couponOrder->readyToPay($paymentUrl, $paymentTimeout);
        $this->couponOrderRepository->save($couponOrder);

    }

    public function updateCouponOrderFinalized(UpdateCouponOrderFinalizedCommand $command)
    {
        $couponOrder = $this->couponOrderRepository->findById($command->coupon_id);
        if(!$couponOrder){
            throw new \InvalidArgumentException("Invalid input parameters.");
        }
        if($couponOrder && !$couponOrder->getCouponOrderStatus()->isPaid()){
            $couponOrder->markAsPaid(new \DateTime(date("c", $command->timestamp)));
            $this->couponOrderRepository->save($couponOrder);
        }
    }

    public function rejectCouponOrderBySecurityError(RejectCouponOrderBySecurityErrorCommand $command)
    {
        $couponOrder = $this->couponOrderRepository->findById($command->coupon_id);
        if($couponOrder && !$couponOrder->getCouponOrderStatus()->isPaid()){
            $dateTime = new \DateTime(date("c", $command->time));
            $couponOrder->rejectBySecurityError($dateTime);
            $this->couponOrderRepository->save($couponOrder);
        }
    }

    public function rejectCouponOrderByInvalidTransactionData(RejectCouponOrderByInvalidTransactionDataCommand $command)
    {
        $couponOrder = $this->couponOrderRepository->findById($command->coupon_id);
        if($couponOrder && !$couponOrder->getCouponOrderStatus()->isPaid()){
            $dateTime = new \DateTime(date("c", $command->time));
            $couponOrder->rejectByInvalidData($dateTime);
            $this->couponOrderRepository->save($couponOrder);
        }
    }

    public function rejectCouponOrderByUserTimeout(RejectCouponOrderByUserTimeoutCommand $command)
    {
        $couponOrder = $this->couponOrderRepository->findById($command->coupon_id);
        if($couponOrder && !$couponOrder->getCouponOrderStatus()->isPaid()){
            $dateTime = new \DateTime(date("c", $command->time));
            $couponOrder->rejectByUserTimeout($dateTime);
            $this->couponOrderRepository->save($couponOrder);
        }
    }
}