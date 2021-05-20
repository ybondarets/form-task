<?php

namespace App\Form;

use App\Entity\QuotesRequest;
use App\Service\CompaniesDataService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\BaseDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToTimestampTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuotesRequestType extends AbstractType
{
    private CompaniesDataService $dataService;

    public function __construct(CompaniesDataService $dataService)
    {
        $this->dataService = $dataService;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', EmailType::class)
            ->add('companyCode', ChoiceType::class, [
                'choices' => $this->getCompanyCodes()
            ])
            ->add('startDate', DateTimeType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'Y-m-d'
            ])
            ->add('endDate', DateTimeType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'Y-m-d'
            ])
            ->add('submit', SubmitType::class);
    }

    private function getCompanyCodes(): array {
        $result = [];
        $companiesData = $this->dataService->getData();

        foreach ($companiesData as $companyData) {
            if (array_key_exists('Symbol', $companyData)) {
                $result[$companyData['Symbol']] = $companyData['Symbol'];
            }
        }

        return $result;
    }
}