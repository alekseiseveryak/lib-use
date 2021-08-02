FROM ubuntu:16.04

RUN apt-get update \
    && apt-get install -y nginx

ENTRYPOINT ["/usr/sbin/nginx", "-c", "/var/dev/nginx.conf"]
