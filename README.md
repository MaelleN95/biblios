# Biblios - Symfony 7.2 Dockerized Project

This repository contains a Symfony 7.2 project named **biblios-app**, fully containerized using Docker with PHP 8.2, MariaDB, and Nginx.  
It is designed as a learning project to help learn Docker, Symfony, and related technologies.

## Objectives

- Learn and practice Docker containerization for PHP/Symfony applications.
- Set up a Symfony 7.2 development environment with PHP 8.2, MariaDB, and Nginx.
- Understand how to orchestrate multiple containers with Docker Compose.
- Learn how to build a website with Symfony.
- Use phpMyAdmin for database management.
- Version control the whole setup with GitHub.

## Project Structure

```php
biblios/
├── biblios-app/ # Symfony application
├── docker/ # Docker configurations (PHP, Nginx, MariaDB)
├── docker-compose.yml # Docker Compose orchestration file
├── .gitignore # Git ignore rules
└── README.md
```

## Usage

1. Clone the repo and navigate to the project root :

   ```bash
   git clone https://github.com/MaelleN95/biblios.git
   cd biblios
   ```

2. Build and start the Docker containers :

   ```bash
   docker compose up -d --build
   ```

3. (Optional) Access the PHP container to run Symfony CLI or Composer commands :
   
   ```bash
   docker exec -it biblios-php bash
   ```

5. Access the Symfony app at `http://localhost:8080`

6. Access phpMyAdmin at `http://localhost:8081`
   - Username: `symfony`
   - Password: `symfony`

To stop the containers :

```bash
docker compose down
```

## Notes

- Database credentials are defined in `docker-compose.yml` and Symfony `.env`.
- This repo is for learning and experimentation.
