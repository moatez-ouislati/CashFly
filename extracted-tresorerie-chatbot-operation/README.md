# Tresorerie / Chatbot / Operation extraction

This folder is a focused copy of the CashFly files related to treasury
management, the financial chatbot, and financial operations.

## Controllers

- `src/Controller/ChatbotController.php`
- `src/Controller/OperationController.php`
- `src/Controller/TresorerieController.php`
- `src/Controller/AdminController.php` - included because it contains admin
  treasury and operation export/listing actions.

## Services

- `src/Service/ChatbotService.php`
- `src/Service/TresorerieAutomationService.php`
- `src/Service/CurrencyConverterService.php`

## Entities / models

- `src/Entity/Tresorerie.php`
- `src/Entity/Operation.php`
- `src/Entity/OperationNote.php`
- `src/Entity/Entreprise.php` - dependency for treasury accounts.
- `src/Entity/User.php` - dependency through company ownership.

## Forms

- `src/Form/TresorerieType.php`
- `src/Form/OperationType.php`
- `src/Form/TransfertType.php`

## Repositories

- `src/Repository/TresorerieRepository.php`
- `src/Repository/OperationRepository.php`
- `src/Repository/EntrepriseRepository.php`
- `src/Repository/UserRepository.php`

## Templates

- `templates/base.html.twig`
- `templates/chatbot/index.html.twig`
- `templates/operation/*.html.twig`
- `templates/tresorerie/*.html.twig`
- `templates/admin/tresoreries.html.twig`

## Notes

- The copied files preserve the original relative paths.
- `ChatbotService.php` currently contains a hard-coded API key from the source
  project. Move that value to environment configuration before reusing it.
- This extraction does not include unrelated vendor files, public assets, or
  the full application configuration.
