    # Fix Deposit Upload Directory Permissions on Live Server

## Method 1: Using Docker (Recommended for Docker Deployments)

If you're using Docker, you have two options:

### Option A: Run Commands Inside Docker Container

1. **Find your container name:**
   ```bash
   docker ps
   ```
   Look for your Laravel application container (it might be named something like `investment-app`, `laravel-app`, etc.)

2. **Execute commands inside the container:**
   ```bash
   # Replace 'container-name' with your actual container name
   docker exec -it container-name sh
   
   # Or if using docker-compose:
   docker-compose exec app sh
   ```

3. **Once inside the container, run these commands:**
   ```bash
   # Create directories
   mkdir -p /var/www/html/public/assets/deposits/payment-proofs
   mkdir -p /var/www/html/public/assets/withdrawals
   
   # Set ownership to www-data (web server user in Docker)
   chown -R www-data:www-data /var/www/html/public/assets/deposits
   chown -R www-data:www-data /var/www/html/public/assets/withdrawals
   
   # Set permissions (775 = read/write/execute for owner and group)
   chmod -R 775 /var/www/html/public/assets/deposits
   chmod -R 775 /var/www/html/public/assets/withdrawals
   
   # Exit the container
   exit
   ```

### Option B: Run Commands from Host (Without Entering Container)

You can run commands directly without entering the container:

```bash
# Replace 'container-name' with your actual container name
docker exec container-name mkdir -p /var/www/html/public/assets/deposits/payment-proofs
docker exec container-name mkdir -p /var/www/html/public/assets/withdrawals
docker exec container-name chown -R www-data:www-data /var/www/html/public/assets/deposits
docker exec container-name chown -R www-data:www-data /var/www/html/public/assets/withdrawals
docker exec container-name chmod -R 775 /var/www/html/public/assets/deposits
docker exec container-name chmod -R 775 /var/www/html/public/assets/withdrawals

# Or if using docker-compose:
docker-compose exec app mkdir -p /var/www/html/public/assets/deposits/payment-proofs
docker-compose exec app mkdir -p /var/www/html/public/assets/withdrawals
docker-compose exec app chown -R www-data:www-data /var/www/html/public/assets/deposits
docker-compose exec app chown -R www-data:www-data /var/www/html/public/assets/withdrawals
docker-compose exec app chmod -R 775 /var/www/html/public/assets/deposits
docker-compose exec app chmod -R 775 /var/www/html/public/assets/withdrawals
```

### Option C: Use Artisan Command (If Available)

If the `SetupDepositDirectory` command is available:

```bash
# Inside container or from host
docker exec container-name php artisan setup:deposit-directory

# Or with docker-compose:
docker-compose exec app php artisan setup:deposit-directory
```

## Method 2: Traditional Server (Non-Docker)

If you're NOT using Docker, connect to your live server via SSH and run these commands:

```bash
# Navigate to your project directory
cd /path/to/your/project

# Create the directory if it doesn't exist
mkdir -p public/assets/deposits/payment-proofs
mkdir -p public/assets/withdrawals

# Set proper permissions (775 for directories)
chmod -R 775 public/assets/deposits
chmod -R 775 public/assets/withdrawals

# Set ownership to web server user (adjust based on your server)
# For Apache:
sudo chown -R www-data:www-data public/assets/deposits public/assets/withdrawals

# For Nginx:
sudo chown -R nginx:nginx public/assets/deposits public/assets/withdrawals

# Or if using a different user (check with: ps aux | grep -E 'nginx|apache|httpd'):
sudo chown -R $(ps aux | grep -E 'nginx|apache|httpd' | grep -v grep | awk '{print $1}' | head -1):$(ps aux | grep -E 'nginx|apache|httpd' | grep -v grep | awk '{print $1}' | head -1) public/assets/deposits public/assets/withdrawals
```

## Verify Permissions

After running the commands, verify with:

### For Docker:
```bash
# Check from inside container
docker exec container-name ls -la /var/www/html/public/assets/deposits/payment-proofs

# Or enter container first
docker exec -it container-name sh
ls -la /var/www/html/public/assets/deposits/payment-proofs
exit
```

### For Traditional Server:
```bash
ls -la public/assets/deposits/payment-proofs
```

**Expected Output:**
- Permissions should show `drwxrwxr-x` (775) or `drwxr-xr-x` (755)
- Owner should be `www-data` (Docker) or your web server user
- Group should match the owner

## Quick One-Liner for Docker

If you want to do everything in one command:

```bash
# Replace 'container-name' with your actual container name
docker exec container-name sh -c "mkdir -p /var/www/html/public/assets/deposits/payment-proofs /var/www/html/public/assets/withdrawals && chown -R www-data:www-data /var/www/html/public/assets/deposits /var/www/html/public/assets/withdrawals && chmod -R 775 /var/www/html/public/assets/deposits /var/www/html/public/assets/withdrawals"

# Or with docker-compose:
docker-compose exec app sh -c "mkdir -p /var/www/html/public/assets/deposits/payment-proofs /var/www/html/public/assets/withdrawals && chown -R www-data:www-data /var/www/html/public/assets/deposits /var/www/html/public/assets/withdrawals && chmod -R 775 /var/www/html/public/assets/deposits /var/www/html/public/assets/withdrawals"
```

## Troubleshooting

### If commands fail with "Permission denied":

1. **For Docker:** Make sure you're running with appropriate permissions or use `sudo` if needed
2. **Check container is running:**
   ```bash
   docker ps
   ```
3. **Try running as root inside container:**
   ```bash
   docker exec -u root container-name chown -R www-data:www-data /var/www/html/public/assets/deposits
   docker exec -u root container-name chmod -R 775 /var/www/html/public/assets/deposits
   ```

### If directories keep getting reset:

The directories should persist, but if they're being reset, make sure:
- The directories are created in a volume that persists
- The entrypoint script includes the directory creation (which we've already added)

## If You Don't Have SSH Access

If you don't have SSH access:
1. Use your hosting control panel's file manager
2. Create the directories manually: `public/assets/deposits/payment-proofs`
3. Set permissions to 775 via the file manager
4. Contact your hosting provider to set proper ownership


