<?php

namespace App\Controller;

use App\Entity\QuotesRequest;
use App\Form\QuotesRequestType;
use App\Message\EmailDataMessage;
use App\Service\CompaniesDataService;
use App\Service\HistoricalDataService;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class AppController
 *
 * @package App\Controller
 *
 * @Route("/api")
 */
class AppController extends AbstractController
{
    private CompaniesDataService $companiesDataService;

    public function __construct(CompaniesDataService $companiesDataService)
    {
        $this->companiesDataService = $companiesDataService;
    }

    /**
     * @Route("/form_company_request", name="form_submit", methods={"POST"})
     */
    public function submitForm(
        Request $request,
        HistoricalDataService $dataService
    )
    {
        $quotesRequest = new QuotesRequest();
        $form = $this->createForm(QuotesRequestType::class, $quotesRequest);

        try {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->sendDetailsEmail($quotesRequest);

                return new JsonResponse($dataService->getData($quotesRequest));
            }

            if (!$form->isValid()) {
                $errors = [];
                /** @var FormError $error */
                foreach ($form->getErrors(true) as $error) {
                    $errors[] = $error->getMessage() . $error->getOrigin()->getName();
                }

                return new JsonResponse(['errors' => $errors]);
            }
        } catch (\Throwable $exception) {
            return new JsonResponse(['errors' => [$exception->getMessage()]], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse(['errors' => ['Unknown error']], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Route("/companies_data", name="companies_data", methods={"GET"})
     */
    public function getCompaniesData(CompaniesDataService $dataService) {
        return new JsonResponse($dataService->getData());
    }

    private function sendDetailsEmail(QuotesRequest $quotesRequest)
    {
        $this->dispatchMessage(new EmailDataMessage(
           $quotesRequest->getEmail(),
           $this->getCompanyName($quotesRequest->getCompanyCode()),
           $quotesRequest->getStartDate(),
           $quotesRequest->getEndDate(),
       ));
    }

    private function getCompanyName(string $companyCode): string
    {
        $companies = $this->companiesDataService->getData();

        foreach ($companies as $company) {
            if (array_key_exists('Symbol', $company)) {
                if ($company['Symbol'] === $companyCode && array_key_exists('Company Name', $company)) {
                    return $company['Company Name'];
                }
            }
        }

        return '';
    }
}
