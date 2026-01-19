# Storage Link Command for Live Server

## Introduction

The `php artisan storage:link` command creates a symbolic link from `public/storage` to `storage/app/public` in your Laravel application. This is essential for serving files stored in the `storage/app/public` directory publicly through the web server.

**Why it's needed:**
- Laravel stores uploaded files in `storage/app/public` by default for security
- Files in the `storage` directory are not directly accessible via web URLs
- The symbolic link makes these files accessible at `/storage/filename.ext`
- This is required for deposit payment proof uploads and other file storage features

**⚠️ Important:** For production deployments, it's **highly recommended** to automate this command rather than running it manually. See the [Automatic Execution](#automatic-execution-on-live-server-recommended) section below for setup instructions.

## Prerequisites

Before running the command, ensure you have:
- SSH access to your live/production server
- Access to the project root directory
- Appropriate permissions to create symbolic links
- PHP and Artisan CLI available on the server

## Automatic Execution on Live Server (Recommended)

To ensure the `storage:link` command runs automatically during deployment, use one of the following methods:

### Method A: Using Composer Scripts (Recommended for Most Deployments)

Add the `storage:link` command to your `composer.json` scripts section. This will run automatically after composer install/update:

**Update `composer.json`:**
```json
{
    "scripts": {
        "post-autoload-dump": [
            "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
            "@php artisan package:discover --ansi"
        ],
        "post-update-cmd": [
            "@php artisan vendor:publish --tag=laravel-assets --ansi --force",
            "@php artisan storage:link || true"
        ],
        "post-install-cmd": [
            "@php artisan storage:link || true"
        ]
    }
}
```

**Note:** The `|| true` ensures the script continues even if the link already exists.

**When it runs:**
- `post-install-cmd`: Runs after `composer install`
- `post-update-cmd`: Runs after `composer update`

### Method B: Using Docker Entrypoint Script

If you're using Docker (as indicated by your Dockerfile), add the command to `docker/entrypoint.sh`:

**Update `docker/entrypoint.sh`:**
```bash
#!/bin/sh
set -e

echo "Running Laravel setup..."

# Create storage link
php artisan storage:link || true

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
```

### Method C: Using Deployment Script

Create a deployment script (e.g., `deploy.sh`) that runs during your deployment process:

**Create `deploy.sh`:**
```bash
#!/bin/bash
set -e

echo "Starting deployment..."

# Pull latest code
git pull origin main

# Install dependencies
composer install --no-dev --optimize-autoloader

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link || true

# Clear and cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

echo "Deployment completed!"
```

**Make it executable:**
```bash
chmod +x deploy.sh
```

### Method D: Using CI/CD Pipelines

#### GitHub Actions Example

Create `.github/workflows/deploy.yml`:
```yaml
name: Deploy to Production

on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.1'
      
      - name: Install dependencies
        run: composer install --no-dev --optimize-autoloader
      
      - name: Run storage:link
        run: php artisan storage:link || true
      
      - name: Deploy to server
        uses: appleboy/ssh-action@master
        with:
          host: ${{ secrets.HOST }}
          username: ${{ secrets.USERNAME }}
          key: ${{ secrets.SSH_KEY }}
          script: |
            cd /path/to/your/project
            git pull
            composer install --no-dev --optimize-autoloader
            php artisan migrate --force
            php artisan storage:link || true
            php artisan config:cache
            php artisan route:cache
            php artisan view:cache
```

#### GitLab CI Example

Create `.gitlab-ci.yml`:
```yaml
deploy:
  stage: deploy
  script:
    - composer install --no-dev --optimize-autoloader
    - php artisan migrate --force
    - php artisan storage:link || true
    - php artisan config:cache
    - php artisan route:cache
    - php artisan view:cache
  only:
    - main
```

### Method E: Using Server Deployment Hooks

#### For cPanel/Shared Hosting

Create a `.cpanel_deploy` script in your project root:
```bash
#!/bin/bash
php artisan storage:link || true
php artisan config:cache
php artisan route:cache
```

#### For VPS/Cloud Servers

Add to your server's deployment hook (e.g., in `/etc/hooks/deploy.sh`):
```bash
#!/bin/bash
cd /var/www/html/investment
php artisan storage:link || true
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Method F: Using Laravel's Built-in Deployment Commands

Create a custom Artisan command or add to your deployment process:

```bash
# In your deployment script
php artisan storage:link || true
php artisan optimize
```

## Step-by-Step Instructions (Manual Method)

### Method 1: Using SSH (Recommended)

1. **Connect to your live server via SSH:**
   ```bash
   ssh username@your-server-ip
   # or
   ssh username@your-domain.com
   ```

2. **Navigate to your project directory:**
   ```bash
   cd /path/to/your/laravel/project
   # Example: cd /var/www/html/investment
   # or: cd /home/username/public_html
   ```

3. **Check if the storage link already exists:**
   ```bash
   ls -la public/storage
   ```
   
   If you see output showing a symbolic link, you may need to remove it first (see Troubleshooting section).

4. **Remove existing link or directory (if needed):**
   ```bash
   # Remove existing symbolic link
   rm public/storage
   
   # OR if it's a directory (not a link), remove it:
   rm -rf public/storage
   ```

5. **Run the storage:link command:**
   ```bash
   php artisan storage:link
   ```

6. **Verify the link was created successfully:**
   ```bash
   ls -la public/storage
   ```
   
   You should see output like:
   ```
   lrwxrwxrwx 1 www-data www-data 46 Jan 15 10:00 public/storage -> /var/www/html/investment/storage/app/public
   ```
   
   The `l` at the beginning indicates it's a symbolic link, and the arrow `->` shows where it points.

7. **Set proper permissions (if needed):**
   ```bash
   # Ensure the storage/app/public directory exists and has proper permissions
   mkdir -p storage/app/public
   chmod -R 755 storage/app/public
   
   # Set ownership to web server user (adjust based on your server)
   # For Apache:
   sudo chown -R www-data:www-data storage/app/public
   
   # For Nginx:
   sudo chown -R nginx:nginx storage/app/public
   
   # Or find your web server user:
   ps aux | grep -E 'nginx|apache|httpd' | grep -v grep
   ```

## Verification Steps

After running the command, verify it worked correctly:

1. **Check the symbolic link:**
   ```bash
   ls -la public/ | grep storage
   ```
   Should show: `lrwxrwxrwx ... storage -> ../storage/app/public`

2. **Test file accessibility:**
   - Create a test file in `storage/app/public/test.txt`
   - Access it via browser: `https://yourdomain.com/storage/test.txt`
   - If you can see the file, the link is working correctly

3. **Check Laravel logs (if issues persist):**
   ```bash
   tail -f storage/logs/laravel.log
   ```

## Troubleshooting

### Issue: "The "public/storage" link already exists"

**Solution:**
```bash
# Remove the existing link
rm public/storage

# Then run the command again
php artisan storage:link
```

### Issue: "Permission denied" error

**Solution:**
```bash
# Check current permissions
ls -la public/

# Give proper permissions to public directory
chmod 755 public

# Try running with sudo (if you have sudo access)
sudo php artisan storage:link

# Or change ownership
sudo chown -R www-data:www-data public storage
```

### Issue: Symbolic link created but files not accessible

**Solution:**
1. Verify the link points to the correct location:
   ```bash
   readlink -f public/storage
   ```
   Should point to: `/path/to/project/storage/app/public`

2. Ensure `storage/app/public` directory exists:
   ```bash
   mkdir -p storage/app/public
   chmod -R 755 storage/app/public
   ```

3. Check web server configuration allows following symbolic links

4. Verify file permissions in `storage/app/public`:
   ```bash
   ls -la storage/app/public
   ```

### Issue: "No such file or directory" error

**Solution:**
```bash
# Ensure storage/app/public directory exists
mkdir -p storage/app/public

# Create a .gitkeep file to ensure the directory is tracked
touch storage/app/public/.gitkeep

# Then run storage:link again
php artisan storage:link
```

## Alternative Methods

### Method 2: Using Hosting Control Panel

If you don't have SSH access, some hosting control panels allow you to:

1. **Via cPanel File Manager:**
   - Navigate to `public` directory
   - Delete existing `storage` folder/link if present
   - Use "Create Symbolic Link" option (if available)
   - Point it to `../storage/app/public`

2. **Via Hosting Terminal/Console:**
   - Most modern hosting providers offer a web-based terminal
   - Access it through your hosting control panel
   - Follow the SSH instructions above

### Method 3: Manual Symbolic Link Creation

If Artisan is not available, you can create the link manually:

```bash
# Navigate to public directory
cd public

# Create symbolic link manually
ln -s ../storage/app/public storage

# Verify
ls -la storage
```

## Post-Installation Checklist

After successfully creating the storage link:

- [ ] Symbolic link exists at `public/storage`
- [ ] Link points to `storage/app/public`
- [ ] Permissions are set correctly (755 for directories)
- [ ] Web server user owns the storage directories
- [ ] Test file upload works (try uploading a deposit payment proof)
- [ ] Files are accessible via `/storage/filename.ext` URL

## Additional Notes

- **Automation is recommended:** Set up automatic execution using one of the methods in the [Automatic Execution](#automatic-execution-on-live-server-recommended) section above
- The storage link is typically created during initial deployment
- If you're using version control, the `public/storage` link should be in `.gitignore`
- After deployment, always verify the storage link exists on the live server
- The `|| true` flag in automation scripts ensures deployment continues even if the link already exists
- For Docker deployments, the entrypoint script is the best place to add this command
- For traditional server deployments, composer scripts or deployment hooks are recommended

## Related Commands

```bash
# Clear all caches (may help if files still not accessible)
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize application
php artisan optimize

# Check storage disk configuration
php artisan tinker
>>> Storage::disk('public')->put('test.txt', 'test');
>>> Storage::disk('public')->url('test.txt');
```

## Support

If you continue to experience issues after following this guide:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Check web server error logs
3. Verify PHP configuration allows symbolic links
4. Contact your hosting provider if symbolic links are disabled

