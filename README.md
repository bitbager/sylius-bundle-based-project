# Installation

```bash
$ composer install
$ bin/console do:da:cre
$ bin/console do:sche:cre
$ bin/console sy:fi:lo --no-interaction
```

# API calls

## Create an order for made to order product

```bash
$ curl --header "Content-Type: application/json" --request POST --data '{"side": "left", "radius": "r_850", "power": "p_600", "cylinder": "c_125", "axes": "a_20", "packages": "pcg_10"}' http://localhost:8000/add-options-to-cart/contact_pad
```

## Create an order for standard product

```bash
$ curl --header "Content-Type: application/json" --request POST --data '{"variant": "ray_ban_glasses_pcg_1"}' http://localhost:8000/add-variant-to-cart/ray_ban_glasses
```
