# Laview

  this is a OOTB(Out Of The Box) project, just clone and take a few steps to run a Front end separation frame. This project based on iviwe-admin 2.1 and laravel5.6.

# Functions
- login and logout
- user management(include user & role & permission)

# File Structures
```
frontend  前端iview项目
├── build  项目构建配置
├── config  开发相关配置
├── public  打包所需静态资源
└── src
    ├── api  AJAX请求
    └── assets  项目静态资源
        ├── icons  自定义图标资源
        └── images  图片资源
    ├── components  业务组件
    ├── config  项目运行配置
    ├── directive  自定义指令
    ├── libs  封装工具函数
    ├── locale  多语言文件
    ├── mock  mock模拟数据
    ├── router  路由配置
    ├── store  Vuex配置
    ├── view  页面文件
    └── tests  测试相关
laview  后端laravel项目
```

# Install
1.Please make sure that you had install `node.js` and `composer`, then `cd` into `frontend` dictionary and run

```
npm installl
```
Change base config of the frondend(`frontend/src/config`), the detail of configuration and how to DIY your frontend, please go iview-admin to see the detail.

2.`cd` into `frontend` dictionary and run

```
composer install
```
copy the `.env.expample` to `.env`, and make sure you had config the DB parameters then

```
php artisan key:generate
php artisan migrate
php artisan passport:keys
php artisan db:seed //generate the original user data
```
you can go to db , you will know the client secret and personal secret, copy this to you `.env` file.(I don't complete the seed data in this version, so you have to create a user and give him `super_admin` permission).

# Others
`Laview` based on following plugins or services:
- vue
- iview
- barryvdh/laravel-cors
- dingo/api
- intervention/image
- laravel/passport
- spatie/laravel-permission

# License
The MIT License (MIT). Please see License File for more information.

