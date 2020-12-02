<?php


namespace Noclegowo\Editpanel\Core\Module\Coupon\Domain\Order;


use Dompdf\Dompdf;
use Dompdf\Options;
use IturDev\ViewModel\ViewModel;

class CouponPdfService
{

    protected $twig;

    /**
     * CouponPdfService constructor.
     * @param $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }


    public function generateCouponFile(ViewModel $couponOrder)
    {
        if($couponOrder)
        {
            $context = ['couponOrder'=>$couponOrder];
            $pdfOptions = new Options();
            $pdfOptions->set("isHtml5ParserEnabled", true);
            $pdfOptions->set("isRemoteEnabled", true);
            $dompdf = new Dompdf($pdfOptions);
            $template = $this->twig->loadTemplate('Email/Coupon/couponFile.html.twig');
            $htmlBody=null;
            if($template->hasBlock("body_html", $context)) {
                $htmlBody = $template->renderBlock('body_html', $context);
            }
//            $t = mb_detect_encoding($htmlBody);
            $htmlBody = mb_convert_encoding($htmlBody, 'HTML-ENTITIES', 'UTF-8');
            $dompdf->loadHtml($htmlBody, 'UTF-8');
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $output = $dompdf->output();

            $pdfFilepath =  $this->getPath() . '/'.$couponOrder->getString("hash").'.pdf';
            file_put_contents($pdfFilepath, $output);

            if(file_exists($pdfFilepath)){
                return true;
            }
            return false;
        }
    }

    public function getPathFilename(string $filename)
    {
        return $this->getPath().'/'.$filename.'.pdf';
    }

    private function getPath()
    {
        return getcwd() .'/uploads/coupons';
    }

}