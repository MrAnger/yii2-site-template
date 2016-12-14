# yii2-site-template
Базовый шаблон сайта на Yii 2.* версии с раздельным frontend и backend на одном домене

Что бы развернуть базовый шаблон, необходимо выполнить следующие команды:

Получаем библиотеки необходимых версий
```
composer update
```

Инициализируем конфиги, первоначально задав необходимые параметры в environments
```
php init
```

Создаем необходимые таблицы для модуля пользователей
```
php yii migrate/up --migrationPath=@vendor/dektrium/yii2-user/migrations
```

Создаем необходимые таблицы для Rbac модуля
```
php yii migrate --migrationPath=@yii/rbac/migrations/
```

Создаем необходимую структуру ролей Rbac
```
php yii rbac-manager/init-roles
```

После необходимо добавить пользователя (админа) для авторизации в панели управления:
```
php yii user/create admin@yoursite.ru admin_login admin_password
```

После создания пользователя, необходимо выдать ему права главного администратора:
```
php yii rbac-manager/set-user-as-master admin_email
```