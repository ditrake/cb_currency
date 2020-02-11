FROM nginx:alpine

RUN apk add --update --no-cache shadow
COPY ./docker/dev/nginx/nginx.conf     /etc/nginx/nginx.conf
COPY ./docker/dev/nginx/symfony.conf   /etc/nginx/conf.d/default.conf

RUN echo "upstream php-upstream { server app:9000; }" > /etc/nginx/conf.d/upstream.conf

WORKDIR /var/www/app
