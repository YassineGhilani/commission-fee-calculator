# Traditional Task + Refactoring Task
[#Go To Tradidtional Task Documentation](#currency-transaction-fee-calculator)
\
[#Go To Refactoring Task](#exchange-rate--commission-calculator)

# Currency Transaction Fee Calculator

## Overview
This project is a PHP-based service that calculates transaction fees for deposits and withdrawals based on user type and transaction amount. The service supports multiple currencies and includes a PHPUnit test suite to ensure correctness.

## Features
- Respects PSR-12.
- Supports **private** and **business** users.
- Calculates fees based on predefined rules.
- Handles **currency conversion**.
- Includes **unit tests** with PHPUnit.

## Installation
This part is for the two projects (Traditional and Refactoring)
### Requirements
- PHP 8.3
- Composer
- PHPUnit

### Setup
1. Clone the repository:
   ```bash
   git clone https://github.com/YassineGhilani/commission-fee-calculator
   cd commission-fee-calculator
   ```
2. Install dependencies:
   ```bash
   composer install
   ```

## Usage
### Running the Calculator
To run the Calculator please run this script: "php .\script.php .\input.csv"

## Running Tests
To run the PHPUnit test suite, execute:
```bash
vendor/bin/phpunit tests/
```

## Troubleshooting
### Error: "Currency not supported: EUR"
If you encounter this issue while running PHPUnit tests but not when executing the script normally, ensure that:
1. The `CurrencyConverter` class properly loads supported currencies.
2. The test environment is correctly set up.
3. There is no static data caching between test runs.

## Contributing
Feel free to fork the repository and submit pull requests.


# Exchange Rate & Commission Calculator

## Overview

This PHP project calculates the commission for transactions based on exchange rates and whether the payment source is from the European Union (EU). It retrieves exchange rates from an external API, determines if a BIN number belongs to an EU country, and calculates the commission based on the determined region.

## Features

 - Fetches real-time exchange rates using an API

 - Determines if a BIN number belongs to an EU country

 - Calculates commissions based on region:

      1% for EU transactions

      2% for non-EU transactions

 - Rounds commissions up to the nearest cent

 - Extensible and modular design

 - Unit tests for reliability

## Technologies Used

 - PHP 8+

 - Guzzle (HTTP client for API requests)

 - PHPUnit (for unit testing)

 - Composer (dependency management)

## Installation

### Clone the repository:
   ```
   git clone https://github.com/YassineGhilani/commission-fee-calculator
   cd commission-fee-calculator
   ```

### Install dependencies:

 - run the command: composer install

## Usage

 - Run the script with a JSON Like text file containing transaction details:

   ```
   cd src\RefactorExchangeRate
   php MainRefactor.php  ..\..\input.txt
   ```

 - Example Input (input.txt)
   ```
   [
      {"bin": "45717360", "amount": 100.00, "currency": "USD"},
      {"bin": "516793", "amount": 50.00, "currency": "EUR"}
   ]
   ```
 - Example Output
   ```
   Commission: 1.85 | Converted Amount: 92.50 | Rate: 1.08
   Commission: 0.50 | Converted Amount: 50.00 | Rate: 1.00
   ```
## Code Structure

```
   src/RefactorExchangeRate/ExchangeRateService.php: Fetches exchange rates

   src/RefactorExchangeRate/BinCheckerService.php: Determines if a BIN is from the EU

   src/RefactorExchangeRate/CommissionCalculator.php: Computes transaction commission

   src/RefactorExchangeRate/MainRefactor.php: Orchestrates the logic and processes transactions

   tests/RefactorExchangeRate/: Contains unit tests
```

## Configuration

 - Update API keys in ExchangeRateService.php and BinCheckerService.php:

## Contributing

 - Pull requests are welcome. Please include tests for any new features or fixes.
