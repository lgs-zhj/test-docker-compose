# test-docker-compose
配置wordpress数据库信息>>vi html/wp-config.php

配置启动文件dockers-compose.yml 
>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>..
version: '3'
services:
  phpfpm:   ##fpm-php服务
    build:
      context: ./php  ##镜像构建路径
      dockerfile: Dockerfile  ##镜像构建文件
    image: fpm-php   ##构建镜像的名字 
    container_name: phpfpm    ##启动容器的名字
    restart: always
    volumes:
      - ./html:/var/www/html   ##webphp文件存放地址映射
    ports:
      - "9000:9000"   
    stdin_open: true
    networks:
      - test-network      ##容器网桥，需先行构建命令参考docker network test-network --subnet=172.99.99.1/24 --gateway=172.99.99.1
  mysql-db:   ##数据库服务
    container_name: mysql-docker
    image: mysql:5.7
    ports:
      - "3306:3306"
    environment:
      MYSQL_ROOT_PASSDWORD: lgs@2022  ##root密码
      MYSQL_ALLOW_EMPTY_PASSWORD: 0
      MYSQL_RANDOM_ROOT_PASSWORD: 0
     #MYSQL_ROOT_HOST: ${MYSQL_ROOT_HOST}
      MYSQL_USER: wordpress   ##创建mysql用户，具有和root一样权限
      MYSQL_PASSWORD: wordpress  ##创建用户的密码
      MYSQL_DATABASE: wordpress  ##创建数据库
    volumes:
      - ./db:/var/lib/mysql     ##映射路径到数据库工作路径
      - ./config:/etc/mysql/conf.d  ##映射my.conf文件（这里无数据）
    networks:
      - test-network
  nginx:
    container_name: nginx
    image: nginx:1.22.0
    restart: always
    tty: true
    links:
      - phpfpm:phpfmp   ##链接php容器（必要，不然无法调用php容器）
    environment:
      NGINX_ENV: TZ "Asia/Shanghai"
    ports:
      - 80:80
      - 443:443
    volumes:
      - ./html:/usr/share/nginx/html ##映射webphp文件到工作路径
     #- ./conf/nginx.conf:/etc/nginx/nginx.conf
      - ./conf.d:/etc/nginx/conf.d
      - ./logs:/var/log/nginx   ##映射log日志存放日志
    networks:
      - test-network
networks:
  test-network:
    external: true
<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
启动容器：docker-compose （需要docker环境和docker-compose，自行百度）
浏览器访问http://真机ip
