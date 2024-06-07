# Prixz WooCommerce Product Get Data

**Plugin Name:** Prixz WooCommerce Product Get Data  
**Description:** Plugin para obtener información de un producto de WooCommerce en formato JSON.  
**Version:** 1.0  
**Author:** Woo Team

## Descripción

El plugin **Prixz WooCommerce Product Get Data** crea un endpoint REST en WordPress que permite obtener información de un producto de WooCommerce utilizando una solicitud GET. El plugin realiza una autenticación para obtener un token y usa este token para recuperar la información del producto.

## Uso

Para obtener la información de un producto, realiza una solicitud GET a la siguiente URL:

```bash
https://prixz.com/wp-json/wc-product-info/v1/product/[ID]
```

Reemplaza '[ID]' con el ID del producto de WooCommerce que deseas consultar.

## Ejemplo de Respuesta

```json
{
    "error": false,
    "statusCode": 200,
    "payload": [
        {
            "productTwo": "6613507",
            "frecuency": 8,
            "uuidStudy": "5e376490-bcdf-4a84-8406-d577bfedcdb9",
            "periodicity": "YEAR",
            "productOne": "6642335",
            "ordersBased": [
                "8754245",
                "8387051",
                "8387051",
                "8400174",
                "8909009",
                "8987139",
                "9059156",
                "9088591"
            ],
            "uid": "b5e1db78-2896-406d-8614-57ced0687cc4"
        }
    ]
}
```


