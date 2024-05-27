# After deployment:

Generate keys
```
./bin/console lexik:jwt:generate-keypair
```

Create DB
```
./bin/console doctrine:schema:update -f
```

Create admin
```
./bin/console security:create-admin
```
