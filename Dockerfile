FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    nodejs \
    npm \
    default-mysql-client \
    netcat-openbsd \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer files first for better caching
COPY composer.json composer.lock ./

# Install PHP dependencies (without dev)
RUN composer install --no-dev --no-interaction --no-progress --optimize-autoloader --no-scripts

# Copy package files
COPY package.json package-lock.json ./

# Install Node dependencies
RUN npm ci

# Copy the rest of the application
COPY . .

# Run composer scripts after copying app files
RUN composer dump-autoload --optimize

# Build frontend assets
RUN npm run build

# Create storage link
RUN php artisan storage:link || true

# Set permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Create startup script with database wait
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
echo "Waiting for database connection..."\n\
\n\
# Extract host and port from DATABASE_URL or use DB_HOST/DB_PORT\n\
if [ -n "$DATABASE_URL" ]; then\n\
    DB_HOST=$(echo $DATABASE_URL | sed -e "s/mysql:\\/\\/[^@]*@\\([^:]*\\).*/\\1/")\n\
    DB_PORT=$(echo $DATABASE_URL | sed -e "s/.*:\\([0-9]*\\)\\/.*/\\1/")\n\
fi\n\
\n\
# Default port if not set\n\
DB_PORT=${DB_PORT:-3306}\n\
\n\
# Wait for database to be ready (max 60 seconds)\n\
attempt=0\n\
max_attempts=30\n\
while [ $attempt -lt $max_attempts ]; do\n\
    if nc -z "$DB_HOST" "$DB_PORT" 2>/dev/null; then\n\
        echo "Database is ready!"\n\
        break\n\
    fi\n\
    attempt=$((attempt + 1))\n\
    echo "Waiting for database... attempt $attempt/$max_attempts"\n\
    sleep 2\n\
done\n\
\n\
if [ $attempt -eq $max_attempts ]; then\n\
    echo "Database not available after $max_attempts attempts, starting anyway..."\n\
fi\n\
\n\
# Run migrations\n\
php artisan migrate --force || true\n\
\n\
# Cache config\n\
php artisan config:cache || true\n\
php artisan route:cache || true\n\
php artisan view:cache || true\n\
\n\
# Start server\n\
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}\n\
' > /app/start.sh && chmod +x /app/start.sh

# Expose port
EXPOSE ${PORT:-8000}

# Start command
CMD ["/app/start.sh"]
