# B2B Marketplace – Rankings Service

This repository contains the **Rankings Service** for the B2B Marketplace.  
It powers the **Agency Rankings** feature that allows brands to view and compare agencies based on expertise, reviews, awards, and performance indicators.

Built with **Laravel 11**, this service follows **DDD + Hexagonal Architecture** to keep the domain pure, infrastructure swappable, and adapters testable.

---

## ✨ Features

- **Agency Rankings**  
  Weighted scoring model using reviews, awards, response time, case studies, and budget fit.

- **Domain-Driven Design**  
  Clear separation of **Domain**, **Application**, **Infrastructure**, and **Adapters**.

- **Extensible**  
  Shared libraries live under `src/B2BMarketplace`, PSR-4 autoloaded, and reusable across services.

- **Contracts First**  
  - Public HTTP API documented in `openapi/rankings.yaml`  
  - Event contracts (JSON Schema) in `contracts/events/`

- **Production Ready**  
  - Dockerized PHP + Nginx + Horizon  
  - Configurable observability (OTEL, FluentBit)  
  - Makefile targets for local dev & CI  

---

## 📂 Project Structure

```

.
├── app/
│   ├── Domain/           # Entities, DTOs, Contracts, Policies
│   ├── Application/      # Use cases & orchestrators
│   ├── Infrastructure/   # DB, cache, messaging, external APIs
│   ├── Http/             # Controllers, Requests, Resources
│   ├── Console/          # Artisan commands
│   ├── Jobs/             # Queue jobs & message consumers
│   └── Providers/        # Service providers (DI bindings)
├── src/B2BMarketplace/   # Shared libraries (PSR-4)
├── config/               # Service config (rankings, telemetry, clients…)
├── database/             # Migrations, seeders, factories
├── routes/               # API & console route definitions
├── tests/                # Unit, Feature, Integration, Contract tests
├── openapi/              # API spec (OpenAPI YAML)
├── contracts/            # Async schemas (events)
├── docker/               # Dockerfiles & configs
├── deploy/               # Deployment manifests (K8s/ECS)
├── ops/                  # Observability configs
└── Makefile              # Common dev commands

````

---

## 🚀 Getting Started

### Prerequisites
- PHP 8.2+
- Composer 2.x
- Docker + Docker Compose
- Node.js (optional, for frontend tooling)

### Installation

```bash
# Clone the repo
git clone git@github.com:clintonshane84/b2b-marketplace-app.git
cd b2b-marketplace-app

# Install dependencies
composer install

# Copy env
cp .env.example .env

# Generate app key
php artisan key:generate

# Run migrations + seed
php artisan migrate --seed
````

### Local Development with Docker

```bash
make up          # start PHP-FPM, Nginx, MySQL, Redis
make migrate     # run migrations inside container
make test        # run Pest tests
make down        # stop containers
```

---

## 🧩 API

* Base path: `/api/v1`
* Swagger/OpenAPI spec: [`openapi/rankings.yaml`](./openapi/rankings.yaml)

### Example: Fetch ranked agencies

```http
GET /api/v1/rankings?country=US&expertise=seo&budget_min=50&budget_max=120
```

Response (truncated):

```json
{
  "data": [
    {
      "agency": {
        "id": "uuid",
        "name": "Acme SEO",
        "country": "US",
        "city": "New York",
        "verified": true,
        "review_avg": 4.7,
        "review_count": 230
      },
      "score": 0.8421,
      "components": {
        "review_avg": 0.94,
        "review_count": 0.77,
        "verified": 1,
        "awards_recent": 0.8,
        "case_studies": 0.5,
        "response_time": 0.9,
        "budget_fit": 0.7
      }
    }
  ]
}
```

---

## 📊 Architecture Overview

* **Domain** – Pure business rules (`App\Domain`)
* **Application** – Use cases orchestrating domain services (`App\Application`)
* **Infrastructure** – Adapters to DB, cache, external APIs (`App\Infrastructure`)
* **Adapters (Inbound)** – Controllers, Commands, Jobs under Laravel defaults
* **Shared Libs** – `src/B2BMarketplace/Shared` (DTOs, ValueObjects, Contracts)

---

## 🧪 Testing

* **Unit Tests** – Fast, domain-only
* **Feature Tests** – Controllers, requests, API responses
* **Integration Tests** – Real DB/cache/queue (Dockerized)
* **Contract Tests** – Against OpenAPI and event schemas

Run all tests:

```bash
composer test
```

---

## ⚙️ Deployment

* **Kubernetes** manifests under `deploy/k8s/`
* **ECS** task definitions under `deploy/ecs/`
* **Docker** images defined in `docker/`

CI/CD pipelines should:

1. Run `make test`
2. Build & push Docker image
3. Apply infra manifests

---

## 📚 Libraries & Standards

* [Laravel](https://laravel.com/) 11.x
* [Pest](https://pestphp.com/) for testing
* [Larastan](https://github.com/nunomaduro/larastan) for static analysis
* [Laravel Pint](https://laravel.com/docs/pint) for code style
* [OpenAPI](https://swagger.io/specification/) for API contracts

---

## 🧑‍💻 Contributing

1. Fork & clone
2. Create a feature branch (`git checkout -b feature/my-feature`)
3. Run `composer fix && composer test`
4. Open a Pull Request

---

## 📄 License

Proprietary © Clinton Shane Wright 2025.
Unauthorized copying, modification, or distribution is prohibited.
