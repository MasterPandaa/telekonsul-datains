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

# Create startup script with robust PHP-based database wait
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
echo "Creating wait-for-db script..."\n\
cat << "EOF" > /tmp/wait_for_db.php\n\
<?php\n\
$url = getenv("DATABASE_URL");\n\
$host = "127.0.0.1";\n\
$port = 3306;\n\
$user = "root";\n\
$pass = "";\n\
$db   = "railway";\n\
\n\
if ($url) {\n\
    $components = parse_url($url);\n\
    $host = $components["host"] ?? $host;\n\
    $port = $components["port"] ?? $port;\n\
    $user = $components["user"] ?? $user;\n\
    $pass = $components["pass"] ?? $pass;\n\
    $db   = ltrim($components["path"] ?? "", "/") ?: $db;\n\
} else {\n\
    $host = getenv("DB_HOST") ?: $host;\n\
    $port = getenv("DB_PORT") ?: $port;\n\
    $user = getenv("DB_USERNAME") ?: $user;\n\
    $pass = getenv("DB_PASSWORD") ?: $pass;\n\
    $db   = getenv("DB_DATABASE") ?: $db;\n\
}\n\
\n\
fwrite(STDERR, "Checking connection to $host:$port for DB: $db ...\n");\n\
\n\
$maxTries = 30;\n\
for ($i = 1; $i <= $maxTries; $i++) {\n\
    try {\n\
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);\n\
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);\n\
        fwrite(STDERR, "Database connection successful!\n");\n\
        exit(0);\n\
    } catch (PDOException $e) {\n\
        fwrite(STDERR, "Attempt $i/$maxTries: Connection failed. " . $e->getMessage() . "\n");\n\
        sleep(2);\n\
    }\n\
}\n\
fwrite(STDERR, "Could not connect to database after $maxTries attempts.\n");\n\
exit(1);\n\
?>\n\
EOF\n\
\n\
echo "Running wait-for-db check..."\n\
php /tmp/wait_for_db.php\n\
\n\
echo "Running migrations..."\n\
php artisan migrate --force\n\
\n\
echo "Running seeds..."\n\
php artisan db:seed --force || echo "Seeding failed or skipped"\n\
\n\
echo "Caching config..."\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
\n\
echo "Starting server..."\n\
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}\n\
' > /app/start.sh && chmod +x /app/start.sh

# Expose port
EXPOSE ${PORT:-8000}

# Start command
CMD ["/app/start.sh"]
