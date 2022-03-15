FROM debian:9.12
RUN apt-get update && apt-get install -y nginx
VOLUME ["/etc/nginx/conf.d", "/var/log/nginx", "/var/www"]
EXPOSE 80
STOPSIGNAL SIGTERM
CMD ["nginx", "-g", "daemon off;"]