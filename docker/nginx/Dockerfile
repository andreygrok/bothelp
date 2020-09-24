FROM nginx:latest AS nginx_bothelp

RUN mkdir -p /var/log/nginx/bothelp

RUN mkdir /etc/nginx/sites

COPY ./nginx.conf /etc/nginx/nginx.conf
COPY ./sites/bothelp.conf /etc/nginx/sites/bothelp.conf
COPY ./app.conf /etc/nginx
COPY ./assets.conf /etc/nginx
COPY ./fastcgi_params.conf /etc/nginx

RUN rm -f /etc/nginx/conf.d/default.conf

RUN apt-get update && apt-get install -y vim curl