<?php
declare(strict_types=1);

namespace Sschreier\Showcase\Storefront\Subscriber;

use Shopware\Core\Framework\Adapter\Cache\Event\HttpCacheHitEvent;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Framework\Routing\Router;
use Shopware\Storefront\Page\Checkout\Cart\CheckoutCartPageLoadedEvent;
use Shopware\Storefront\Page\Account\Login\AccountLoginPageLoadedEvent;
use Shopware\Storefront\Page\Search\SearchPageLoadedEvent;
use Shopware\Storefront\Page\Wishlist\GuestWishlistPageLoadedEvent;
use Shopware\Storefront\Page\Wishlist\WishlistPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;

class FrontendSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly RequestMatcherInterface $matcher,
        private readonly Router $router,
        private readonly SystemConfigService $systemConfigService,
    )
    {
        $this->salesChannelId = null;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AccountLoginPageLoadedEvent::class => 'onAccountLoginPageLoaded',
            CheckoutCartPageLoadedEvent::class => 'onCheckoutCartPageLoaded',
            SearchPageLoadedEvent::class => 'onSearchLoaded',
            HttpCacheHitEvent::class => 'onCachedPageLoaded',
            GuestWishlistPageLoadedEvent::class => 'onWishlistLoaded',
            WishlistPageLoadedEvent::class => 'onWishlistLoaded',
        ];
    }

    public function onAccountLoginPageLoaded(AccountLoginPageLoadedEvent $event): void
    {
        $this->salesChannelId = $this->getSalesChannelId($event);

        if ($this->isActive() && !((bool) $this->systemConfigService->get('SschreierShowcase.config.showCustomerAccountButton', $this->salesChannelId))) {
            $this->redirectRequest();
        }
    }

    public function onCheckoutCartPageLoaded(CheckoutCartPageLoadedEvent $event): void
    {
        $this->salesChannelId = $this->getSalesChannelId($event);

        if ($this->isActive() && !((bool) $this->systemConfigService->get('SschreierShowcase.config.showCartButton', $this->salesChannelId))) {
            $this->redirectRequest();
        }
    }

    public function onSearchLoaded(SearchPageLoadedEvent $event): void
    {
        $this->salesChannelId = $this->getSalesChannelId($event);

        if ($this->isActive() && !((bool) $this->systemConfigService->get('SschreierShowcase.config.showSearchField', $this->salesChannelId))) {
            $this->redirectRequest();
        }
    }

    public function onCachedPageLoaded(HttpCacheHitEvent $event): void
    {
        if ($this->matcher instanceof RequestMatcherInterface) {
            $parameters = $this->matcher->matchRequest($event->request);
        } else {
            $parameters = $this->matcher->match($event->request->getPathInfo());
        }

        $this->salesChannelId = $event->request->attributes->get('sw-sales-channel-id');

        if($this->isActive() && ($parameters['_route'] === "frontend.search.page") && (!((bool) $this->systemConfigService->get('SschreierShowcase.config.showSearchField', $this->salesChannelId)))) {
            $this->redirectRequest();
        }

        return;
    }

    public function onWishlistLoaded(GuestWishlistPageLoadedEvent | WishlistPageLoadedEvent $event): void
    {
        $this->salesChannelId = $this->getSalesChannelId($event);

        if ($this->isActive() && !((bool) $this->systemConfigService->get('SschreierShowcase.config.showWishListButton', $this->salesChannelId))) {
            $this->redirectRequest();
        }
    }

    private function getSalesChannelId($event): ?string
    {
        return $event->getRequest()->attributes->get('sw-sales-channel-id');
    }

    private function isActive(): bool
    {
        return (bool) $this->systemConfigService->get('SschreierShowcase.config.redirectionIsActive', $this->salesChannelId);
    }

    private function redirectRequest(): void
    {
        $redirectTarget = $this->systemConfigService->get('SschreierShowcase.config.redirectTarget', $this->salesChannelId);

        $path = $this->router->generate('frontend.home.page');

        if ($redirectTarget === "category") {
            $redirectToCategory = $this->systemConfigService->get('SschreierShowcase.config.redirectToCategory', $this->salesChannelId);

            if ($redirectToCategory !== null) {
                $path = $this->router->generate('frontend.navigation.page', ['navigationId' => $redirectToCategory]);
            }
        } elseif ($redirectTarget === "landing_page") {
            $redirectToLandingpage = $this->systemConfigService->get('SschreierShowcase.config.redirectToLandingpage', $this->salesChannelId);

            if ($redirectToLandingpage !== null) {
                $path = $this->router->generate('frontend.landing.page', ['landingPageId' => $redirectToLandingpage]);
            }
        }

        $response = new RedirectResponse($path, 301);
        $response->send();
    }
}
