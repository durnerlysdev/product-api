# Documentación API RESTful de Gestión de Productos

Este repositorio contiene una API RESTful desarrollada con **Laravel** para la gestión de productos, incluyendo la capacidad de registrar precios en múltiples divisas. La autenticación se realiza mediante **Laravel Sanctum**, utilizando tokens de API.

---

## Tabla de Contenidos

1.  [¿Qué hace esta API?](#qué-hace-esta-api)
2.  [Requisitos del Sistema](#requisitos-del-sistema)
3.  [Instalación del Proyecto](#instalación-del-proyecto)
    -   [Clonar el Repositorio](#clonar-el-repositorio)
    -   [Instalar Dependencias](#instalar-dependencias)
    -   [Configuración de Base de Datos](#configuración-de-base-de-datos)
    -   [Ejecutar Migraciones y Seeders](#ejecutar-migraciones-y-seeders)
    -   [Iniciar el Servidor de Desarrollo](#iniciar-el-servidor-de-desarrollo)
4.  [Uso de la API](#uso-de-la-api)
    -   [Autenticación con Laravel Sanctum (Obtener Token)](#autenticación-con-laravel-sanctum-obtener-token)
    -   [Endpoints de la API](#endpoints-de-la-api)
        -   [`GET /api/products`](#get-apiproducts)
        -   [`POST /api/products`](#post-apiproducts)
        -   [`GET /api/products/{id}`](#get-apiproductsid)
        -   [`PUT /api/products/{id}`](#put-apiproductsid)
        -   [`DELETE /api/products/{id}`](#delete-apiproductsid)
        -   [`GET /api/products/{id}/prices`](#get-apiproductsidprices)
        -   [`POST /api/products/{id}/prices`](#post-apiproductsidprices)
5.  [Estructura de las Respuestas JSON](#estructura-de-las-respuestas-json)
6.  [Uso de Postman para Pruebas](#uso-de-postman-para-pruebas)
    -   [Importar la Colección](#importar-la-colección)
    -   [Configuración Global de Autenticación en Postman](#configuración-global-de-autenticación-en-postman)
7.  [Notas de Seguridad](#notas-de-seguridad)

---

## ¿Qué hace esta API?

Esta API RESTful está diseñada para gestionar un **catálogo de productos** y sus precios en **múltiples divisas**. Te permite realizar las operaciones básicas de creación, lectura, actualización y eliminación (CRUD) para productos, así como gestionar sus precios específicos.

**Características principales:**

-   **Gestión de Productos:** Crea, lee, actualiza y elimina productos.
-   **Atributos del Producto:** Cada producto tiene un nombre, descripción, precio base, divisa base, costo de impuestos y costo de fabricación.
-   **Precios Multidivisa:** Registra diferentes precios para un mismo producto en varias divisas, utilizando tasas de cambio.
-   **Formato JSON:** Todas las respuestas de la API están en formato JSON, con una estructura consistente de `message`, `data` y `status`.
-   **Base de Datos:** Utiliza **Eloquent ORM** de Laravel para interactuar con tu base de datos relacional.
-   **Autenticación:** Implementa autenticación basada en **tokens de API** mediante **Laravel Sanctum**, asegurando que solo usuarios autorizados puedan acceder a los endpoints protegidos.

---

## Requisitos del Sistema

Para ejecutar este proyecto, necesitas tener instalado lo siguiente:

-   **PHP:** Versión 8.2 o superior (Laravel 12 requiere PHP 8.2+).
-   **Composer:** El gestor de paquetes de PHP.
-   **Base de Datos:** MySQL.

---

## Instalación del Proyecto

Sigue estos pasos para configurar y ejecutar la API en tu entorno local:

### Clonar el Repositorio

Primero, clona el repositorio de Git en tu máquina local:

```bash
git clone https://github.com/durnerlysdev/product-api.git
cd product-api
```

### Instalar Dependencias

Una vez dentro del directorio del proyecto, instala las dependencias de PHP usando Composer:

```

composer install

```

### Configuración de Base de Datos

Abre el archivo .env y configura los detalles de tu conexión a la base de datos. Aquí un ejemplo para MySQL:

```

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=product_api_db # Nombre de base de datos
DB_USERNAME=root # Usuario BD
DB_PASSWORD= # Contraseña de DB

```

### Ejecutar Migraciones y Seeders

Ejecuta las migraciones de la base de datos para crear las tablas necesarias y luego los seeders para poblar la base de datos con datos de prueba (incluyendo un usuario y divisas):

```

php artisan migrate:fresh --seed

```

**Notas sobre los Seeders**:

-   Se creará un usuario de prueba:
    -   Email: [test@example.com](mailto:test@example.com)
    -   Contraseña: password (Este usuario es necesario para obtener un token de API y probar los endpoints protegidos).
-   Se crearán divisas (USD, EUR, GBP) y algunos productos de ejemplo con precios asociados.

### Iniciar el Servidor de Desarrollo

Inicia el servidor de desarrollo de Laravel:

```

php artisan serve

```

La API estará disponible en

```
http://127.0.0.1:8000/api

```

## Uso de la API

Todos los endpoints de la API están protegidos por autenticación con Laravel Sanctum. Esto significa que para interactuar con ellos, primero debes obtener un token de API.

### Autenticación con Laravel Sanctum (Obtener Token)

Laravel Sanctum es un paquete ligero que proporciona un sistema de autenticación de API simple y robusto basado en tokens. Cada usuario autenticado recibe un "token" único que luego se utiliza para autorizar las solicitudes a la API.

Para obtener un token:

-   **Endpoint**:

```
    /api/login

```

-   **Método**: POST
-   **Headers**:
    -   Content-Type: application/json
    -   Accept: application/json
-   **Body (JSON)**:

```

{
    "email": "test@example.com",
    "password": "password"
}

```

-   **Respuesta Exitosa (200 OK)**:

```

{
    "token": "2|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
}

```

Guarda este token. Lo necesitarás para todas las demás peticiones. En Postman, se recomienda almacenarlo en una variable de entorno (ej. authToken).

### Cómo enviar el token en las peticiones:

Para autenticar tus peticiones a los endpoints protegidos, debes incluir el token en el encabezado Authorization con el esquema Bearer.

**Ejemplo de Encabezado**:

```

Authorization: Bearer <TU_TOKEN_AQUI>

```

Donde

```
<TU_TOKEN_AQUI>

```

es el valor del token que obtuviste del endpoint

```
/api/login

```

## Endpoints de la API

Todos los endpoints listados a continuación requieren el encabezado, revisar [Configuración Global de Autenticación en Postman](#configuración-global-de-autenticación-en-postman) para mayor rapidez.

```
Authorization: Bearer <TU_TOKEN>

```

en la petición.

### GET /api/products

-   **Descripción**: Obtiene una lista de todos los productos disponibles.
-   **Método**: GET
-   **Headers**:
    -   Accept: application/json
    -   Authorization: Bearer <TU_TOKEN>
-   **Cuerpo de Respuesta (200 OK)**:

```

{
    "message": "Lista de productos recuperada exitosamente.",
    "data": [
        {
            "id": 1,
            "name": "Laptop Gamer",
            "description": "Potente laptop para gaming.",
            "price": "1200.00",
            "currency_id": 1,
            "tax_cost": "100.00",
            "manufacturing_cost": "800.00",
            "created_at": "...",
            "updated_at": "...",
            "currency": { /* Detalles de la divisa */ }
        }
        // ... más productos
    ],
    "status": 200
}

```

### POST /api/products

-   **Descripción**: Crea un nuevo producto.
-   **Método**: POST
-   **Headers**: Content-Type: application/json, Accept: application/json, Authorization: Bearer
-   **Body (JSON)**:

```

{
    "name": "Teclado Mecánico RGB",
    "description": "Teclado de alta calidad con switches Cherry MX y retroiluminación RGB.",
    "price": 150.00,
    "currency_id": 1, // ID de la divisa base (ej. 1 para USD)
    "tax_cost": 15.00,
    "manufacturing_cost": 75.00
}

```

-   **Cuerpo de Respuesta (201 Created)**:

```

{
    "message": "Producto creado exitosamente.",
    "data": { /_ Detalles del producto creado _/ },
    "status": 201
}

```

-   **Errores de Validación (422 Unprocessable Entity)**:

```

{
    "message": "The given data was invalid.",
    "errors": { /_ Detalles de los errores de validación _/ }
}

```

### GET /api/products/{id}

-   **Descripción**: Obtiene los detalles de un producto específico, incluyendo su divisa base y todos sus precios asociados en diferentes divisas.
-   **Método**: GET
-   **Headers**: Accept: application/json, Authorization: Bearer
-   **Parámetros de Ruta**: id (integer) - El ID del producto.
-   **Cuerpo de Respuesta (200 OK)**:

```

{
    "message": "Detalles del producto recuperados exitosamente.",
    "data": {
        "id": 1,
        "name": "Laptop Gamer",
        // ... otros atributos
        "base*currency": { /* Detalles de la divisa base \_/ },
        "prices": [
            {
                "id": 1,
                "price": "1104.00",
                "currency": { /* Detalles de la divisa del precio */ }
            }
            // ... más precios
        ]
    },
    "status": 200
}

```

-   **Producto No Encontrado (404 Not Found)**:

```

{
    "message": "El recurso solicitado no fue encontrado.",
    "code": 404
}

```

### PUT /api/products/{id}

-   **Descripción**: Actualiza un producto existente. Permite actualizaciones parciales (solo envía los campos a modificar).
-   **Método**: PUT
-   **Headers**: Content-Type: application/json, Accept: application/json, Authorization: Bearer
-   **Parámetros de Ruta**: id (integer) - El ID del producto a actualizar.
-   **Body (JSON)**:

```

{
    "name": "Teclado Mecánico RGB Pro",
    "price": 160.00
}

```

-   **Cuerpo de Respuesta (200 OK)**:

```

{
    "message": "Producto actualizado exitosamente.",
    "data": { /_ Detalles del producto actualizado _/ },
    "status": 200
}

```

-   **Producto No Encontrado (404 Not Found)**:

```

{
    "message": "El recurso solicitado no fue encontrado.",
    "code": 404
}

```

-   **Errores de Validación (422 Unprocessable Entity)**:

```

{
    "message": "The given data was invalid.",
    "errors": { /_ Detalles de los errores de validación _/ }
}

```

### DELETE /api/products/{id}

-   **Descripción**: Elimina un producto. La eliminación en cascada de los precios asociados es manejada por la base de datos.
-   **Método**: DELETE
-   **Headers**: Accept: application/json, Authorization: Bearer
-   **Parámetros de Ruta**: id (integer) - El ID del producto a eliminar.
-   **Cuerpo de Respuesta (200 OK)**:

```

{
    "message": "El producto 'Nombre del Producto' ha sido eliminado exitosamente.",
    "data": {
        "product_id": 1
    },
    "status": 200
}

```

-   **Producto No Encontrado (404 Not Found)**:

```

{
    "message": "El recurso solicitado no fue encontrado.",
    "code": 404
}

```

### GET /api/products/{id}/prices

-   **Descripción**: Obtiene todos los precios registrados para un producto específico en diferentes divisas.
-   **Método**: GET
-   **Headers**: Accept: application/json, Authorization: Bearer
-   **Parámetros de Ruta**: id (integer) - El ID del producto.
-   **Cuerpo de Respuesta (200 OK)**:

```

{
    "message": "Lista de precios recuperada exitosamente para el producto.",
    "data": [
        {
            "id": 1,
            "product_id": 1,
            "currency_id": 2,
            "price": "1104.00",
            "created_at": "...",
            "updated_at": "...",
            "currency": { /* Detalles de la divisa del precio */ }
        }
        // ... más precios para este producto
    ],
    "status": 200
}

```

-   **Producto No Encontrado (404 Not Found)**:

```

{
    "message": "El recurso solicitado no fue encontrado.",
    "code": 404
}

```

### POST /api/products/{id}/prices

-   **Descripción**: Crea un nuevo precio para un producto en una divisa específica.
-   **Método**: POST
-   **Headers**: Content-Type: application/json, Accept: application/json, Authorization: Bearer
-   **Parámetros de Ruta**: id (integer) - El ID del producto al que se asignará el precio.
-   **Body (JSON)**:

```

{
    "currency_id": 2, // ID de la divisa (ej. 2 para EUR)
    "price": 320.00
}

```

-   **Cuerpo de Respuesta (201 Created)**:

```

{
    "message": "Precio del producto creado exitosamente.",
    "data": { /_ Detalles del precio creado _/ },
    "status": 201
}

```

-   **Conflicto (409 Conflict)**:

```

{
    "message": "El precio para esta divisa ya existe para este producto. Usa PUT para actualizar.",
    "data": null,
    "status": 409
}

```

-   **Errores de Validación (422 Unprocessable Entity)**:

```

{
    "message": "The given data was invalid.",
    "errors": { /_ Detalles de los errores de validación _/ }
}

```

## Estructura de las Respuestas JSON

Todas las respuestas de la API, tanto exitosas como con errores manejados (ej. validación, conflicto), siguen una estructura consistente para facilitar el procesamiento por parte de los clientes:

```

{
    "message": "Descripción legible del resultado de la operación.",
    "data": { /_ Objeto o array de datos relevantes para la respuesta. Puede ser null en algunos casos (ej. conflicto). _/ },
    "status": 200 /_ Código de estado HTTP numérico de la respuesta. _/
}

```

-   **message**: Un mensaje amigable para el usuario que describe el resultado de la operación.
-   **data**: Contiene los datos del recurso solicitado o modificado. Será null si la operación no produce datos relevantes (ej. un error de conflicto sin nuevo recurso).
-   **status**: El código de estado HTTP estándar asociado a la respuesta. Este valor se corresponde con el código de estado HTTP en el encabezado de la respuesta.

## Uso de Postman para Pruebas

Se proporciona un archivo de colección de Postman (Product API.postman_collection.json) para facilitar la prueba de todos los endpoints de la API. Se guardó en la raíz del proyecto.

### Importar la Colección

1. Abre Postman.
2. Haz clic en "Import" en la esquina superior izquierda.
3. Selecciona "Choose Files" y navega hasta el archivo Product API.postman_collection.json
4. Haz clic en "Import". Esto añadirá la colección "Product API" a tu espacio de trabajo.

### Configuración Global de Autenticación en Postman

Para facilitar la autenticación en todas las solicitudes de la colección, se va a establecer el método de autorización **Bearer Token** de forma global. Esto significa que el token se aplicará automáticamente a cada solicitud en la colección, y podrás anularlo en solicitudes individuales si es necesario.

#### Pasos para Establecer la Autenticación Global en Postman:

1. **Seleccionar la Colección**:

    - En Postman, localiza la colección que deseas configurar en el panel de la izquierda.

2. **Configurar la Autenticación**:

    - Ve a la pestaña **Authorization**.
    - En el campo **Type**, selecciona **Bearer Token**.

3. **Ingresar el Token**:

    - En el campo **Token**, ingresa el token que obtuviste del proceso de inicio de sesión.

4. **Guardar los Cambios**:
    - Haz clic en **Save o Guardar** para guardar los cambios en la colección.

#### Ejemplo de Configuración:

-   **Auth Type**: Bearer Token
-   **Token**: `xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx`

Con esta configuración, el método de autorización se aplicará a todas las solicitudes dentro de la colección. Si en alguna solicitud específica deseas usar un token diferente o no usar autenticación, puedes anular esta configuración en la pestaña de **Authorization** de esa solicitud.

### Nota

Recuerda que el token debe ser actualizado cada vez que inicies sesión nuevamente, ya que puede expirar. Asegúrate de mantener el token actualizado en la configuración de la colección para evitar problemas de autenticación.

## Notas de Seguridad

La seguridad es un aspecto crítico en el desarrollo de APIs. A continuación se presentan algunas consideraciones importantes para asegurar la API de gestión de productos:

### 1. Autenticación con Laravel Sanctum

-   **Tokens de API**: La seguridad de esta API se fundamenta en los tokens de API generados por Laravel Sanctum. Estos tokens son la credencial principal para acceder a los recursos protegidos.

### 2. Uso de HTTPS

-   **Cifrado de Datos**: En un entorno de producción, es crucial servir la API a través de HTTPS para asegurar que todas las comunicaciones, incluyendo el envío de tokens, estén encriptadas.

### 3. Validación de Entrada

-   **Prevención de Ataques**: La API implementa validación de datos de entrada para prevenir ataques comunes como inyección SQL y XSS (Cross-Site Scripting).

### 4. Manejo de Errores

-   **Códigos de Estado HTTP**: La API devuelve códigos de estado HTTP apropiados y mensajes descriptivos en formato JSON para facilitar el manejo de errores en el cliente.
