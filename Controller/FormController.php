<?php

declare(strict_types=1);

namespace Ekyna\Bundle\UiBundle\Controller;

use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberType;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Countries;

/**
 * Class FormController
 * @package Ekyna\Bundle\UiBundle\Controller
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class FormController
{
    private array $formJs;

    public function __construct(array $formJs)
    {
        $this->formJs = $formJs;
    }

    /**
     * Returns the form plugins configuration.
     */
    public function plugins(): Response
    {
        $response = new JsonResponse($this->formJs);

        $response
            ->setPublic()
            ->setMaxAge(3600 * 24 * 30)
            ->setSharedMaxAge(3600 * 24 * 30);;

        return $response;
    }

    /**
     * Returns the countries configuration.
     */
    public function countries(Request $request): Response
    {
        $locale = $request->query->get('locale', $request->getLocale());

        $countries = Countries::getNames($locale);
        $util = PhoneNumberUtil::getInstance();

        $config = [];
        foreach ($countries as $code => $name) {
            if (0 == $dial = $util->getCountryCodeForRegion($code)) {
                continue;
            }

            $fixed = $mobile = null;
            if ($example = $util->getExampleNumberForType($code, PhoneNumberType::FIXED_LINE)) {
                $fixed = $util->format($example, PhoneNumberFormat::NATIONAL);
            }
            if ($example = $util->getExampleNumberForType($code, PhoneNumberType::MOBILE)) {
                $mobile = $util->format($example, PhoneNumberFormat::NATIONAL);
            }

            $config[$code] = [
                'name'   => $name,
                'dial'   => $dial,
                'fixed'  => $fixed,
                'mobile' => $mobile,
            ];
        }

        // TODO Cache by locale
        // TODO Sort countries by name

        $response = new JsonResponse($config);

        $response
            ->setPublic()
            ->setMaxAge(3600 * 24 * 30)
            ->setSharedMaxAge(3600 * 24 * 30);

        return $response;
    }
}
