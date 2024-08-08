Описание файлов и директорий:
src/: Каталог с исходным кодом приложения.

Reels.php: Класс для управления катушками.
Game.php: Основной класс игры.
PayoutTable.php: Класс для управления таблицей выплат.
tests/: Каталог с тестами.

ReelsTest.php: Тесты для класса Reels.
GameTest.php: Тесты для класса Game.
PayoutTableTest.php: Тесты для класса PayoutTable.
vendor/: Каталог для внешних зависимостей (Composer).

composer.json: Файл конфигурации Composer для управления зависимостями.

slot_machine.php: Главный исполняемый файл приложения.


composer require --dev phpunit/phpunit

vendor/bin/phpunit