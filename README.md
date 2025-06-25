Aplikacja do tworzenia fiszek edukacyjnych

git clone [https://github.com/marimaki0/flashcards.git]


Uruchomienie aplikacji:
docker ps - sprawdzanie statusu kontenerow
docker-compose down - zatrzymac kontenery (jesli dzialaja)
docker-compose up -d --build - uruchamianie kontenerow z przebudowa
docker exec -it flashcards_php composer install -instalacja zaleznosci
docker exec -it flashcards_php bin/console doctrine:database:create - tworzenie bazy danych
docker exec -it flashcards_php php bin/console - uruchamianie migracji 

Wejście do kontenera PHP
docker-compose exec php bash
Wewnątrz uruchamiamy server
wewnątrz bash: php -S 0.0.0.0:8000 -t public/


Testy: 
Uruchomienie: docker exec -it flashcards_php php bin/phpunit

Pokrycie: docker exec -it flashcards_php php bin/phpunit --coverage-html coverage

Dostęp do aplikacji:
Aplikacja: http://localhost:8000
Baza danych: localhost:3307

Dostęp do MySQL:
docker exec -it flashcards_php mysql -h db -u symfony -psymfony flashcards


docker exec -it flashcards_php php bin/console cache:clear - czyszczenie cache
