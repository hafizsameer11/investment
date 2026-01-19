#!/bin/sh
# Don't use set -e here, we want to continue even if some commands fail
set +e

echo "Running Laravel setup..."

# Create storage link
php artisan storage:link || true

# Ensure upload directories exist with proper permissions
# Create parent directories first (important for Dokploy volume mounts)
echo "Creating upload directories..."
mkdir -p /var/www/html/public/assets/deposits/payment-proofs || true
mkdir -p /var/www/html/public/assets/withdrawals || true

# Set ownership recursively (important for Dokploy/volume mounts)
echo "Setting directory ownership..."
# Try to set ownership to www-data first (standard web server user)
if command -v chown >/dev/null 2>&1; then
    # Check if www-data user exists
    if id www-data >/dev/null 2>&1; then
        chown -R www-data:www-data /var/www/html/public/assets 2>/dev/null || true
    else
        # If www-data doesn't exist, use current user
        CURRENT_UID=$(id -u)
        CURRENT_GID=$(id -g)
        chown -R ${CURRENT_UID}:${CURRENT_GID} /var/www/html/public/assets 2>/dev/null || true
    fi
fi

# Set permissions recursively
echo "Setting directory permissions..."
chmod -R 775 /var/www/html/public/assets 2>/dev/null || \
chmod -R 777 /var/www/html/public/assets 2>/dev/null || true

# Specifically ensure deposit directory is writable
chmod 775 /var/www/html/public/assets/deposits/payment-proofs 2>/dev/null || \
chmod 777 /var/www/html/public/assets/deposits/payment-proofs 2>/dev/null || true

chmod 775 /var/www/html/public/assets/withdrawals 2>/dev/null || \
chmod 777 /var/www/html/public/assets/withdrawals 2>/dev/null || true

# Verify permissions and writability (for debugging)
echo "Verifying upload directory permissions..."
if [ -d "/var/www/html/public/assets/deposits/payment-proofs" ]; then
    ls -la /var/www/html/public/assets/deposits/ 2>/dev/null || true
    if [ -w "/var/www/html/public/assets/deposits/payment-proofs" ]; then
        echo "✓ Deposit directory is writable"
    else
        echo "⚠ Warning: Deposit directory may not be writable - trying to fix..."
        chmod 777 /var/www/html/public/assets/deposits/payment-proofs 2>/dev/null || true
    fi
else
    echo "⚠ Warning: Deposit directory does not exist"
fi

# Re-enable exit on error for the rest of the script
set -e

# Clear and cache config
php artisan config:clear || true
php artisan cache:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Cache configuration for better performance
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "Laravel setup completed!"

# Execute the main command
exec "$@"

