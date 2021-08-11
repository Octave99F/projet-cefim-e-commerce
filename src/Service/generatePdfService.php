<?php 

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class GeneratePdfService{

    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function renderPDF($filename,$template,$params): Response {

        // les options de pdf
        $pdfOptions = new Options();
        // la police
        $pdfOptions->set('defaultFont','Arial');

        $domPDF = new  Dompdf($pdfOptions);

        $context = stream_context_create(
            [
                'ssl' => [
                    'verify_peer' => FALSE,
                    'verify_peer_name' => FALSE,
                    'allow_self_signed' => TRUE 
                ]
            ]
        );

        $domPDF->setHttpContext($context);

        // générer pdf

        $html = $this->twig->render($template,$params);

        $domPDF->loadHtml($html);
        $domPDF->setPaper('A4', 'portrait');
        $domPDF->render();
        $domPDF->stream($filename,['Attachment'=>true]);

        return new Response();
    }

}

