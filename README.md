# Currency Converter

A module for storing and converting currencies.

## Features

- **Predefined Currencies**: The module has a hardcoded list of predefined currencies.
- **Exchange Rates Update**:
  - Exchange rates are downloaded from [freecurrencyapi.com](https://freecurrencyapi.com/).
  - Rates are updated once a day.
- **Conversion Service**:
  - Provides a service for converting prices from one currency to another.
  - Usage example:
    ```php
    $convertedAmount = $converter->convert(123, 'USD', 'RUB');
    ```
- **Admin Panel**:
  - A page in the admin panel displays all saved exchange rates.
- **Integration**:
  - Implemented using [Guzzle](https://github.com/guzzle/guzzle) for HTTP requests.

## Installation

[TBU]

## Usage

[TBU]

## Integration Details

- **API Source**: Exchange rates are fetched from [freecurrencyapi.com](https://freecurrencyapi.com/).
- **HTTP Client**: Uses Guzzle for making HTTP requests to the API.

---