# Yii2-site-template
Что бы развернуть сайт, необходимо выполнить следующие команды:

Получаем библиотеки необходимых версий
```
composer update
```

Инициализируем конфиги, первоначально задав необходимые параметры в environments
```
php init
```

Выполняем миграции
```
php yii migrate
```

Создаем необходимую структуру ролей Rbac
```
php yii rbac-manager/init-roles
```

После необходимо добавить пользователя (админа) для авторизации в панели управления:
```
php yii user/create <email> <username> [password] MASTER
```

Так как данные авторизации для админки и публичной части одинаковы, необходимо сделать идентичными cookieValidationKey.
Т.е. необходимо скопировать данное значение из backend/config/main-local.php в frontend/config/main-local.php
```
frontend.components.request.cookieValidationKey = backend.components.request.cookieValidationKey
```

Основные параметры:
```
backend/config/params.php
------------------------
robotsTxtFiles - Массив с путями, до файлов robots.txt для их управления в соответствующем контроллере
```
```
common/config/params-local.php
------------------------
contactEmailSource - Email адрес, который будет написан в отправителе письма
```

```
common/config/main-local.php
------------------------
backendUrlManager.baseUrl - Ссылка до панели управления сайтом
frontendUrlManager.baseUrl - Ссылка до публичной части сайта
modules.user.fromEmail - Email адрес, который будет написан в отправителе письма
```
