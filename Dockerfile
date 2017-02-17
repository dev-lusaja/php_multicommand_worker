FROM urbania_web

MAINTAINER Oscar Sanchez <janoone52@gmail.com>

RUN mkdir -p /usr/local/opt/apps
WORKDIR /usr/local/opt/apps/workers

EXPOSE 80