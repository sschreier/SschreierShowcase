# An extension to implement a shop without a buy function and therefore as a pure showcase for Shopware 6

An extension to _implement a shop without a buy function_ and therefore as a pure showcase. To do this, you can _hide_ the _search field_, the _wish list button_, the _customer account button_ and the _cart button_ and _the prices_. _If_ the _functions are hidden_, the _pages are redirected to a self-defined target_. The _redirection can be to_ the _homepage_, a _category_ or a _landing page_.

## how to use it

### product settings

- assigning a custom **product page layout** for the product in the tab "**Layout**"

### product page layout settings

- create a **product page layout** under "**Content**" and "**Shopping Experiences**"
- replace the cms element **Buy box** with a cms element **Text** via the **replace element icon** if the cart should not be shown
- remove the cms block **Cross Selling** if you don't want to display assigned products

### shop settings

- deactivate the switch **Activate wishlist** under **Settings**, **Shop** and **Cart settings** in the area "**Wishlist**" if the wish list should not be shown
- deactivate the switch **Display buy buttons in listings** under **Settings**, **Shop** and **Products** in the area "**Product**" if the cart should not be shown

## Possible Configurations
- select, if the search field should be shown (*1)
- select, if the wish list button should be shown (*1)
- select, if the customer account button should be shown (*1)
- select, if the cart button should be shown (*1)
- select, if the prices should be shown
- select, if the search field should be right-aligned

## Possible Configurations for the redirection
- activate the redirection (*1)
- select the redirect target (*1)
- select the category to redirect to (*1)
- select the landingpage to redirect to (*1)

### (*1): some changes in the settings within the configuration are not immediately visible in the frontend of the shop
After changing settings specifically for the redirections, you should over and over again "**browse privately**" in a **new window** (in **Google Chrome** you can find the **Incognito mode** at the top right over the button **...** and **New Incognito Window**)

## How to install the extension
### via zip and console (recommended)
1. Download the latest _SschreierShowcase-master.zip_.
2. Unzip the zip file and rename the folder to _SschreierShowcase_.
3. Move the folder to the project folder _custom/plugins/_ .
4. Connect to the console via ssh:

```
bin/console plugin:refresh
bin/console plugin:install --activate SschreierShowcase
```

### via composer
1. Add the repository URL to the composer.json of the project
```
"repositories": [
    ...,
    {
        "type": "vcs",
        "url": "https://github.com/sschreier/SschreierShowcase"
    }
],
```

2. Connect to the console via ssh and install the plugin via the command
```
composer require sschreier/sschreiershowcase
bin/console plugin:refresh
bin/console plugin:install --activate SschreierShowcase
```

### via https://packagist.org
 - Connect to the console via ssh and install the plugin via the command

 ```
composer require sschreier/sschreiershowcase
bin/console plugin:refresh
bin/console plugin:install --activate SschreierShowcase
```

### via zip upload
1. Download the latest _SschreierShowcase-master.zip_.
2. Unzip the zip file and rename the folder to _SschreierShowcase_.
3. Zip the folder to _SschreierShowcase.zip_.
4. Upload the zip in the Shopware Administration.
5. Install & Activate the extension.

#### extension update (zip)
1. Download the latest _SschreierShowcase-master.zip_.
2. Unzip the zip file and rename the folder to _SschreierShowcase_.
3. Zip the folder to _SschreierShowcase.zip_.
4. Upload the zip in the Shopware Administration.
5. Update the extension.

## Images

### home page as pure showcase without search field, wish list button, customer account button and cart button and prices

![home page as pure showcase without search field, wish list button, customer account button and cart button and prices](https://www.sebastianschreier.de/plugins/SschreierShowcase/SschreierShowcase-Image1.jpg)

### product category as pure showcase without search field, wish list button, customer account button and cart button and prices

![product category as pure showcase without search field, wish list button, customer account button and cart button and prices](https://www.sebastianschreier.de/plugins/SschreierShowcase/SschreierShowcase-Image2.jpg)

### product detail page as pure showcase without search field, wish list button, customer account button, cart button, prices and buy box

![product detail page as pure showcase without search field, wish list button, customer account button, cart button, prices and buy box](https://www.sebastianschreier.de/plugins/SschreierShowcase/SschreierShowcase-Image3.jpg)

### home page as pure showcase without wish list button, customer account button, cart button and prices but with search field

![home page as pure showcase without wish list button, customer account button, cart button and prices but with search field](https://www.sebastianschreier.de/plugins/SschreierShowcase/SschreierShowcase-Image4.jpg)

### home page as pure showcase without wish list button, customer account button, cart button and prices but with opened search field with no prices

![home page as pure showcase without wish list button, customer account button, cart button and prices but with opened search field with no prices](https://www.sebastianschreier.de/plugins/SschreierShowcase/SschreierShowcase-Image5.jpg)

### product category as pure showcase without wish list button, customer account button, cart button and prices but with search field

![product category as pure showcase without wish list button, customer account button, cart button and prices but with search field](https://www.sebastianschreier.de/plugins/SschreierShowcase/SschreierShowcase-Image6.jpg)

### product detail page as pure showcase without wish list button, customer account button, cart button, prices and buy box but with search field

![product detail page as pure showcase without wish list button, customer account button, cart button, prices and buy box but with search field](https://www.sebastianschreier.de/plugins/SschreierShowcase/SschreierShowcase-Image7.jpg)

### extension configuration for case 1 part 1

![extension configuration for case 1 part 1](https://www.sebastianschreier.de/plugins/SschreierShowcase/SschreierShowcase-Image8.jpg)

### extension configuration for case 1 part 2

![extension configuration for case 1 part 2](https://www.sebastianschreier.de/plugins/SschreierShowcase/SschreierShowcase-Image9.jpg)

### extension configuration for case 2 part 1

![extension configuration for case 2 part 1](https://www.sebastianschreier.de/plugins/SschreierShowcase/SschreierShowcase-Image10.jpg)

### extension configuration for case 2 part 2

![extension configuration for case 2 part 2](https://www.sebastianschreier.de/plugins/SschreierShowcase/SschreierShowcase-Image11.jpg)

### product page layout without the cms element Buy box

![product page layout without the cms element Buy box](https://www.sebastianschreier.de/plugins/SschreierShowcase/SschreierShowcase-Image12.jpg)
