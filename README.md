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
TTo run the Calculator please run this script: "php .\script.php .\input.csv"

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

## License
This project is licensed under the MIT License.

