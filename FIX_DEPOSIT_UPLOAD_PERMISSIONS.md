# Fix Deposit Upload Directory Permissions on Live Server

## Quick Fix (SSH Commands)

Connect to your live server via SSH and run these commands:

```bash
# Navigate to your project directory
cd /path/to/your/project

# Create the directory if it doesn't exist
mkdir -p public/assets/deposits/payment-proofs

# Set proper permissions (755 for directories, 644 for files)
chmod -R 755 public/assets/deposits/payment-proofs

# Set ownership to web server user (adjust based on your server)
# For Apache:
sudo chown -R www-data:www-data public/assets/deposits/payment-proofs

# For Nginx:
sudo chown -R nginx:nginx public/assets/deposits/payment-proofs

# Or if using a different user (check with: ps aux | grep -E 'nginx|apache|httpd'):
sudo chown -R $(ps aux | grep -E 'nginx|apache|httpd' | grep -v grep | awk '{print $1}' | head -1):$(ps aux | grep -E 'nginx|apache|httpd' | grep -v grep | awk '{print $1}' | head -1) public/assets/deposits/payment-proofs
```

## Alternative: Create Directory via Artisan Command

You can also create an Artisan command to set up the directory:

```bash
php artisan make:command SetupDepositDirectory
```

Then run:
```bash
php artisan setup:deposit-directory
```

## Verify Permissions

After running the commands, verify with:

```bash
ls -la public/assets/deposits/payment-proofs
```

You should see permissions like `drwxr-xr-x` and the owner should be your web server user.

## If You Don't Have SSH Access

If you don't have SSH access, you can create the directory manually via your hosting control panel's file manager, then set permissions to 755.


