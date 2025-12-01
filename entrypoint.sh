cat > entrypoint.sh << 'EOF'
#!/bin/sh

# Wait for filesystem
sleep 2

# Fix permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database 2>/dev/null || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true
mkdir -p /var/www/html/storage/logs
touch /var/www/html/storage/logs/lumen.log
chown www-data:www-data /var/www/html/storage/logs/lumen.log

# Run migrations
php artisan migrate --force --no-interaction

# Start everything
exec /usr/bin/supervisord -c /etc/supervisord.conf
EOF

chmod +x entrypoint.sh