<?php

declare(strict_types=1);

/*
 * This file is part of the evoting.uzh.ch project.
 *
 * (c) Florian Moser <git@famoser.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Extension;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension
{
    public function __construct(private readonly TranslatorInterface $translator, private readonly RequestStack $requestStack, private readonly HttpKernelInterface $httpKernel)
    {
    }

    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('format_date', $this->formatDateFilter(...)),
            new TwigFilter('format_date_time', $this->formatDateTimeFilter(...)),
            new TwigFilter('trans_boolean', $this->transBooleanFilter(...)),
        ];
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('subRequest', $this->subRequestFunction(...)),
        ];
    }

    public function subRequestFunction(string $url): false|string
    {
        $request = Request::create($url, Request::METHOD_GET, [], [], [], ['HTTP_ACCEPT' => null]);
        $request->setSession($this->requestStack->getSession());
        $response = $this->httpKernel->handle($request, HttpKernelInterface::SUB_REQUEST);

        return $response->getContent();
    }

    public function formatDateFilter(?\DateTimeInterface $date): string
    {
        if ($date instanceof \DateTimeInterface) {
            return $date->format('d.m.Y');
        }

        return '-';
    }

    public function formatDateTimeFilter(?\DateTimeInterface $date): string
    {
        if ($date instanceof \DateTimeInterface) {
            return $date->format('d.m.Y H:i');
        }

        return '-';
    }

    public function transBooleanFilter(?bool $value): string
    {
        if ($value !== null) {
            return $this->translator->trans($value ? 'yes' : 'no');
        }

        return '-';
    }
}
