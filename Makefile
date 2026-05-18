.PHONY: help install env composer-install key-generate migrate seed npm-install npm-build fresh dev test lint shield-generate storage-link clean-media

help: ## Tampilkan daftar perintah yang tersedia
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  \033[36m%-20s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

install-reset: env composer-install key-generate fresh storage-link npm-install npm-build shield-generate ## Instalasi awal proyek (env, composer, key, migrate, seed, npm build, shield generate)
	@echo ""
	@echo "\033[32m✓ Instalasi selesai. Jalankan 'composer run dev' untuk memulai server pengembangan.\033[0m"

env: ## Salin .env.example ke .env jika belum ada
	@if [ ! -f .env ]; then \
		cp .env.example .env; \
		echo "\033[32m✓ .env berhasil dibuat dari .env.example\033[0m"; \
	else \
		echo "\033[33m• .env sudah ada, dilewati\033[0m"; \
	fi

composer-install: ## Install dependensi PHP
	composer install

key-generate: ## Generate application key
	php artisan key:generate

migrate: ## Jalankan migrasi database
	php artisan migrate --force

seed: ## Jalankan database seeder
	php artisan db:seed --force

fresh: ## Drop semua tabel lalu migrate ulang dan seed
	php artisan migrate:fresh --seed --force

npm-install: ## Install dependensi JavaScript
	npm install

npm-build: ## Build aset frontend untuk production
	npm run build

dev: ## Jalankan server pengembangan (server + queue + logs + vite)
	composer run dev

test: ## Jalankan test suite
	php artisan test --compact

lint: ## Jalankan Laravel Pint untuk memformat kode
	vendor/bin/pint --dirty --format agent

shield-generate: ## Generate shield for README.md
	php artisan shield:generate --panel=admin --all --option=policies_and_permissions --ignore-existing-policies

storage-link: ## Generate storage link
	php artisan storage:link

clean-media: ## Hapus folder storage/app/public/media-library beserta isinya
	@if [ -d storage/app/public/media-library ]; then \
		rm -rf storage/app/public/media-library; \
		echo "\033[32m✓ storage/app/public/media-library berhasil dihapus\033[0m"; \
	else \
		echo "\033[33m• storage/app/public/media-library tidak ditemukan, dilewati\033[0m"; \
	fi
