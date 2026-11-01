<?php
declare(strict_types=1);

/*
 * (c) Sebastian Schreier <info@web-solutions-dresden.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sschreier\Showcase\Storefront\Subscriber;

use Shopware\Core\Framework\Adapter\Cache\Event\HttpCacheHitEvent;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Framework\Routing\Router;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class FrontendSubscriber implements EventSubscriberInterface
{
    private const RESTRICTED_ROUTES = [
        'frontend.account.login.page' => 'showCustomerAccountButton',
        'frontend.checkout.cart.page' => 'showCartButton',
        'frontend.search.page' => 'showSearchField',
        'frontend.wishlist.page' => 'showWishListButton',
    ];

    public function __construct(
        private readonly Router $router,
        private readonly SystemConfigService $systemConfigService,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 0],
            HttpCacheHitEvent::class => 'onCachedPageLoaded',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $route = $event->getRequest()->attributes->get('_route');
        if (!is_string($route)) {
            return;
        }

        $response = $this->createRedirectResponse($route, $event->getRequest());
        if ($response !== null) {
            $event->setResponse($response);
        }
    }

    public function onCachedPageLoaded(HttpCacheHitEvent $event): void
    {
        $route = $event->request->attributes->get('_route');
        if (!is_string($route)) {
            $parameters = $this->router->matchRequest($event->request);
            $route = $parameters['_route'] ?? null;
        }

        if (!is_string($route)) {
            return;
        }

        $redirect = $this->createRedirectResponse($route, $event->request);
        if ($redirect === null) {
            return;
        }

        $response = $event->response;
        $response->setStatusCode(Response::HTTP_FOUND);
        $response->setContent('');
        $response->headers->set('Location', $redirect->getTargetUrl());
        $response->headers->set('Cache-Control', 'no-store, private');
        $response->headers->remove('Content-Length');
        $response->headers->remove('Content-Encoding');
        $response->headers->remove('Content-Range');
        $response->headers->remove('ETag');
        $response->headers->remove('Last-Modified');
    }

    private function createRedirectResponse(string $route, Request $request): ?RedirectResponse
    {
        $configKey = self::RESTRICTED_ROUTES[$route] ?? null;
        if ($configKey === null) {
            return null;
        }

        $salesChannelId = $request->attributes->get('sw-sales-channel-id');
        if (!is_string($salesChannelId)) {
            $salesChannelId = null;
        }

        if (!(bool) $this->systemConfigService->get('SschreierShowcase.config.redirectionIsActive', $salesChannelId)) {
            return null;
        }

        if ((bool) $this->systemConfigService->get('SschreierShowcase.config.' . $configKey, $salesChannelId)) {
            return null;
        }

        return new RedirectResponse($this->getRedirectPath($salesChannelId), Response::HTTP_FOUND);
    }

    private function getRedirectPath(?string $salesChannelId): string
    {
        $redirectTarget = $this->systemConfigService->get('SschreierShowcase.config.redirectTarget', $salesChannelId);

        if ($redirectTarget === 'category') {
            $categoryId = $this->systemConfigService->get('SschreierShowcase.config.redirectToCategory', $salesChannelId);
            if (is_string($categoryId) && $categoryId !== '') {
                return $this->router->generate('frontend.navigation.page', ['navigationId' => $categoryId]);
            }
        }

        if ($redirectTarget === 'landing_page') {
            $landingPageId = $this->systemConfigService->get('SschreierShowcase.config.redirectToLandingpage', $salesChannelId);
            if (is_string($landingPageId) && $landingPageId !== '') {
                return $this->router->generate('frontend.landing.page', ['landingPageId' => $landingPageId]);
            }
        }

        return $this->router->generate('frontend.home.page');
    }
}
