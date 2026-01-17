FROM php:8.2-fpm

# Prevent interactive prompts during package installation
ENV DEBIAN_FRONTEND=noninteractive

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Fix git ownership issue
RUN git config --global --add safe.directory /var/www

# Change ownership to non-root user
RUN chown -R www-data:www-data /var/www

# Switch to non-root user
USER www-data

# Install Laravel dependencies first
RUN composer install --no-dev --optimize-autoloader

# Install frontend dependencies
RUN npm ci

# Build frontend assets
RUN npm run build

# Ensure storage and bootstrap/cache directories are writable
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Remove node_modules to save space (optional, since they're not needed at runtime)
RUN npm prune --production

# Switch back to root user to run PHP-FPM
USER root

# Copy the startup script
COPY docker-startup.sh /usr/local/bin/docker-startup.sh
RUN chmod +x /usr/local/bin/docker-startup.sh

# Expose port
EXPOSE 9000

# Start with our startup script
CMD ["/usr/local/bin/docker-startup.sh"]