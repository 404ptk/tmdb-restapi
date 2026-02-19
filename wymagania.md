Cel: Stworzenie aplikacji Laravel, która pobiera dane z API TMDB, zapisuje je w bazie danych i udostępnia własne REST API z obsługą wielu języków.

Szacowany czas realizacji: 2-4 godzin
Wymagania
  Modele
    Movie
    Serie
    Genre

Funkcjonalności
  Scrapowanie danych
    Pobierz podstawowe informacje o filmach, serialach i gatunkach z API TMDB: Getting Started 
    Zapisz pobrane dane do bazy danych:
      50 rekordów movie
      10 rekordów serie
      wszystkie gatunki
  Pobieranie danych musi działać przez Queue (Job)

  Multi-language
    Zaimplementuj obsługę wielu języków (PL, EN, DE) dla wszystkich trzech modeli
    API powinno zwracać dane w języku określonym przez nagłówek Accept-Language

  REST API
    Stwórz endpointy do pobierania danych o filmach, serialach i gatunkach
    Wszystkie endpointy listujące powinny obsługiwać paginację
    W pliku README.md opisz endpointy i sposób uruchomienia projektu

Wymagania techniczne
  Laravel w najnowszej stabilnej wersji
  Zastosuj dobre praktyki programowania (SOLID)
  Komenda Artisan do uruchomienia pobierania danych z TMDB (dispatch Job)

#narazie tego nie robimy
Punkty bonusowe (opcjonalne)
  Uruchomienie projektu za pomocą Docker (docker-compose.yml)
  Testy Feature
  Livewire component wyświetlający listę filmów z paginacją