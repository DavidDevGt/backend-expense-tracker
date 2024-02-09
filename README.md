# Expense Tracker API Documentation

La API de Expense Tracker permite a los usuarios administrar sus transacciones financieras de manera eficiente y segura.

## Version: 1.0.0

**Base URL:** `/backend-expense-tracker`

### Endpoints

#### Login de usuario
- **URL:** `/users/login`
- **Method:** `POST`
- **Auth required:** No
- **Data constraints:**

```json
{
    "username": "[valid username]",
    "password": "[password in plain text]"
}
```

- **Data example:**

```json
{
    "username": "testuser",
    "password": "mypassword"
}
```

#### Logout de usuario
- **URL:** `/users/logout`
- **Method:** `POST`
- **Auth required:** Sí (Token JWT)

#### Obtener transacciones
- **URL:** `/transactions`
- **Method:** `GET`
- **Auth required:** Sí (Token JWT)

#### Crear transacción
- **URL:** `/transactions`
- **Method:** `POST`
- **Auth required:** Sí (Token JWT)
- **Data constraints:**

```json
{
    "text": "[description of the transaction]",
    "amount": "[transaction amount, positive or negative]"
}
```

- **Data example:**

```json
{
    "text": "Venta de libro",
    "amount": 19.99
}
```

#### Actualizar transacción
- **URL:** `/transactions/[:id]`
- **Method:** `PUT`
- **Auth required:** Sí (Token JWT)
- **Data constraints:**

```json
{
    "text": "[new description of the transaction]",
    "amount": "[new transaction amount, positive or negative]"
}
```

- **Data example:**

```json
{
    "text": "Venta de videojuego",
    "amount": 29.99
}
```

#### Eliminar transacción
- **URL:** `/transactions/[:id]`
- **Method:** `DELETE`
- **Auth required:** Sí (Token JWT)

### Respuestas

Cada endpoint retorna una respuesta en formato JSON. Los códigos de estado HTTP estándar se utilizan para indicar el éxito o fracaso de una solicitud.

#### Ejemplo de respuesta exitosa

```json
HTTP/1.1 200 OK
Content-Type: application/json

{
    "message": "Transacción creada con éxito.",
    "data": {
        "id": "123",
        "text": "Venta de libro",
        "amount": 19.99
    }
}
```

#### Ejemplo de respuesta de error

```json
HTTP/1.1 400 Bad Request
Content-Type: application/json

{
    "message": "Datos de entrada inválidos."
}
```

### Modelos

#### Usuario

```json
{
    "id": "uuid",
    "username": "string",
    "email": "string",
    "created_at": "datetime",
    "updated_at": "datetime"
}
```

#### Transacción

```json
{
    "id": "uuid",
    "text": "string",
    "amount": "number",
    "user_id": "uuid",
    "created_at": "datetime",
    "updated_at": "datetime"
}
```

### Seguridad

La API utiliza tokens JWT para la autenticación. El token debe ser incluido en la cabecera `Authorization` de todas las solicitudes que lo requieran.

