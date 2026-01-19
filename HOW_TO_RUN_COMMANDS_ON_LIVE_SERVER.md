# How to Run Commands on Live Server - Step by Step Guide

## Step 1: Connect to Your Live Server

You need to connect to your live server using SSH (Secure Shell). Here's how:

### On Windows:

1. **Open PowerShell or Command Prompt**
   - Press `Windows Key + R`
   - Type `powershell` or `cmd`
   - Press Enter

2. **Connect via SSH:**
   ```bash
   ssh username@your-server-ip
   ```
   
   **Example:**
   ```bash
   ssh root@72.61.117.178
   ```
   
   Or if you have a domain:
   ```bash
   ssh root@yourdomain.com
   ```

3. **Enter your password** when prompted

### On Mac/Linux:

1. **Open Terminal**
   - Press `Cmd + Space` (Mac) or `Ctrl + Alt + T` (Linux)
   - Type `terminal` and press Enter

2. **Connect via SSH:**
   ```bash
   ssh username@your-server-ip
   ```

### If You Don't Know Your Server Details:

- **Server IP:** Check your hosting provider's dashboard/control panel
- **Username:** Usually `root`, `ubuntu`, or provided by your hosting
- **Password/SSH Key:** Provided by your hosting provider

---

## Step 2: Once Connected to Server

After successfully connecting, you'll see a prompt like:
```
root@server:~#
```

**This is where you run all the commands!**

---

## Step 3: Find Your Docker Container

Run this command to see all running containers:

```bash
docker ps
```

**You'll see output like:**
```
CONTAINER ID   IMAGE              COMMAND                  CREATED        STATUS        PORTS                    NAMES
abc123def456   investment-app     "/entrypoint.sh ..."     2 days ago     Up 2 days     0.0.0.0:80->80/tcp      investment-app-1
```

**Note the container name** (last column) - in this example it's `investment-app-1`

---

## Step 4: Run the Fix Commands

### Option A: Quick One-Liner (Easiest)

Replace `investment-app-1` with YOUR container name from Step 3:

```bash
docker exec investment-app-1 sh -c "mkdir -p /var/www/html/public/assets/deposits/payment-proofs /var/www/html/public/assets/withdrawals && chown -R www-data:www-data /var/www/html/public/assets/deposits /var/www/html/public/assets/withdrawals && chmod -R 775 /var/www/html/public/assets/deposits /var/www/html/public/assets/withdrawals"
```

### Option B: If Using Docker Compose

If you're using `docker-compose`, run:

```bash
docker-compose exec app sh -c "mkdir -p /var/www/html/public/assets/deposits/payment-proofs /var/www/html/public/assets/withdrawals && chown -R www-data:www-data /var/www/html/public/assets/deposits /var/www/html/public/assets/withdrawals && chmod -R 775 /var/www/html/public/assets/deposits /var/www/html/public/assets/withdrawals"
```

### Option C: Step by Step (If One-Liner Doesn't Work)

1. **Enter the container:**
   ```bash
   docker exec -it investment-app-1 sh
   ```
   (Replace `investment-app-1` with your container name)

2. **You'll see a prompt like:** `/var/www/html #`

3. **Run these commands one by one:**
   ```bash
   mkdir -p /var/www/html/public/assets/deposits/payment-proofs
   mkdir -p /var/www/html/public/assets/withdrawals
   chown -R www-data:www-data /var/www/html/public/assets/deposits
   chown -R www-data:www-data /var/www/html/public/assets/withdrawals
   chmod -R 775 /var/www/html/public/assets/deposits
   chmod -R 775 /var/www/html/public/assets/withdrawals
   ```

4. **Verify it worked:**
   ```bash
   ls -la /var/www/html/public/assets/deposits/payment-proofs
   ```

5. **Exit the container:**
   ```bash
   exit
   ```

---

## Step 5: Verify the Fix

Run this to check if directories were created:

```bash
docker exec investment-app-1 ls -la /var/www/html/public/assets/deposits/
```

You should see:
```
drwxrwxr-x  www-data www-data  payment-proofs
```

---

## Step 6: Test the Deposit Upload

1. Go to your live website
2. Try to upload a deposit payment proof
3. It should work now without the 500 error!

---

## Troubleshooting

### "docker: command not found"
- Docker is not installed or not in PATH
- Try: `sudo docker ps` or check if Docker is running

### "Cannot connect to the Docker daemon"
- Docker service might not be running
- Try: `sudo systemctl start docker`

### "No such container"
- Container name is wrong
- Run `docker ps` again to get the correct name

### "Permission denied"
- Try with sudo: `sudo docker exec ...`
- Or run as root: `docker exec -u root container-name ...`

---

## Summary: Where to Run Commands

```
Your Computer (Local)
    ↓
    SSH Connection
    ↓
Live Server Terminal/SSH Session  ← RUN COMMANDS HERE
    ↓
    docker exec ...
    ↓
Inside Docker Container
```

**Important:** All commands are run in the **SSH terminal session** connected to your live server, NOT on your local computer!

---

## Need Help?

If you're stuck:
1. Check your hosting provider's documentation for SSH access
2. Contact your hosting support for SSH credentials
3. Make sure Docker is installed and running on the server

