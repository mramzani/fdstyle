# hypersmart
هایپراسمارت یک نرم افزار حسابداری آنلاین با قابلیت صندوق فروشگاه و به همراه فروشگاه اینترنتی می‌باشد.
این پلتفرم به فروشگاه‌داران فیزیکی کمک می‌کند تا درکنار فروش حضوری، بدون دغدغه اتمام موجودی کالاها فروش آنلاین داشته باشند.
## Installation Step By Step In LIARA:
1. create platform in liara console:
2. create disk for platform
3. set disk name in liara.json file
4. create domain for platform and config dns
5. activation ssl for domain and create www subdomain
6. create database in liara console
7. config in setting platform (.env)
8. config "app" object in liara.json file
9. run clear command:
```
php artisan clear-compiled
php artisan cache:clear 
php artisan config:clear
php artisan route:clear
php artisan view:clear
```
10. run ``` npx liara login ``` && ``` npx liara deploy ```
11. upload public/storage file to disk with FileZilla.
12. create BUCKET, ACCESS KEY,SECRET KEY
13. create domain and config dns for bucket
14. config value in setting platform (.env)
```
FILESYSTEM_DRIVER=liara
FILESYSTEM_CLOUD=liara
ENDPOINT_URL=storage.iran.liara.space
BUCKET_NAME=
ACCESS_KEY=
SECRET_KEY=
DEFAULT_REGION=us-east-1
DOMAIN_NAME=http://DOMAIN_NAME
```
15. run ```php artisan migrate``` and ```php artisan db:seed```
16. test all program. END!

## Change Log:
#### ver0.0.3:
add Units management
#### ver0.0.2:
add taxes management
#### ver0.0.1:
add warehouses management
